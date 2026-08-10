<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Piutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'nota' => 'required',
            'pelanggan' => 'required',
            'nilai_nota' => 'required',
            'pembayaran' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            $lastNota = Pembayaran::orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNumber = $lastNota
                ? ((int) substr($lastNota->no_pembayaran, 5)) + 1
                : 1;

            $user = session('kd_pengguna');
            $kode = substr($user, -3);

            $nota = 'Y-' . $kode . str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );


            $piutang = Piutang::where('nota', $input['nota'])->first();


            $p = new Pembayaran;
            $p->no_pembayaran = $nota;
            $p->nota = $input['nota'];
            $p->pelanggan = $input['pelanggan'];
            $p->nilai_nota = $piutang->sisa;
            $p->pembayaran = $input['pembayaran'];

            
            $sisa = (int)$piutang->sisa - (int)$input['pembayaran'];
            $p->sisa = $sisa;
            $p->tanggal = $input['tanggal'];
            $p->keterangan = $input['keterangan'] ?? '';
            $p->kd_user = session('kd_pengguna');
            $p->save();

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Pembayaran berhasil"
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
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
}
