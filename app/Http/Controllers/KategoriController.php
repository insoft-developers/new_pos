<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
     public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_kategori', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . strtoupper($row->nm_kategori) . '</div>';
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
                ->rawColumns(['action', 'nm_kategori', 'keterangan'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'kategori';
        
        return view('pages.kategori.index', compact('view'));
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
            'nm_kategori' => 'required',
            'keterangan' => 'nullable',
            
        ]);


        DB::beginTransaction();
        try {
            $kode =  generateKode(
                'master_kategori',
                'kd_kategori',
                'KT',
                3
            );

            $input['kd_kategori'] = $kode;
            $input['keterangan'] = $request->keterangan ?? '';
            $input['nm_kategori'] = strtoupper($request->nm_kategori);
            Kategori::create($input);

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
        $data = Kategori::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $input = $request->all();

        $validated = $request->validate([
            'nm_kategori' => 'required',
            'keterangan' => 'nullable',
            
        ]);


        DB::beginTransaction();
        $data = Kategori::find($id);
        try {
           
            $input['kd_kategori'] = $data->kd_kategori;
            $input['keterangan'] = $request->keterangan ?? '';
            $input['nm_kategori'] = strtoupper($request->nm_kategori);
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
       return Kategori::destroy($id);
    }
}
