<?php

namespace App\Http\Controllers;

use App\Exports\PembelianExport;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianItem;
use App\Models\Pengguna;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'pembelian';
        $supplier = Supplier::all();
        $users = Pengguna::all();
        return view('pages.pembelian.daftar.index', compact('view', 'supplier', 'users'));
    }


    public function table(Request $request)
    {
        if ($request->ajax()) {
            $query = Pembelian::query();
            if ($request->tanggal_dari) {
                $query->whereDate(
                    'tanggal',
                    '>=',
                    $request->tanggal_dari
                );
            }

            if ($request->tanggal_sampai) {
                $query->whereDate(
                    'tanggal',
                    '<=',
                    $request->tanggal_sampai
                );
            }

            if ($request->supplier) {
                $query->where(
                    'kd_supplier',
                    $request->supplier
                );
            }

            if ($request->kasir) {
                $query->where(
                    'kd_user',
                    $request->kasir
                );
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return date('d-m-Y', strtotime($row->tanggal));
                })
                ->addColumn('kd_supplier', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . $row->supplier?->nm_supplier ?? '' . '</div>';
                })
                ->addColumn('subtotal', function ($row) {
                    return number_format($row->subtotal);
                })

                ->addColumn('total_discount', function ($row) {
                    return number_format($row->total_discount);
                })

                ->addColumn('total_pembelian', function ($row) {
                    return number_format($row->total_pembelian);
                })

                ->addColumn('kd_user', function ($row) {
                    return $row->kasir?->nama ?? '';
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
                            title="Detail">
                            <i class="mdi mdi-file"></i>
                        </button>


                         <button
                            onclick="hapusPenjualan(\'' . $row->nota . '\')"
                            class="btn btn-danger btn-sm"
                            title="Hapus">
                            <i class="mdi mdi-delete"></i>
                        </button>
                            </center>
                        ';

                    return $button;
                })
                ->rawColumns(['action', 'kd_supplier'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $view = 'pembelian-tambah';
        $supplier = Supplier::all();
        return view('pages.pembelian.index', compact('view', 'supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $request->validate([
            'kd_supplier' => 'required',
            'tanggal' => 'required|date',
            'items' => 'required',
        ]);


        DB::beginTransaction();

        try {

            $items = json_decode(
                $request->items
            );


            if (!$items || count($items) === 0) {

                return back()
                    ->with('error', 'Barang belum ditambahkan.');
            }


            // ==========================
            // HITUNG TOTAL
            // ==========================

            $subtotal = 0;
            $totalDiscount = 0;
            $totalPembelian = 0;


            foreach ($items as $item) {

                $itemSubtotal =
                    ((float) $item->jumlah *
                        (float) $item->harga);

                $diskon =
                    (float) ($item->diskon ?? 0);

                $itemTotal =
                    $itemSubtotal - $diskon;


                $subtotal += $itemSubtotal;

                $totalDiscount += $diskon;

                $totalPembelian += $itemTotal;
            }


            // ==========================
            // NOTA
            // ==========================

            $lastNota = Pembelian::orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNumber = $lastNota
                ? ((int) substr($lastNota->nota, 5)) + 1
                : 1;

            $user = session('kd_pengguna');
            $kode = substr($user, -3);

            $nota = 'B-' . $kode . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );



            // ==========================
            // HEADER PEMBELIAN
            // ==========================

            $pembelian = Pembelian::create([

                'nota' =>
                $nota,

                'kd_supplier' =>
                $request->kd_supplier,

                'keterangan' =>
                $request->keterangan ?? '',

                'tanggal' =>
                $request->tanggal,

                'kd_user' =>
                session('kd_pengguna'),

                'status' =>
                0,

                'subtotal' =>
                $subtotal,

                'total_discount' =>
                $totalDiscount,

                'total_pembelian' =>
                $totalPembelian,

            ]);


            // ==========================
            // ITEM
            // ==========================

            foreach ($items as $item) {

                $itemSubtotal =
                    ((float) $item->jumlah *
                        (float) $item->harga);

                $diskon =
                    (float) ($item->diskon ?? 0);

                $itemTotal =
                    $itemSubtotal - $diskon;


                PembelianItem::create([

                    'nota' =>
                    $nota,

                    'kd_barang' =>
                    $item->kd_barang,

                    'barcode' =>
                    $item->barcode ?? '',

                    'nm_barang' =>
                    $item->nm_barang,

                    'satuan' =>
                    $item->satuan ?? '',

                    'jumlah' =>
                    $item->jumlah,

                    'harga' =>
                    $item->harga,

                    'subtotal' =>
                    $itemSubtotal,

                    'diskon' =>
                    $diskon,

                    'total' =>
                    $itemTotal,

                    'status' =>
                    0,

                    'grf' =>
                    0,

                ]);
            }


            DB::commit();


            return redirect(
                url('pembelian/create')
            )->with(
                'success',
                'Pembelian berhasil disimpan.'
            );
        } catch (\Throwable $e) {

            DB::rollBack();


            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function getBarangDetail($kd_barang)
    {
        $barang = Barang::where(
            'kd_barang',
            $kd_barang
        )->first();


        if (!$barang) {

            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }


        return response()->json([
            'success' => true,
            'kd_barang' => $barang->kd_barang,
            'barcode' => $barang->barcode,
            'nm_barang' => $barang->nm_barang,
            'satuan' => $barang->satuan,
            'harga_beli' => $barang->harga_beli ?? 0,
        ]);
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(new PembelianExport($request), 'pembelian-' . date('Y-m-d-His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Pembelian::query();

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

        // Filter supplier
        if ($request->filled('supplier')) {
            $query->where(
                'kd_supplier',
                $request->supplier
            );
        }


        // Filter kasir
        if ($request->filled('kasir')) {
            $query->where(
                'kd_user',
                $request->kasir
            );
        }

        $pembelian = $query
            ->with([
                'supplier',
                'kasir'
            ])
            ->orderBy('tanggal', 'desc')
            ->get();


        $pdf = Pdf::loadView(
            'pages.pembelian.daftar.pdf',
            [
                'pembelian' => $pembelian,

                'tanggal_dari' =>
                $request->tanggal_dari,

                'tanggal_sampai' =>
                $request->tanggal_sampai,

                'supplier_nama' =>
                $request->supplier,

                'kasir_nama' =>
                $request->kasir,
            ]
        );


        $pdf->setPaper(
            'A4',
            'landscape'
        );


        return $pdf->stream(
            'pembelian-' .
                date('Y-m-d-His') .
                '.pdf'
        );
    }


    // public function hapus(Request $request)
    // {
    //     $input = $request->all();
    //     $nota = $input['nota'];

    //     DB::beginTransaction();
    //     try {
            

    //         Penjualan::where('nota', $nota)->delete();
    //         PenjualanItem::where('nota', $nota)->delete();

    //         DB::commit();
    //         return response()->json([
    //             "success" => true,
    //             "message" => "Hapus Berhasil"
    //         ]);


    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return response()->json([
    //             "success" => false,
    //             "message" => $th->getMessage()
    //         ]);
    //     }

    // }
}
