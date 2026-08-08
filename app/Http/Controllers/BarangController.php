<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Branch;
use App\Models\Kategori;
use App\Models\LeadSource;
use App\Models\Satuan;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_barang', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . strtoupper($row->nm_barang) . '</div>';
                })
                ->addColumn('satuan', function ($row) {
                    return strtoupper($row->satuan);
                })
                ->addColumn('kd_kategori', function ($row) {
                    return strtoupper($row->kd_kategori);
                })
                ->addColumn('stok', function ($row) {
                    return '<div style="text-align:right;">' . number_format($row->stok) . '</div>';
                })
                ->addColumn('harga_beli', function ($row) {
                    return '<div style="text-align:right;">' . number_format($row->harga_beli) . '</div>';
                })
                ->addColumn('harga_jual', function ($row) {
                    return '<div style="text-align:right;">' . number_format($row->harga_jual) . '</div>';
                })
                ->addColumn('harga_reseller', function ($row) {
                    return '<div style="text-align:right;">' . number_format($row->harga_reseller) . '</div>';
                })
                ->addColumn('kd_supplier', function ($row) {
                    return $row->supplier?->nm_supplier ?? '';
                })

                ->addColumn('action', function ($row) {
                    $button = '
                        <center>
                        <button
                            onclick="editData(\'' . $row->kd_barang . '\')"
                            class="btn btn-warning btn-sm"
                            title="Edit">
                            <i class="mdi mdi-pencil"></i>
                        </button>

                        <button
                            onclick="deleteData(\'' . $row->kd_barang . '\')"
                            class="btn btn-danger btn-sm"
                            title="Hapus">
                            <i class="mdi mdi-delete"></i>
                        </button>
                            </center>
                        ';

                    return $button;
                })
                ->rawColumns(['action', 'harga_beli', 'harga_jual', 'harga_reseller', 'nm_barang', 'stok'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'barang';
        $supplier = Supplier::all();
        $kategori = Kategori::all();
        $satuan = Satuan::all();
        return view('pages.barang.index', compact('view', 'supplier', 'kategori', 'satuan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $validated = $request->validate([
            'barcode' => 'nullable',
            'nm_barang' => 'required',
            'kd_kategori' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'satuan' => 'required',
            'stok' => 'nullable',
            'konversi' => 'nullable',
            'kd_supplier' => 'required',
            'harga_reseller' => 'nullable'
        ]);


        DB::beginTransaction();
        try {
            $kodeBarang =  generateKode(
                'master_barang',
                'kd_barang',
                'BR',
                5
            );

            $input['kd_barang'] = $kodeBarang;
            $input['tanggal'] = date('Y-m-d');
            $input['gambar'] = '';
            $input['alamat'] = '';
            $input['konversi'] = $request->konversi ?? 1;
            $input['hj'] =  $request->harga_jual;
            $input['harga_member'] =  $request->harga_jual;
            $input['diskon_member'] = $request->harga_jual;
            $input['diskon'] = 0;
            $input['harga_reseller'] = $request->harga_reseller ?? $request->harga_jual;
            $input['barcode'] = $request->barcode ?? strtoupper(uniqid());
            $input['stok'] = $request->stok ?? 0;
            $input['nm_barang'] = strtoupper($request->nm_barang);
            $input['kd_kategori'] = strtoupper($request->kd_kategori);

            Barang::create($input);


            Stok::create([
                "kd_barang" => $kodeBarang,
                "nota" => "SLD-AWAL",
                "type" => "SLD01",
                "masuk" => $request->stok ?? 0,
                "keluar" => 0,
                "tanggal" => date('Y-m-d'),
                "lokasi" => "SALDO-AWAL"
            ]);


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
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
    public function edit(string $kdBarang)
    {
        $data = Barang::where('kd_barang', $kdBarang)->first();
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $request->all();
        $barang = Barang::where('kd_barang', $input['kd_barang'])->first();

        $validated = $request->validate([
            'barcode' => 'nullable',
            'nm_barang' => 'required',
            'kd_kategori' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'satuan' => 'required',
            'stok' => 'nullable',
            'konversi' => 'nullable',
            'kd_supplier' => 'required',
            'harga_reseller' => 'nullable'
        ]);



        $barang->barcode = $input['barcode'];
        $barang->nm_barang = strtoupper($input['nm_barang']);
        $barang->kd_kategori = strtoupper($input['kd_kategori']);
        $barang->harga_beli = $input['harga_beli'];
        $barang->harga_jual = $input['harga_jual'];
        $barang->satuan = $input['satuan'];
        $barang->hj = $input['harga_jual'];
        $barang->harga_member = $input['harga_jual'];
        $barang->diskon_member = $input['harga_jual'];
        $barang->harga_reseller = $request->harga_reseller ?? $request->harga_jual;
        $barang->kd_supplier = $input['kd_supplier'];
        $barang->save();




        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {

            Barang::where('kd_barang', $input['kd_barang'])->delete();

            Stok::where('kd_barang', $input['kd_barang'])->where('type', 'SLD01')->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
