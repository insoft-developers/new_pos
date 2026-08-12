<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SatuanController extends Controller
{
     public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Satuan::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_satuan', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . strtoupper($row->nm_satuan) . '</div>';
                })
                ->addColumn('keterangan', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . strtoupper($row->keterangan) . '</div>';
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
                ->rawColumns(['action', 'nm_satuan', 'keterangan'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'satuan';
        
        return view('pages.satuan.index', compact('view'));
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
            'nm_satuan' => 'required',
            'keterangan' => 'nullable',
            
        ]);


        DB::beginTransaction();
        try {
            $kode =  generateKode(
                'master_satuan',
                'kd_satuan',
                'ST',
                3
            );

            $input['kd_satuan'] = $kode;
            $input['keterangan'] = $request->keterangan ?? '';
            $input['nm_satuan'] = strtoupper($request->nm_satuan);
            Satuan::create($input);

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
        $data = Satuan::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $input = $request->all();

        $validated = $request->validate([
            'nm_satuan' => 'required',
            'keterangan' => 'nullable',
            
        ]);


        DB::beginTransaction();
        $data = Satuan::find($id);
        try {
           
            $input['kd_satuan'] = $data->kd_satuan;
            $input['keterangan'] = $request->keterangan ?? '';
            $input['nm_satuan'] = strtoupper($request->nm_satuan);
            $data->update($input);

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
       return Satuan::destroy($id);
    }
}
