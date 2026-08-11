<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $view = 'dashboard';



        $today = Carbon::today();
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth(); /* |-------------------------------------------------------------------------- | 1. PENJUALAN HARI INI |-------------------------------------------------------------------------- */
        $penjualanHariIni = DB::table('penjualan')->whereDate('tanggal', $today)->sum('belanja'); /* |-------------------------------------------------------------------------- | 2. PENJUALAN BULAN INI |-------------------------------------------------------------------------- */
        $penjualanBulanIni = DB::table('penjualan')->whereBetween('tanggal', [$startMonth, $endMonth])->sum('belanja'); /* |-------------------------------------------------------------------------- | 3. TOTAL PEMBAYARAN BULAN INI |-------------------------------------------------------------------------- */
        $pembayaranBulanIni = DB::table('pembayaran')->whereBetween('tanggal', [$startMonth, $endMonth])->sum('pembayaran'); /* |-------------------------------------------------------------------------- | 4. TOTAL PEMBELIAN BULAN INI |-------------------------------------------------------------------------- */
        $pembelianBulanIni = DB::table('pembelian')->whereBetween('tanggal', [$startMonth, $endMonth])->sum('total_pembelian'); /* |-------------------------------------------------------------------------- | 5. STOK KOSONG |-------------------------------------------------------------------------- */
        $stokKosong = DB::table('master_barang')->where('stok', '<=', 0)->orderBy('nm_barang')->limit(10)->get();
        $jumlahStokKosong = DB::table('master_barang')->where('stok', '<=', 0)->count(); /* |-------------------------------------------------------------------------- | 6. PIUTANG JATUH TEMPO |-------------------------------------------------------------------------- */
        $piutangJatuhTempo = DB::table('penjualan')->where('status_pembayaran', 'BELUM LUNAS')->whereNotNull('jatuh_tempo')->whereDate('jatuh_tempo', '<=', $today)->orderBy('jatuh_tempo')->limit(10)->get(); /* |-------------------------------------------------------------------------- | TOTAL NILAI PIUTANG JATUH TEMPO |-------------------------------------------------------------------------- */
        $totalPiutangJatuhTempo = DB::table('penjualan')->where('status_pembayaran', 'BELUM LUNAS')->whereNotNull('jatuh_tempo')->whereDate('jatuh_tempo', '<=', $today)->sum(DB::raw('belanja - bayar')); /* |-------------------------------------------------------------------------- | 7. PRODUK TERLARIS |-------------------------------------------------------------------------- */
        $produkTerlaris = DB::table('penjualan_post as pi')->select('pi.kd_barang', 'pi.nm_barang', DB::raw('SUM(pi.jumlah) as total_terjual'))->join('penjualan as p', 'p.nota', '=', 'pi.nota')->whereBetween('p.tanggal', [$startMonth, $endMonth])->groupBy('pi.kd_barang', 'pi.nm_barang')->orderByDesc('total_terjual')->limit(10)->get(); /* |-------------------------------------------------------------------------- | 8. RECENT PENJUALAN |-------------------------------------------------------------------------- */
        $recentPenjualan = DB::table('penjualan')->select('nota', 'tanggal', 'kd_pelanggan', 'belanja', 'bayar', 'status_pembayaran')->orderByDesc('tanggal')->orderByDesc('nota')->limit(10)->get(); /* |-------------------------------------------------------------------------- | KIRIM KE VIEW |-------------------------------------------------------------------------- */
        return view('pages.dashboard', compact('view','penjualanHariIni', 'penjualanBulanIni', 'pembayaranBulanIni', 'pembelianBulanIni', 'stokKosong', 'jumlahStokKosong', 'piutangJatuhTempo', 'totalPiutangJatuhTempo', 'produkTerlaris', 'recentPenjualan'));
    }
}
