<?php

namespace App\Http\Controllers;

use App\Exports\PenjualanExport;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Pengaturan;
use App\Models\Pengguna;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\Piutang;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
        $users = Pengguna::all();

        return view('pages.penjualan.daftar.index', compact('view', 'customers', 'users'));
    }


    public function table(Request $request)
    {

        if ($request->ajax()) {

            $query = Penjualan::query()
                ->select('penjualan.*')

                // Hindari N+1 query
                ->with(['pelanggan', 'kasir'])

                // Total penjualan dari detail
                ->addSelect([
                    'item_amount' => DB::table('penjualan_post')
                        ->selectRaw('COALESCE(SUM(penjualan_post.total), 0)')
                        ->whereColumn(
                            'penjualan_post.nota',
                            'penjualan.nota'
                        ),

                    // Total HPP = jumlah x modal
                    'hpp' => DB::table('penjualan_post')
                        ->selectRaw('COALESCE(SUM(penjualan_post.jumlah * penjualan_post.modal), 0)')
                        ->whereColumn(
                            'penjualan_post.nota',
                            'penjualan.nota'
                        ),
                ]);

            // FILTER TANGGAL
            if ($request->tanggal_dari) {
                $query->where(
                    'penjualan.tanggal',
                    '>=',
                    $request->tanggal_dari
                );
            }

            if ($request->tanggal_sampai) {
                $query->where(
                    'penjualan.tanggal',
                    '<=',
                    $request->tanggal_sampai
                );
            }

            // FILTER CUSTOMER
            if ($request->customer) {
                $query->where(
                    'penjualan.kd_pelanggan',
                    $request->customer
                );
            }

            // FILTER STATUS
            if ($request->status) {
                $query->where(
                    'penjualan.status_pembayaran',
                    $request->status
                );
            }

            // FILTER KASIR
            if ($request->kasir) {
                $query->where(
                    'penjualan.kd_user',
                    $request->kasir
                );
            }

            if ($request->deviasi == '1') {
                $query->whereRaw("
        (
            SELECT COALESCE(SUM(pp.total), 0)
            FROM penjualan_post pp
            WHERE pp.nota = penjualan.nota
        ) > penjualan.belanja
    ");
            }

            // Filter HPP minus
            if ($request->hpp == 'minus') {
                $query->whereRaw("
        (
            SELECT COALESCE(SUM(pp.jumlah * pp.modal), 0)
            FROM penjualan_post pp
            WHERE pp.nota = penjualan.nota
        ) < 0
    ");
            }

            $query->orderBy('penjualan.id', 'desc');

            return DataTables::of($query)

                ->addIndexColumn()

                ->addColumn('tanggal', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal));
                })

                ->addColumn('jam', function ($row) {
                    return $row->jam
                        ? date('H:i:s', strtotime($row->jam))
                        : '-';
                })

                ->addColumn('kd_pelanggan', function ($row) {
                    return '<div style="white-space:normal;width:180px;">'
                        . ($row->pelanggan?->nm_pelanggan ?? '')
                        . '</div>';
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

                ->addColumn('status', function ($row) {
                    return $row->status_pembayaran;
                })

                ->addColumn('kd_user', function ($row) {
                    return $row->kasir?->nama ?? '';
                })

                // TOTAL PENJUALAN DETAIL
                ->addColumn('item_amount', function ($row) {
                    return number_format($row->item_amount);
                })

                // SELISIH HEADER DENGAN DETAIL
                ->addColumn('deviation', function ($row) {
                    return number_format(
                        $row->item_amount - $row->belanja
                    );
                })

                // HARGA POKOK PENJUALAN
                ->addColumn('hpp', function ($row) {
                    return number_format($row->hpp);
                })

                // MARGIN NOMINAL
                ->addColumn('margin', function ($row) {
                    return number_format(
                        $row->belanja - $row->hpp
                    );
                })

                // PERSENTASE MARGIN
                // ->addColumn('margin_percent', function ($row) {

                //     if ((float)$row->belanja <= 0) {
                //         return '0%';
                //     }

                //     $margin = $row->belanja - $row->hpp;

                //     $persentase = ($margin / $row->belanja) * 100;

                //     return number_format($persentase, 2) . '%';
                // })

                ->addColumn('action', function ($row) {

                    $nota = $row->nota;

                    $button = '
                <center>

                <a target="_blank"
                   href="' . url('penjualan/struk/' . $nota) . '">
                    <button class="btn btn-success btn-sm"
                        title="Print">
                        <i class="mdi mdi-printer"></i>
                    </button>
                </a>

                <button
                    onclick="detailPenjualan(\'' . $nota . '\')"
                    class="btn btn-info btn-sm"
                    title="Detail">
                    <i class="mdi mdi-file"></i>
                </button>

                <button
                    onclick="hapusPenjualan(\'' . $nota . '\')"
                    class="btn btn-danger btn-sm"
                    title="Hapus">
                    <i class="mdi mdi-delete"></i>
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

            $user = session('kd_pengguna');
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
                'jam' => date('Y-m-d H:i:s'),
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
                $piutang->keterangan = $request->keterangan ?? '';
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



        $pengaturan = Pengaturan::first();

        $view = 'pages.penjualan.struk78';

        // Kalau setting printer = besar
        if ($pengaturan?->printer_setting === 'besar') {
            $view = 'pages.penjualan.struk';
        }

        // Kalau kecil atau belum ada → struk_kecil
        return view($view, compact(
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


    public function exportExcel(Request $request)
    {
        return Excel::download(new PenjualanExport($request), 'penjualan-' . date('Y-m-d-His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Penjualan::query();

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_dari
            );
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_sampai
            );
        }

        // Filter customer
        if ($request->filled('customer')) {
            $query->where(
                'kd_pelanggan',
                $request->customer
            );
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where(
                'status_pembayaran',
                $request->status
            );
        }

        // Filter kasir
        if ($request->filled('kasir')) {
            $query->where(
                'kd_user',
                $request->kasir
            );
        }

        $penjualan = $query
            ->with([
                'pelanggan',
                'kasir'
            ])
            ->orderBy('tanggal', 'desc')
            ->get();


        $pdf = Pdf::loadView(
            'pages.penjualan.daftar.pdf',
            [
                'penjualan' => $penjualan,

                'tanggal_dari' =>
                $request->tanggal_dari,

                'tanggal_sampai' =>
                $request->tanggal_sampai,

                'status' =>
                $request->status,

                'customer_nama' =>
                $request->customer,

                'kasir_nama' =>
                $request->kasir,
            ]
        );


        $pdf->setPaper(
            'A4',
            'landscape'
        );


        return $pdf->stream(
            'penjualan-' .
                date('Y-m-d-His') .
                '.pdf'
        );
    }


    public function hapus(Request $request)
    {
        $input = $request->all();
        $nota = $input['nota'];

        DB::beginTransaction();
        try {


            Penjualan::where('nota', $nota)->delete();
            PenjualanItem::where('nota', $nota)->delete();

            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "Hapus Berhasil"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ]);
        }
    }


    public function barangBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barang = Barang::where('barcode', $request->barcode)
            ->first();

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang dengan barcode tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $barang,
        ]);
    }



    public function double(Request $request)
    {
        $input = $request->validate([
            'nota' => 'required|string',
        ]);

        try {

            $result = DB::transaction(function () use ($input) {

                $penjualan = Penjualan::where('nota', $input['nota'])
                    ->orderBy('id', 'asc')
                    ->lockForUpdate()
                    ->get();

                if ($penjualan->count() <= 1) {
                    return [
                        'success' => true,
                        'message' => 'Nota penjualan tidak double',
                    ];
                }

                // Hanya proses kasus dua baris identik
                if ($penjualan->count() != 2) {
                    throw new \Exception(
                        'Jumlah duplikat bukan 2, perlu pemeriksaan manual'
                    );
                }

                $pertama = $penjualan->first();
                $kedua = $penjualan->last();

                // Pastikan nominal kedua baris identik
                foreach (
                    [
                        'belanja',
                        'subtotal',
                        'bayar',
                        'total_discount'
                    ] as $field
                ) {

                    if (bccomp(
                        (string) $pertama->$field,
                        (string) $kedua->$field,
                        2
                    ) !== 0) {
                        throw new \Exception(
                            "Nilai {$field} berbeda, periksa manual"
                        );
                    }
                }

                // Gabungkan nilai kedua record
                $pertama->belanja =
                    $pertama->belanja + $kedua->belanja;

                $pertama->subtotal =
                    $pertama->subtotal + $kedua->subtotal;

                $pertama->bayar =
                    $pertama->bayar + $kedua->bayar;

                $pertama->total_discount =
                    $pertama->total_discount + $kedua->total_discount;

                $pertama->save();

                // Hapus hanya header kedua
                $kedua->delete();

                return [
                    'success' => true,
                    'message' => 'Penjualan double berhasil digabungkan',
                    'nota' => $pertama->nota,
                    'id_dipertahankan' => $pertama->id,
                    'id_dihapus' => $kedua->id,
                    'total_belanja' => $pertama->belanja,
                ];
            });

            return response()->json($result);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
