<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PelangganController;
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


Route::get('/', [MainController::class, 'index']);

Route::get('/barang', [BarangController::class,'index']);
Route::get('/barang_edit/{kd_barang}', [BarangController::class, 'edit']);
Route::post('/barang_simpan', [BarangController::class, 'store']);
Route::post('/barang_update', [BarangController::class, 'update']);
Route::post('/barang_delete', [BarangController::class, 'destroy']);
Route::get('/barang_table', [BarangController::class, 'table'])->name('barang.table');


Route::resource('/pelanggan', PelangganController::class);
Route::get('/pelanggan_table', [PelangganController::class, 'table'])->name('pelanggan.table');

