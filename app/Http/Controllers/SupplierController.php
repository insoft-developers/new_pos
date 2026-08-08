<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
     public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_supplier', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . strtoupper($row->nm_supplier) . '</div>';
                })
                ->addColumn('alamat', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . strtoupper($row->alamat) . '</div>';
                })
                
                ->addColumn('telepon', function ($row) {
                    return $row->telepon;
                })

                ->addColumn('action', function ($row) {
                    $button = '
                        <center>
                        <button
                            onclick="editData(' . $row->id . ')"
                            class="btn btn-warning btn-sm"
                            title="Edit">
                            <i class="mdi mdi-pencil"></i>
                        </button>

                        <button
                            onclick="deleteData(' . $row->id . ')"
                            class="btn btn-danger btn-sm"
                            title="Hapus">
                            <i class="mdi mdi-delete"></i>
                        </button>
                            </center>
                        ';

                    return $button;
                })
                ->rawColumns(['action', 'nm_supplier', 'alamat'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'supplier';
        
        return view('pages.supplier.index', compact('view'));
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
            'nm_supplier' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $kode =  generateKode(
                'master_supplier',
                'kd_supplier',
                'SPL',
                6
            );

            $input['kd_supplier'] = $kode;
            $input['alamat2'] = "";
            $input['kontak'] = $input['nm_supplier'];
            $input['nm_supplier'] = strtoupper($request->nm_supplier);
            $input['kunjungan'] = 0;
            Supplier::create($input);

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
    public function edit(string $id)
    {
        $data = Supplier::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $input = $request->all();

        $data = Supplier::find($id);

        $validated = $request->validate([
            'nm_supplier' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);


        DB::beginTransaction();
        try {
            
            $data->nm_supplier = strtoupper($input['nm_supplier']);
            $data->alamat = $input['alamat'];
            $data->telepon = $input['telepon'];
            $data->save();

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
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
       return Supplier::destroy($id);
    }
}
