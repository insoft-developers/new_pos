
@extends('master')

@section('content')

<style>
    .dashboard-card {
        border: 0;
        border-radius: 14px;
        transition: all .2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, .07) !important;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        right: -30px;
        top: -35px;
        background: rgba(0, 0, 0, .025);
    }

    .stat-label {
        font-size: 12px;
        color: #7b8190;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -.3px;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        position: relative;
        z-index: 2;
    }

    .dashboard-section {
        border-radius: 14px;
        border: 0;
        overflow: hidden;
    }

    .section-header {
        padding: 15px 17px;
        background: #fff;
        border-bottom: 1px solid #f0f1f3;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        margin: 0;
    }

    .section-subtitle {
        font-size: 11px;
        color: #9298a3;
        margin-top: 2px;
    }

    .dashboard-table {
        margin: 0;
    }

    .dashboard-table td {
        padding: 11px 15px;
        vertical-align: middle;
        border-color: #f2f3f5;
        font-size: 12px;
    }

    .dashboard-table tr:last-child td {
        border-bottom: 0;
    }

    .item-name {
        font-size: 12px;
        font-weight: 600;
        color: #30343b;
    }

    .item-code {
        font-size: 10px;
        color: #9aa0aa;
        margin-top: 2px;
    }

    .empty-state {
        padding: 30px 15px !important;
        text-align: center;
        color: #a0a5ae;
    }

    .empty-icon {
        width: 38px;
        height: 38px;
        margin: 0 auto 8px;
        border-radius: 50%;
        background: #f6f7f8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .rank {
        width: 25px;
        height: 25px;
        border-radius: 7px;
        background: #f4f6f8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: #68707c;
    }

    .dashboard-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 5px 8px;
        border-radius: 7px;
    }

    .danger-value {
        color: #dc3545;
        font-weight: 700;
        font-size: 12px;
    }

    .success-value {
        color: #198754;
        font-weight: 700;
        font-size: 12px;
    }

    .recent-date {
        font-size: 10px;
        color: #9aa0aa;
        margin-top: 2px;
    }

    .dashboard-header {
        margin-bottom: 18px;
    }

    .dashboard-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .dashboard-desc {
        color: #8b919c;
        font-size: 12px;
    }

    .section-link {
        font-size: 11px;
        color: #6c757d;
        text-decoration: none;
    }

    .section-link:hover {
        color: #0d6efd;
    }
</style>


<div class="content-page">

    <div class="content">

        <div class="container-fluid py-3">


            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div class="dashboard-header">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="dashboard-title">
                            Dashboard
                        </div>

                        <div class="dashboard-desc">
                            Ringkasan aktivitas toko hari ini
                        </div>

                    </div>

                    <div class="text-muted small">

                        <i class="mdi mdi-calendar-outline me-1"></i>

                        {{ now()->translatedFormat('d F Y') }}

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- STAT CARDS --}}
            {{-- ================================================= --}}

            <div class="row g-3 mb-3">


                {{-- PENJUALAN HARI INI --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card dashboard-card stat-card shadow-sm h-100">

                        <div class="card-body p-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="stat-label">
                                        PENJUALAN HARI INI
                                    </div>

                                    <div class="stat-value">

                                        Rp {{ number_format($penjualanHariIni ?? 0, 0, ',', '.') }}

                                    </div>

                                </div>

                                <div class="stat-icon bg-primary-subtle text-primary">

                                    <i class="mdi mdi-cart-outline"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PENJUALAN BULAN INI --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card dashboard-card stat-card shadow-sm h-100">

                        <div class="card-body p-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="stat-label">
                                        PENJUALAN BULAN INI
                                    </div>

                                    <div class="stat-value">

                                        Rp {{ number_format($penjualanBulanIni ?? 0, 0, ',', '.') }}

                                    </div>

                                </div>

                                <div class="stat-icon bg-success-subtle text-success">

                                    <i class="mdi mdi-chart-line"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PEMBAYARAN --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card dashboard-card stat-card shadow-sm h-100">

                        <div class="card-body p-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="stat-label">
                                        PEMBAYARAN BULAN INI
                                    </div>

                                    <div class="stat-value">

                                        Rp {{ number_format($pembayaranBulanIni ?? 0, 0, ',', '.') }}

                                    </div>

                                </div>

                                <div class="stat-icon bg-info-subtle text-info">

                                    <i class="mdi mdi-cash-check"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PEMBELIAN --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card dashboard-card stat-card shadow-sm h-100">

                        <div class="card-body p-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="stat-label">
                                        PEMBELIAN BULAN INI
                                    </div>

                                    <div class="stat-value">

                                        Rp {{ number_format($pembelianBulanIni ?? 0, 0, ',', '.') }}

                                    </div>

                                </div>

                                <div class="stat-icon bg-warning-subtle text-warning">

                                    <i class="mdi mdi-truck-check-outline"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- MAIN PANELS --}}
            {{-- ================================================= --}}

            <div class="row g-3">


                {{-- ================================================= --}}
                {{-- STOK KOSONG --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card dashboard-section shadow-sm h-100">

                        <div class="section-header">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="section-title">

                                        <i class="mdi mdi-package-variant-closed-remove text-danger me-1"></i>

                                        Stok Kosong

                                    </div>

                                    <div class="section-subtitle">
                                        Produk yang perlu direstock
                                    </div>

                                </div>

                                <span class="dashboard-badge bg-danger-subtle text-danger">

                                    {{ $jumlahStokKosong ?? 0 }} item

                                </span>

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table class="table dashboard-table">

                                <tbody>

                                    @forelse($stokKosong ?? [] as $item)

                                        <tr>

                                            <td>

                                                <div class="item-name">
                                                    {{ $item->nm_barang }}
                                                </div>

                                                <div class="item-code">
                                                    {{ $item->kd_barang }}
                                                </div>

                                            </td>

                                            <td class="text-end">

                                                <span class="dashboard-badge bg-danger-subtle text-danger">

                                                    {{ number_format($item->stok, 0, ',', '.') }}

                                                </span>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td class="empty-state">

                                                <div class="empty-icon text-success">

                                                    <i class="mdi mdi-check"></i>

                                                </div>

                                                Tidak ada stok kosong

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PIUTANG --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card dashboard-section shadow-sm h-100">

                        <div class="section-header">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="section-title">

                                        <i class="mdi mdi-calendar-alert text-danger me-1"></i>

                                        Piutang Jatuh Tempo

                                    </div>

                                    <div class="section-subtitle">
                                        Tagihan yang harus ditagih
                                    </div>

                                </div>

                                <span class="dashboard-badge bg-danger-subtle text-danger">

                                    Rp {{ number_format($totalPiutangJatuhTempo ?? 0, 0, ',', '.') }}

                                </span>

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table class="table dashboard-table">

                                <tbody>

                                    @forelse($piutangJatuhTempo ?? [] as $item)

                                        <tr>

                                            <td>

                                                <div class="item-name">

                                                    {{ $item->kd_pelanggan ?? 'Pelanggan Umum' }}

                                                </div>

                                                <div class="item-code">

                                                    {{ $item->nota }}

                                                </div>

                                            </td>

                                            <td class="text-end">

                                                <div class="danger-value">

                                                    Rp {{ number_format(($item->belanja - $item->bayar), 0, ',', '.') }}

                                                </div>

                                                <div class="recent-date">

                                                    {{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/Y') }}

                                                </div>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td class="empty-state">

                                                <div class="empty-icon text-success">

                                                    <i class="mdi mdi-check"></i>

                                                </div>

                                                Tidak ada piutang jatuh tempo

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PRODUK TERLARIS --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card dashboard-section shadow-sm h-100">

                        <div class="section-header">

                            <div class="section-title">

                                <i class="mdi mdi-star-outline text-warning me-1"></i>

                                Produk Terlaris

                            </div>

                            <div class="section-subtitle">

                                Produk dengan penjualan tertinggi bulan ini

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table class="table dashboard-table">

                                <tbody>

                                    @forelse($produkTerlaris ?? [] as $index => $item)

                                        <tr>

                                            <td width="40">

                                                <span class="rank">

                                                    {{ $index + 1 }}

                                                </span>

                                            </td>

                                            <td>

                                                <div class="item-name">

                                                    {{ $item->nm_barang }}

                                                </div>

                                                <div class="item-code">

                                                    {{ $item->kd_barang }}

                                                </div>

                                            </td>

                                            <td class="text-end">

                                                <span class="dashboard-badge bg-primary-subtle text-primary">

                                                    {{ number_format($item->total_terjual, 0, ',', '.') }}

                                                </span>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td class="empty-state">

                                                <div class="empty-icon">

                                                    <i class="mdi mdi-chart-bar"></i>

                                                </div>

                                                Belum ada data penjualan

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- RECENT PENJUALAN --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card dashboard-section shadow-sm h-100">

                        <div class="section-header">

                            <div class="section-title">

                                <i class="mdi mdi-receipt-text-outline text-primary me-1"></i>

                                Recent Penjualan

                            </div>

                            <div class="section-subtitle">

                                Transaksi penjualan terbaru

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table class="table dashboard-table">

                                <tbody>

                                    @forelse($recentPenjualan ?? [] as $item)

                                        <tr>

                                            <td>

                                                <div class="item-name">

                                                    {{ $item->nota }}

                                                </div>

                                                <div class="item-code">

                                                    {{ $item->kd_pelanggan ?? 'Pelanggan Umum' }}

                                                </div>

                                            </td>

                                            <td class="text-end">

                                                <div class="success-value">

                                                    Rp {{ number_format($item->belanja, 0, ',', '.') }}

                                                </div>

                                                <div class="recent-date">

                                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}

                                                </div>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td class="empty-state">

                                                <div class="empty-icon">

                                                    <i class="mdi mdi-receipt-outline"></i>

                                                </div>

                                                Belum ada transaksi

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection