<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('login')->group(function () {


    Route::get('/', [MainController::class, 'index']);

    Route::get('/barang', [BarangController::class, 'index']);
    Route::get('/barang_edit/{kd_barang}', [BarangController::class, 'edit']);
    Route::post('/barang_simpan', [BarangController::class, 'store']);
    Route::post('/barang_update', [BarangController::class, 'update']);
    Route::post('/barang_delete', [BarangController::class, 'destroy']);
    Route::get('/barang_table', [BarangController::class, 'table'])->name('barang.table');

    Route::get('/barang/export/excel', [BarangController::class, 'exportExcel']);
    Route::get('/barang/export/pdf', [BarangController::class, 'exportPdf']);


    Route::resource('/pelanggan', PelangganController::class);
    Route::get('/pelanggan_table', [PelangganController::class, 'table'])->name('pelanggan.table');

    Route::resource('/supplier', SupplierController::class);
    Route::get('/supplier_table', [SupplierController::class, 'table'])->name('supplier.table');


    Route::resource('/pengguna', PenggunaController::class);
    Route::get('/pengguna_table', [PenggunaController::class, 'table'])->name('pengguna.table');

    Route::get('/pos', [PenjualanController::class, 'pos']);
    Route::post('/penjualan_simpan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/barang_list', [PenjualanController::class, 'barangList'])->name('barang.list');
    Route::get('/penjualan/struk/{nota}', [PenjualanController::class, 'struk'])
        ->name('penjualan.struk');

    Route::get('/penjualan', [PenjualanController::class, 'index']);
    Route::get('/penjualan_table', [PenjualanController::class, 'table'])->name('penjualan.table');
    Route::get('/penjualan/{nota}/detail', [PenjualanController::class, 'detail']);

    Route::get('/penjualan/export/excel', [PenjualanController::class, 'exportExcel']);
    Route::get('/penjualan/export/pdf', [PenjualanController::class, 'exportPdf']);
    Route::post('/penjualan_hapus', [PenjualanController::class, 'hapus']);

    Route::resource('pembelian', PembelianController::class);
    Route::get('/pembelian/barang/{kd_barang}', [PembelianController::class,'getBarangDetail'])->name('pembelian.barang.detail');
    Route::get('/pembelian_table', [PembelianController::class, 'table']);

    Route::get('/pembelian/export/excel', [PembelianController::class, 'exportExcel']);
    Route::get('/pembelian/export/pdf', [PembelianController::class, 'exportPdf']);
    Route::post('/pembelian_hapus', [PembelianController::class, 'hapus']);

    Route::get('/pembelian/struk/{nota}', [PembelianController::class, 'struk'])
        ->name('pembelian.struk');

    Route::get('/pembelian/{nota}/detail', [PembelianController::class, 'detail']);

    Route::resource('/piutang', PiutangController::class);
    Route::get('/piutang_table', [PiutangController::class, 'table'])->name('piutang.table');

    Route::resource('/pembayaran', PembayaranController::class);
    Route::get('/pembayaran_table', [PembayaranController::class, 'table'])->name('pembayaran.table');

    Route::get('/piutang/export/excel', [PiutangController::class, 'exportExcel']);
    Route::get('/piutang/export/pdf', [PiutangController::class, 'exportPdf']);
    Route::get('/piutang_list/{id}', [PiutangController::class, 'piutangList']);


    Route::get('/pembayaran/export/excel', [PembayaranController::class, 'exportExcel']);
    Route::get('/pembayaran/export/pdf', [PembayaranController::class, 'exportPdf']);


    Route::post('/logout', [
        LoginController::class,
        'logout'
    ])->name('logout');
});


Route::get('/login', [
    LoginController::class,
    'index'
])->name('login');


Route::post('/login', [
    LoginController::class,
    'login'
])->name('login.process');
