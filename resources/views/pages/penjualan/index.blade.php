@extends('master')

@section('content')
    <div class="pos-page">

        <div class="container-fluid px-3 px-lg-4 py-3">

            {{-- =====================================================
            TOP HEADER
        ====================================================== --}}
            <div class="pos-header mb-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="pos-logo">
                        <i class="mdi mdi-sprout"></i>
                    </div>

                    <div>
                        <h4 class="mb-0 fw-bold">
                            Penjualan
                        </h4>

                        <div class="text-muted small">
                            Transaksi penjualan produk pertanian
                        </div>
                    </div>

                </div>


                <div class="nota-box">

                    <div class="nota-label">
                        NOMOR NOTA
                    </div>

                    <div class="nota-number">
                        {{ $nota ?? 'AUTO' }}
                    </div>

                </div>

            </div>



            {{-- =====================================================
            CUSTOMER BAR
        ====================================================== --}}
            <div class="customer-card mb-3">

                <div class="customer-icon">

                    <i class="mdi mdi-account-outline"></i>

                </div>


                <div class="customer-content">

                    <div class="customer-label">
                        PELANGGAN
                    </div>

                    <select id="kd_pelanggan" class="customer-select">


                        @foreach ($customers as $customer)
                            <option value="{{ $customer->kd_pelanggan }}">
                                {{ $customer->nm_pelanggan }}
                            </option>
                        @endforeach

                    </select>

                </div>


                <div class="customer-divider"></div>


                <div class="date-content">

                    <div class="customer-label">
                        TANGGAL TRANSAKSI
                    </div>

                    <input type="date" id="tanggal" class="date-input" value="{{ date('Y-m-d') }}"  readonly
       onkeydown="return false;"
       onclick="return false;">

                </div>


                <div class="flex-grow-1"></div>


                <button type="button" class="btn btn-light customer-add" onclick="addCustomer()">

                    <i class="mdi mdi-account-plus-outline"></i>

                    Pelanggan Baru

                </button>

            </div>



            {{-- =====================================================
            MAIN CONTENT
        ====================================================== --}}
            <div class="row g-3">


                {{-- =================================================
                LEFT : CART
            ================================================== --}}
                <div class="col-xl-8">

                    <div class="cart-card">

                        {{-- CART HEADER --}}
                        <div class="cart-header">

                            <div>

                                <div class="cart-title">

                                    <i class="mdi mdi-cart-outline"></i>

                                    Daftar Pesanan

                                </div>

                                <div class="cart-subtitle">

                                    Barang yang akan diproses dalam transaksi

                                </div>

                            </div>


                            <button type="button" class="btn btn-add-product" onclick="openProductModal()">

                                <i class="mdi mdi-plus"></i>

                                Tambah Barang

                            </button>

                        </div>


                        {{-- CART TABLE --}}
                        {{-- ===================================================== CART TABLE ====================================================== --}} <div class="table-responsive">
                            <table class="table pos-table mb-0">
                                <thead>
                                    <tr>
                                        <th width="45">#</th>
                                        <th>PRODUK</th>
                                        <th width="150" class="text-end"> DISKON </th>
                                        <th width="160" class="text-end"> SUBTOTAL </th>
                                        <th width="45"></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-list">
                                    <tr id="empty-cart">
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-icon"> <i class="mdi mdi-cart-outline"></i> </div>
                                                <div class="empty-title"> Belum ada barang </div>
                                                <div class="empty-description"> Tambahkan barang untuk memulai transaksi
                                                </div> <button type="button" class="btn btn-outline-success mt-3"
                                                    onclick="openProductModal()"> <i class="mdi mdi-plus"></i> Tambah Barang
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> {{-- CART FOOTER --}} <div class="cart-footer">
                            <div class="d-flex align-items-center gap-2"> <i
                                    class="mdi mdi-information-outline text-muted"></i> <span> Klik <strong>Tambah
                                        Barang</strong> untuk memilih produk </span> </div> <span id="cart-count"
                                class="item-count"> 0 Barang </span>
                        </div>





                    </div>

                </div>



                {{-- =================================================
                RIGHT : PAYMENT
            ================================================== --}}
                <div class="col-xl-4">

                    <div class="payment-card">

                        <div class="payment-header">

                            <div class="payment-title">

                                Ringkasan Pembayaran

                            </div>

                            <i class="mdi mdi-receipt-text-outline"></i>

                        </div>


                        <div class="payment-body">


                            {{-- SUBTOTAL --}}
                            <div class="payment-row">

                                <span>
                                    Subtotal
                                </span>

                                <strong id="subtotal">
                                    Rp 0
                                </strong>

                            </div>


                            {{-- DISCOUNT --}}
                            <div class="payment-row">

                                <span>
                                    Total Diskon
                                </span>

                                <strong id="total_discount" class="discount-value">

                                    Rp 0

                                </strong>

                            </div>


                            <div class="payment-line"></div>


                            {{-- TOTAL --}}
                            <div class="grand-total">

                                <div>

                                    <div class="grand-label">
                                        TOTAL TAGIHAN
                                    </div>

                                    <div class="grand-caption">
                                        Jumlah yang harus dibayar
                                    </div>

                                </div>


                                <div id="grand_total" class="grand-value">

                                    Rp 0

                                </div>

                            </div>


                            {{-- PAYMENT --}}
                            <div class="payment-input-section">

                                <label>
                                    Pembayaran
                                </label>


                                <div class="payment-input">

                                    <span>
                                        Rp
                                    </span>

                                    <input type="number" id="bayar" value="0"
                                        placeholder="0" oninput="checkPayment()">

                                </div>

                                <div id="bayar-info" class="alert alert-warning mt-2 py-2 d-none">

                                    <div class="d-flex align-items-center">

                                        <i class="mdi mdi-clock-alert-outline fs-4 me-2"></i>

                                        <div>

                                            <div class="fw-semibold">
                                                Pembayaran menjadi Tempo
                                            </div>

                                            <small>
                                                Sisa pembayaran:
                                                <strong id="sisa-hutang">
                                                    Rp 0
                                                </strong>
                                            </small>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- METHOD --}}
                            <div class="mb-3">

                                <label class="payment-label">
                                    Metode Pembayaran
                                </label>

                                <select id="metode_bayar" class="form-select payment-select">

                                    <option value="CASH">
                                        Cash
                                    </option>



                                    <option value="TEMPO">
                                        Tempo
                                    </option>

                                </select>

                            </div>


                            {{-- TEMPO --}}
                            <div class="mb-3 d-none" id="tempo-section">

                                <label class="payment-label">
                                    Jatuh Tempo
                                </label>

                                <select id="tempo_hari" class="form-select payment-select">

                                    <option value="">
                                        Pilih Jatuh Tempo
                                    </option>

                                    <option value="3">
                                        3 Hari
                                    </option>

                                    <option value="7">
                                        7 Hari
                                    </option>

                                    <option value="14">
                                        14 Hari
                                    </option>

                                    <option value="21">
                                        21 Hari
                                    </option>

                                    <option value="28">
                                        28 Hari
                                    </option>

                                </select>

                                <div class="small text-muted mt-2" id="jatuh-tempo-info">

                                    Pilih tempo pembayaran

                                </div>

                            </div>


                            {{-- CHANGE --}}
                            <div class="change-box">

                                <div>

                                    <div class="change-label">
                                        KEMBALIAN
                                    </div>

                                    <div class="change-caption">
                                        Uang kembali
                                    </div>

                                </div>

                                <strong id="kembali">
                                    Rp 0
                                </strong>

                            </div>


                            {{-- NOTE --}}
                            <div class="mb-3 mt-3">

                                <label class="payment-label">
                                    Keterangan
                                </label>

                                <textarea id="keterangan" class="form-control" rows="2" placeholder="Catatan transaksi..."></textarea>

                            </div>


                            {{-- SAVE --}}
                            <button type="button" id="btn-save" class="btn btn-save" onclick="saveTransaction()">

                                <i class="mdi mdi-check-circle-outline"></i>

                                Simpan Transaksi

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =============================================================
    MODAL BARANG
============================================================= --}}
    <div class="modal fade" id="productModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content product-modal">


                {{-- HEADER --}}
                <div class="modal-header product-modal-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="modal-product-icon">

                            <i class="mdi mdi-package-variant"></i>

                        </div>

                        <div>

                            <h5 class="modal-title fw-bold mb-1">
                                Pilih Barang
                            </h5>

                            <div class="small text-muted">
                                Cari produk berdasarkan nama, kode atau barcode
                            </div>

                        </div>

                    </div>


                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                {{-- SEARCH --}}
                <div class="product-search-area">

                    <div class="row g-2">

                        <div class="col-md-9">

                            <div class="search-box">

                                <i class="mdi mdi-magnify"></i>

                                <input type="text" id="product-search"
                                    placeholder="Cari nama barang, kode barang atau barcode...">

                                <kbd>Ctrl + K</kbd>

                            </div>

                        </div>


                        <div class="col-md-3">

                            <select id="product-per-page" class="form-select h-100">

                                <option value="10">
                                    10 barang
                                </option>

                                <option value="20" selected>
                                    20 barang
                                </option>

                                <option value="50">
                                    50 barang
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="product-table-wrapper">

                    <table class="table product-table mb-0">

                        <thead>

                            <tr>

                                <th>
                                    KODE
                                </th>

                                <th>
                                    NAMA BARANG
                                </th>

                                <th width="100" class="text-center">
                                    SATUAN
                                </th>

                                <th width="100" class="text-center">
                                    STOK
                                </th>

                                <th width="150" class="text-end">
                                    HARGA
                                </th>

                                <th width="80">
                                </th>

                            </tr>

                        </thead>

                        <tbody id="modal-product-list">
                        </tbody>

                    </table>

                </div>


                {{-- FOOTER --}}
                <div class="product-modal-footer">

                    <div id="product-info" class="small text-muted">
                    </div>

                    <div id="product-pagination">
                    </div>

                </div>

            </div>

        </div>

    </div>



    @include('pages.penjualan.css')
@endsection

@push('scripts')
    @include('pages.penjualan.js')
@endpush
