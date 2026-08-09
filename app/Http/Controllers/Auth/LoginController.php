<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Form login
     */
    public function index()
    {
        if (Session::has('pengguna')) {
            return redirect('/');
        }

        return view('auth.login');
    }


    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'nm_pengguna' => 'required',
            'password' => 'required',
        ], [
            'nm_pengguna.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);


        $pengguna = Pengguna::where(
            'nm_pengguna',
            $request->nm_pengguna
        )->first();


        if (!$pengguna) {

            return back()
                ->withInput($request->only('nm_pengguna'))
                ->with(
                    'error',
                    'Username atau password salah'
                );
        }


        // Cek password MD5
        if (md5($request->password) !== $pengguna->password) {

            return back()
                ->withInput($request->only('nm_pengguna'))
                ->with(
                    'error',
                    'Username atau password salah'
                );
        }


        // Login berhasil
        $request->session()->regenerate();


        session([
            'pengguna' => $pengguna,
            'kd_pengguna' => $pengguna->kd_pengguna,
            'nm_pengguna' => $pengguna->nm_pengguna,
            'nama_pengguna' => $pengguna->nama,
            'level' => $pengguna->level,
        ]);


        return redirect('/')
            ->with(
                'success',
                'Selamat datang ' . $pengguna->nama
            );

        // ==========================================
        // LOGIN BERHASIL
        // ==========================================


    }


    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Session::flush();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
