<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengguna::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_pengguna', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . $row->nm_pengguna . '</div>';
                })
                ->addColumn('nama', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . strtoupper($row->nama) . '</div>';
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
                ->rawColumns(['action', 'nm_pengguna', 'alamat', 'nama'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'pengguna';
        
        return view('pages.pengguna.index', compact('view'));
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
            'nm_pengguna' => 'required',
            'password' => 'required|min:6',
            'nama' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'level' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $kode =  generateKode(
                'master_pengguna',
                'kd_pengguna',
                'USR',
                6
            );

            $input['nama'] = strtoupper($request->nama);
            $input['kd_pengguna'] = $kode;
            $input['password'] = md5($request->password);
            Pengguna::create($input);

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
        $data = Pengguna::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $input = $request->all();

        $data = Pengguna::find($id);

        $validated = $request->validate([
            'nm_pengguna' => 'required',
            'password' => 'nullable|min:6',
            'nama' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'level' => 'required',
        ]);


        DB::beginTransaction();
        try {
            
            $data->nm_pengguna = $input['nm_pengguna'];
            $data->nama = strtoupper($input['nama']);
            if(! empty($input['password'])) {
                $data->password = md5($request->password);
            }
            $data->alamat = $input['alamat'];
            $data->telepon = $input['telepon'];
            $data->level = $input['level'];
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
       return Pengguna::destroy($id);
    }
}
