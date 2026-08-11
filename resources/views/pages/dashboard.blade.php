
@extends('master')

@section('content')

<div class="content-page">

    <div class="content">

        <div class="container-fluid">

            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">

                <div class="flex-grow-1">

                    <h4 class="fs-18 fw-semibold m-0">
                        Dashboard
                    </h4>

                    <small class="text-muted">
                        Ringkasan aktivitas toko
                    </small>

                </div>

                <div class="text-end">

                    <ol class="breadcrumb m-0 py-0">

                        <li class="breadcrumb-item">
                            
                        </li>

                    </ol>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- SUMMARY CARDS --}}
            {{-- ================================================= --}}

            <div class="row g-3 mb-3">


                {{-- PENJUALAN HARI INI --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm mb-0">

                        <div class="card-body py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="text-muted small mb-1">
                                        Penjualan Hari Ini
                                    </div>

                                    <h5 class="mb-0 fw-bold">
                                        Rp 0
                                    </h5>

                                </div>

                                <div>

                                    <span class="avatar-sm">

                                        <span class="avatar-title rounded bg-primary-subtle text-primary">

                                            <i class="mdi mdi-cart-check-outline fs-20"></i>

                                        </span>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PENJUALAN BULAN INI --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm mb-0">

                        <div class="card-body py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="text-muted small mb-1">
                                        Penjualan Bulan Ini
                                    </div>

                                    <h5 class="mb-0 fw-bold">
                                        Rp 0
                                    </h5>

                                </div>

                                <div>

                                    <span class="avatar-sm">

                                        <span class="avatar-title rounded bg-success-subtle text-success">

                                            <i class="mdi mdi-chart-line fs-20"></i>

                                        </span>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PEMBAYARAN BULAN INI --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm mb-0">

                        <div class="card-body py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="text-muted small mb-1">
                                        Pembayaran Bulan Ini
                                    </div>

                                    <h5 class="mb-0 fw-bold">
                                        Rp 0
                                    </h5>

                                </div>

                                <div>

                                    <span class="avatar-sm">

                                        <span class="avatar-title rounded bg-info-subtle text-info">

                                            <i class="mdi mdi-cash-check fs-20"></i>

                                        </span>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PEMBELIAN BULAN INI --}}
                <div class="col-xl-3 col-md-6">

                    <div class="card border-0 shadow-sm mb-0">

                        <div class="card-body py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <div class="text-muted small mb-1">
                                        Pembelian Bulan Ini
                                    </div>

                                    <h5 class="mb-0 fw-bold">
                                        Rp 0
                                    </h5>

                                </div>

                                <div>

                                    <span class="avatar-sm">

                                        <span class="avatar-title rounded bg-warning-subtle text-warning">

                                            <i class="mdi mdi-truck-check-outline fs-20"></i>

                                        </span>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CONTENT --}}
            {{-- ================================================= --}}

            <div class="row g-3">


                {{-- ================================================= --}}
                {{-- STOK KOSONG --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-bottom py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <h6 class="mb-0 fw-bold">
                                        <i class="mdi mdi-package-variant-closed-remove text-danger me-1"></i>
                                        Stok Kosong
                                    </h6>

                                </div>

                                <span class="badge bg-danger-subtle text-danger">
                                    0
                                </span>

                            </div>

                        </div>


                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-sm table-hover mb-0">

                                    <tbody>

                                        {{-- DATA BACKEND NANTI --}}

                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    Contoh Produk
                                                </div>

                                                <small class="text-muted">
                                                    BRG001
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <span class="badge bg-danger">
                                                    0
                                                </span>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    Contoh Produk 2
                                                </div>

                                                <small class="text-muted">
                                                    BRG002
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <span class="badge bg-danger">
                                                    0
                                                </span>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td colspan="2"
                                                class="text-center text-muted py-4">

                                                Tidak ada data

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PIUTANG JATUH TEMPO --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-bottom py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <h6 class="mb-0 fw-bold">

                                        <i class="mdi mdi-calendar-alert text-danger me-1"></i>

                                        Piutang Jatuh Tempo

                                    </h6>

                                </div>

                                <span class="badge bg-danger-subtle text-danger">
                                    0
                                </span>

                            </div>

                        </div>


                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-sm table-hover mb-0">

                                    <tbody>

                                        {{-- DATA BACKEND NANTI --}}

                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    Pelanggan Contoh
                                                </div>

                                                <small class="text-muted">
                                                    INV0001
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <div class="fw-semibold text-danger">
                                                    Rp 0
                                                </div>

                                                <small class="text-muted">
                                                    01/01/2026
                                                </small>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    Pelanggan Contoh 2
                                                </div>

                                                <small class="text-muted">
                                                    INV0002
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <div class="fw-semibold text-danger">
                                                    Rp 0
                                                </div>

                                                <small class="text-muted">
                                                    02/01/2026
                                                </small>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td colspan="2"
                                                class="text-center text-muted py-4">

                                                Tidak ada piutang jatuh tempo

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PRODUK TERLARIS --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-bottom py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <h6 class="mb-0 fw-bold">

                                        <i class="mdi mdi-star-outline text-warning me-1"></i>

                                        Produk Terlaris

                                    </h6>

                                </div>

                            </div>

                        </div>


                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-sm table-hover mb-0">

                                    <tbody>

                                        {{-- DATA BACKEND NANTI --}}

                                        <tr>

                                            <td width="30">

                                                <span class="fw-bold">
                                                    1
                                                </span>

                                            </td>

                                            <td>

                                                <div class="fw-semibold">
                                                    Contoh Produk
                                                </div>

                                                <small class="text-muted">
                                                    BRG001
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <span class="badge bg-primary-subtle text-primary">
                                                    100
                                                </span>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                <span class="fw-bold">
                                                    2
                                                </span>
                                            </td>

                                            <td>

                                                <div class="fw-semibold">
                                                    Contoh Produk 2
                                                </div>

                                                <small class="text-muted">
                                                    BRG002
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <span class="badge bg-primary-subtle text-primary">
                                                    80
                                                </span>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                <span class="fw-bold">
                                                    3
                                                </span>
                                            </td>

                                            <td>

                                                <div class="fw-semibold">
                                                    Contoh Produk 3
                                                </div>

                                                <small class="text-muted">
                                                    BRG003
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <span class="badge bg-primary-subtle text-primary">
                                                    60
                                                </span>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td colspan="3"
                                                class="text-center text-muted py-4">

                                                Belum ada data penjualan

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- RECENT PENJUALAN --}}
                {{-- ================================================= --}}

                <div class="col-xl-3 col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-bottom py-3">

                            <div class="d-flex align-items-center">

                                <div class="flex-grow-1">

                                    <h6 class="mb-0 fw-bold">

                                        <i class="mdi mdi-receipt-text-outline text-primary me-1"></i>

                                        Recent Penjualan

                                    </h6>

                                </div>

                            </div>

                        </div>


                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-sm table-hover mb-0">

                                    <tbody>

                                        {{-- DATA BACKEND NANTI --}}

                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    INV0001
                                                </div>

                                                <small class="text-muted">
                                                    Pelanggan Umum
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <div class="fw-semibold">
                                                    Rp 0
                                                </div>

                                                <small class="text-muted">
                                                    Hari ini
                                                </small>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    INV0002
                                                </div>

                                                <small class="text-muted">
                                                    Pelanggan Umum
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <div class="fw-semibold">
                                                    Rp 0
                                                </div>

                                                <small class="text-muted">
                                                    Hari ini
                                                </small>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td>

                                                <div class="fw-semibold">
                                                    INV0003
                                                </div>

                                                <small class="text-muted">
                                                    Pelanggan Umum
                                                </small>

                                            </td>

                                            <td class="text-end">

                                                <div class="fw-semibold">
                                                    Rp 0
                                                </div>

                                                <small class="text-muted">
                                                    Hari ini
                                                </small>

                                            </td>

                                        </tr>


                                        <tr>

                                            <td colspan="2"
                                                class="text-center text-muted py-4">

                                                Belum ada transaksi

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection
