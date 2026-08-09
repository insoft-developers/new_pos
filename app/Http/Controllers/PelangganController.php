<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Pelanggan::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nm_pelanggan', function ($row) {
                    return '<div style="white-space:normal;width:180px;">' . strtoupper($row->nm_pelanggan) . '</div>';
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
                ->rawColumns(['action', 'nm_pelanggan', 'alamat'])
                ->make(true);
        }
    }


    public function index()
    {
        $view = 'pelanggan';
        
        return view('pages.pelanggan.index', compact('view'));
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
            'nm_pelanggan' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $kode =  generateKode(
                'master_pelanggan',
                'kd_pelanggan',
                'CST',
                6
            );

            $input['kd_pelanggan'] = $kode;
            $input['alamat2'] = "";
            $input['kontak'] = $input['nm_pelanggan'];
            $input['grup'] = "reguler";
            $input['poin'] = 0;
            $input['deposit'] = 0;
            $input['angka'] = 1000000000;
            $input['kredit_limit'] = 1000000000;
            $input['bank_deposit'] = 0;
            $input['ktp'] = '';
            $input['gambar'] = '';
            $input['alamat_gambar'] = '';
            $input['nm_pelanggan'] = strtoupper($request->nm_pelanggan);
            Pelanggan::create($input);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => Pelanggan::all(),
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
        $data = Pelanggan::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $input = $request->all();

        $data = Pelanggan::find($id);

        $validated = $request->validate([
            'nm_pelanggan' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);


        DB::beginTransaction();
        try {
            
            $data->nm_pelanggan = strtoupper($input['nm_pelanggan']);
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
       return Pelanggan::destroy($id);
    }
}
