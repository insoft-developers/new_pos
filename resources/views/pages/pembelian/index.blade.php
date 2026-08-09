@extends('master')

@section('content')
    <div class="content">

        <div class="container-fluid">

            {{-- ================================
             BREADCRUMB
        ================================= --}}

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">

                    <i class="mdi mdi-check-circle me-1"></i>

                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                    <i class="mdi mdi-alert-circle me-1"></i>

                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>
            @endif


            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>Data belum dapat disimpan:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                </div>
            @endif

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">

                <div class="flex-grow-1">

                    <h4 class="fs-18 fw-semibold m-0">
                        Tambah Pembelian
                    </h4>

                </div>

                <div class="text-end">

                    <ol class="breadcrumb m-0 py-0">

                        <li class="breadcrumb-item">
                            Pembelian
                        </li>

                        <li class="breadcrumb-item active">
                            Tambah Pembelian
                        </li>

                    </ol>

                </div>

            </div>


            {{-- ================================
             FORM
        ================================= --}}

            <form id="form-pembelian" method="POST" action="{{ url('pembelian') }}">

                @csrf


                {{-- ================================
                 INFORMASI PEMBELIAN
            ================================= --}}

                <div class="card shadow-sm border-0 mb-3">

                    <div class="card-header bg-white border-bottom">

                        <h5 class="mb-0 fw-bold">

                            <i class="mdi mdi-cart-plus text-primary"></i>

                            Informasi Pembelian

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- SUPPLIER --}}

                            <div class="col-md-6">

                                <label class="form-label">
                                    Supplier
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="kd_supplier" id="kd_supplier" class="form-select" required>

                                    <option value="">
                                        Pilih Supplier
                                    </option>

                                    @foreach ($supplier as $item)
                                        <option value="{{ $item->kd_supplier }}">
                                            {{ $item->nm_supplier }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            {{-- TANGGAL --}}

                            <div class="col-md-3">

                                <label class="form-label">
                                    Tanggal
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>

                            </div>


                            {{-- NOTA --}}

                            <div class="col-md-3">

                                <label class="form-label">
                                    Nota
                                </label>

                                <input type="text" name="nota" id="nota" class="form-control"
                                    value="{{ $nota ?? '' }}" readonly>

                            </div>


                            {{-- KETERANGAN --}}

                            <div class="col-12">

                                <label class="form-label">
                                    Keterangan
                                </label>

                                <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Keterangan pembelian..."></textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================
                 DAFTAR ITEM
            ================================= --}}

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-0 fw-bold">

                                <i class="mdi mdi-package-variant text-primary"></i>

                                Daftar Barang

                            </h5>

                            <small class="text-muted">
                                Tambahkan barang yang dibeli
                            </small>

                        </div>


                        <button type="button" class="btn btn-primary btn-sm rounded-pill" id="btn-tambah-barang">

                            <i class="mdi mdi-plus"></i>

                            Tambah Barang

                        </button>

                    </div>


                    <div class="card-body">


                        <div class="table-responsive">

                            <table class="table table-bordered table-hover table-sm align-middle mb-0" id="table-item">

                                <thead class="table-light">

                                    <tr>

                                        <th width="45" class="text-center">
                                            #
                                        </th>

                                        <th>
                                            Barang
                                        </th>

                                        <th width="100">
                                            Satuan
                                        </th>

                                        <th width="100" class="text-center">
                                            Jumlah
                                        </th>

                                        <th width="150" class="text-end">
                                            Harga
                                        </th>

                                        <th width="130" class="text-end">
                                            Diskon
                                        </th>

                                        <th width="160" class="text-end">
                                            Total
                                        </th>

                                        <th width="60" class="text-center">
                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="item-list">

                                    <tr id="empty-item">

                                        <td colspan="8" class="text-center text-muted py-4">

                                            <i class="mdi mdi-package-variant-closed fs-24 d-block mb-1"></i>

                                            Belum ada barang

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>


                        {{-- ================================
                         TOTAL
                    ================================= --}}

                        <div class="row justify-content-end mt-4">

                            <div class="col-md-5">


                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Subtotal
                                    </span>

                                    <strong id="subtotal-display">
                                        Rp 0
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Total Diskon
                                    </span>

                                    <strong id="discount-display">
                                        Rp 0
                                    </strong>

                                </div>


                                <hr>


                                <div class="d-flex justify-content-between">

                                    <span class="fw-bold fs-16">
                                        TOTAL PEMBELIAN
                                    </span>

                                    <strong class="text-primary fs-18" id="total-display">
                                        Rp 0
                                    </strong>

                                </div>


                            </div>

                        </div>


                        {{-- Hidden JSON --}}

                        <input type="hidden" name="items" id="items">


                        {{-- BUTTON --}}

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="{{ url('pembelian') }}" class="btn btn-light">

                                Batal

                            </a>


                            <button type="submit" class="btn btn-primary" id="btn-simpan">

                                <i class="mdi mdi-content-save me-1"></i>

                                Simpan Pembelian

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- MODAL BARANG --}}

    @include('pages.pembelian.modal')
@endsection


@push('scripts')
    @include('pages.pembelian.js')
@endpush
