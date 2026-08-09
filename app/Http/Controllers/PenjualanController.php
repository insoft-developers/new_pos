<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\Piutang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function pos()
    {
        $view = 'pos';
        $customers = Pelanggan::all();

        return view('pages.penjualan.index', compact('view', 'customers'));
    }

    public function index()
    {
        $view = 'penjualan';
        $customers = Pelanggan::all();

        return view('pages.penjualan.daftar.index', compact('view', 'customers'));
    }


    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Penjualan::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal));
                })
                ->addColumn('kd_pelanggan', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . $row->pelanggan?->nm_pelanggan ?? '' . '</div>';
                })
                ->addColumn('subtotal', function ($row) {
                    return number_format($row->subtotal);
                })

                ->addColumn('total_discount', function ($row) {
                    return number_format($row->total_discount);
                })

                ->addColumn('belanja', function ($row) {
                    return number_format($row->belanja);
                })

                ->addColumn('bayar', function ($row) {
                    return number_format($row->bayar);
                })

                ->addColumn('kembali', function ($row) {
                    return number_format($row->kembali);
                })

                ->addColumn('action', function ($row) {
                    $button = '
                        <center>
                        <a target="_blank" href="' . url('penjualan/struk/' . $row->nota) . '"><button
                            class="btn btn-success btn-sm"
                            title="Print">
                            <i class="mdi mdi-printer"></i>
                        </button></a>

                        <button
                            onclick="detailPenjualan(\'' . $row->nota . '\')"
                            class="btn btn-info btn-sm"
                            title="Hapus">
                            <i class="mdi mdi-file"></i>
                        </button>
                            </center>
                        ';

                    return $button;
                })
                ->rawColumns(['action', 'kd_pelanggan'])
                ->make(true);
        }
    }

    public function barangList(Request $request)
    {
        $search = $request->input('search');
        $perPage = (int) $request->input('per_page', 20);

        $barang = Barang::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nm_barang', 'like', "%{$search}%")
                        ->orWhere('kd_barang', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->orderBy('nm_barang', 'asc')
            ->paginate($perPage);

        return response()->json($barang);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'kd_pelanggan' => 'required|string|max:10',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
                'subtotal' => 'required|numeric|min:0',
                'total_discount' => 'required|numeric|min:0',
                'belanja' => 'required|numeric|min:0',
                'bayar' => 'required|numeric|min:0',
                'tempo_hari' => 'nullable|integer|in:0,3,7,14,21,28',
                'items' => 'required|array|min:1',
                'items.*.kd_barang' => 'required',
                'items.*.jumlah' => 'required|numeric|min:1',
                'items.*.harga' => 'required|numeric|min:0',
                'items.*.disk' => 'nullable|numeric|min:0',
            ]);

            $total = (int) $request->belanja;
            $bayar = (int) $request->bayar;
            $detailSubtotal = 0;
            $detailDiscount = 0;

            foreach ($request->items as $item) {
                $qty = (int) $item['jumlah'];
                $harga = (int) $item['harga'];
                $disk = (int) ($item['disk'] ?? 0);

                $detailSubtotal += $qty * $harga;
                $detailDiscount += $disk;
            }

            $detailTotal = $detailSubtotal - $detailDiscount;

            if ($detailTotal != $total) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total transaksi tidak sesuai dengan detail barang.'
                ], 422);
            }

            $sisa = $total - $bayar;
            $tempoHari = 0;
            $jatuhTempo = null;
            $statusPembayaran = 'LUNAS';

            if ($sisa > 0) {
                $tempoHari = (int) ($request->tempo_hari ?? 0);

                if (!in_array($tempoHari, [3, 7, 14, 21, 28])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan pilih jatuh tempo 3, 7, 14, 21 atau 28 hari.'
                    ], 422);
                }

                $jatuhTempo = Carbon::parse($request->tanggal)
                    ->addDays($tempoHari)
                    ->format('Y-m-d');

                $statusPembayaran = 'BELUM LUNAS';
            }

            $kembali = abs($bayar - $total);
            $lastNota = Penjualan::orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNumber = $lastNota
                ? ((int) substr($lastNota->nota, 5)) + 1
                : 1;

            $user = 'USR-100006';
            $kode = substr($user, -3);

            $nota = 'W-' . $kode . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );


            Penjualan::create([
                'nota' => $nota,
                'kd_pelanggan' => $request->kd_pelanggan,
                'keterangan' => $request->keterangan ?? '',
                'tanggal' => $request->tanggal,
                'belanja' => $total,
                'bayar' => $bayar,
                'donasi' => 0,
                'kembali' => $kembali,
                'kd_user' => $user,
                'depo' => 0,
                'bank_deposit' => 0,
                'subtotal' => $detailSubtotal,
                'total_discount' => $detailDiscount,
                'tempo_hari' => $tempoHari,
                'jatuh_tempo' => $jatuhTempo,
                'status_pembayaran' => $statusPembayaran,
            ]);

            if ($bayar < $total) {
                $piutang = new Piutang;
                $piutang->nota = $nota;
                $piutang->kd_pelanggan = $request->kd_pelanggan;
                $piutang->keterangan = $request->keterangan;
                $piutang->tanggal = date('Y-m-d');
                $piutang->belanja = $total;
                $piutang->bayar = $bayar;
                $piutang->sisa = $kembali;
                $piutang->donasi = 0;
                $piutang->kembali = 0;
                $piutang->kd_user = $user;
                $piutang->tempo_hari = $tempoHari;
                $piutang->jatuh_tempo = $jatuhTempo;
                $piutang->save();
            }



            foreach ($request->items as $item) {
                $barang = Barang::where('kd_barang', $item['kd_barang'])
                    ->lockForUpdate()
                    ->first();

                if (!$barang) {
                    throw new \Exception(
                        "Barang {$item['kd_barang']} tidak ditemukan."
                    );
                }

                $qty = (int) $item['jumlah'];
                $harga = (int) $item['harga'];
                $disk = (int) ($item['disk'] ?? 0);

                $subtotalItem = $qty * $harga;
                $totalItem = max(0, $subtotalItem - $disk);

                PenjualanItem::create([
                    'nota' => $nota,
                    'kd_barang' => $barang->kd_barang,
                    'barcode' => $barang->barcode ?? '',
                    'nm_barang' => $barang->nm_barang,
                    'satuan' => $barang->satuan ?? '',
                    'jumlah' => $qty,
                    'harga' => $harga,
                    'modal' => (int) ($barang->harga_beli ?? 0),
                    'total' => $totalItem,
                    'status' => 1,
                    'disk' => $disk,
                    'price_type' => (int) ($item['price_type'] ?? 1),
                    'subtotal' => $subtotalItem,
                ]);

                $barang->stok = (int) $barang->stok - $qty;
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'nota' => $nota,
                'status_pembayaran' => $statusPembayaran,
                'tempo_hari' => $tempoHari,
                'jatuh_tempo' => $jatuhTempo,
                'sisa' => $sisa,
                'kembali' => $kembali,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function struk($nota)
    {
        $penjualan = Penjualan::where('nota', $nota)->firstOrFail();

        $items = PenjualanItem::where('nota', $nota)
            ->orderBy('id')
            ->get();

        $pelanggan = Pelanggan::where(
            'kd_pelanggan',
            $penjualan->kd_pelanggan
        )->first();

        return view('pages.penjualan.struk', compact(
            'penjualan',
            'items',
            'pelanggan'
        ));
    }


    public function detail(String $nota)
    {
        $penjualan = Penjualan::where('nota', $nota)
            ->first();

        if (!$penjualan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan.'
            ], 404);
        }

        $items = PenjualanItem::where('nota', $nota)
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,

            'data' => [

                'nota' => $penjualan->nota,

                'user' => $penjualan->kd_user,

                'tanggal' => date('d F Y', strtotime($penjualan->tanggal)),

                'pelanggan' => $penjualan->pelanggan->nm_pelanggan ?? 'Umum',

                'subtotal' => $penjualan->subtotal ?? 0,

                'diskon' => $penjualan->total_discount ?? 0,

                'total' => $penjualan->belanja ?? 0,

                'bayar' => $penjualan->bayar ?? 0,

                'kembali' => $penjualan->kembali ?? 0,

                'items' => $items

            ]
        ]);
    }
}
