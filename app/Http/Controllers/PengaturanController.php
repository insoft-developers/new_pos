<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function printer()
    {
        $view = 'pengaturan-printer';
        $pengaturan = Pengaturan::find(1);
        return view('pages.pengaturan.printer', compact('view', 'pengaturan'));
    }


    public function printerUpdate(Request $request)
    {
        $request->validate([
            'printer_setting' => 'required|in:besar,kecil',
        ]);

        Pengaturan::updateOrCreate(
            ['id' => 1],
            [
                'printer_setting' => $request->printer_setting
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan printer berhasil disimpan.'
        ]);
    }
}
