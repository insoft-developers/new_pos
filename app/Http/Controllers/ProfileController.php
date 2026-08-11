<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $view = 'profile';
        return view('pages.profile.index', compact('view'));
    }


    public function profile()
    {
        $kd = session('kd_pengguna');
        $data = Pengguna::where('kd_pengguna', $kd)->first();
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $user = Pengguna::where('kd_pengguna', $input['kd_pengguna'])->first();
        $user->nama = $input['nama'];
        $user->telepon = $input['telepon'];
        $user->alamat = $input['alamat'];
        $user->save();

        return response()->json([
            "success" => true
        ]);
    }


    public function change()
    {
        $view = 'password';
        return view('pages.password.index', compact('view'));
    }




    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);


        // Ambil kode pengguna dari session
        $kd_pengguna = session('kd_pengguna');


        if (!$kd_pengguna) {

            return response()->json([
                'success' => false,
                'message' => 'Session pengguna tidak ditemukan.'
            ], 401);
        }


        // Ambil data pengguna
        $pengguna = DB::table('master_pengguna')
            ->where('kd_pengguna', $kd_pengguna)
            ->first();


        if (!$pengguna) {

            return response()->json([
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan.'
            ], 404);
        }


        // Cek password lama menggunakan MD5
        if (md5($request->password_lama) !== $pengguna->password) {

            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai.'
            ], 422);
        }


        // Password baru tidak boleh sama
        if (md5($request->password) === $pengguna->password) {

            return response()->json([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama.'
            ], 422);
        }


        // Update password baru
        DB::table('master_pengguna')
            ->where('kd_pengguna', $kd_pengguna)
            ->update([
                'password' => md5($request->password),
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.'
        ]);
    }
}
