<style>
    .modal-content {
        border: 0;
        border-radius: 14px;
    }

    .modal-header {
        border-bottom: 1px solid #edf2f7;
    }

    .modal-footer {
        border-top: 1px solid #edf2f7;
    }

    .form-label {
        color: #6c757d;
        margin-bottom: .35rem;
        font-size: .82rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        min-height: 42px;
    }

    .input-group-text {
        background: #f8f9fa;
        border-right: 0;
    }

    .form-control:focus,
    .form-select:focus {
        box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .15);
    }

    .card {
        border-radius: 12px;
    }

    .modal-body {
        background: #f8f9fc;
    }
</style>
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-add">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        Modal Title
                    </h5>
                    <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-large-line fw-semibold"></i>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">

                            <div class="row g-3">

                                <!-- Kode Barang -->
                                <input type="hidden" id="kd_barang" name="kd_barang">
                                <!-- Barcode -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Barcode
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-barcode-scan"></i>
                                        </span>
                                        <input type="text" class="form-control" id="barcode" name="barcode"
                                            placeholder="Scan Barcode">
                                    </div>
                                </div>

                                <!-- Stok -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Stok Awal
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-package-variant"></i>
                                        </span>
                                        <input type="number" class="form-control text-end" id="stok"
                                            name="stok" value="0">
                                    </div>
                                </div>

                                <!-- Nama Barang -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">
                                        Nama Barang <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" class="form-control" id="nm_barang" name="nm_barang"
                                        placeholder="Masukkan nama barang">
                                </div>

                                <!-- Kategori -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Kategori <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select" id="kd_kategori" name="kd_kategori">
                                        <option value="">Pilih Kategori</option>

                                        @foreach ($kategori as $item)
                                            <option value="{{ $item->nm_kategori }}">
                                                {{ $item->nm_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Supplier -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Supplier
                                    </label>

                                    <select class="form-select" id="kd_supplier" name="kd_supplier">
                                        <option value="">Pilih Supplier</option>

                                        @foreach ($supplier as $item)
                                            <option value="{{ $item->kd_supplier }}">
                                                {{ $item->nm_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Harga -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Harga Beli
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control text-end" id="harga_beli"
                                            name="harga_beli">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Konversi
                                    </label>

                                    <input type="number" class="form-control text-end" id="konversi"
                                        name="konversi" value="1">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Harga Jual
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control text-end" id="harga_jual"
                                            name="harga_jual">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Harga Reseller
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control text-end" id="harga_reseller"
                                            name="harga_reseller">
                                    </div>
                                </div>

                               <!-- Supplier -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Satuan
                                    </label>

                                    <select class="form-select" id="satuan" name="satuan">
                                        <option value="">Pilih Satuan</option>

                                        @foreach ($satuan as $item)
                                            <option value="{{ $item->nm_satuan }}">
                                                {{ $item->nm_satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Konversi -->
                                

                            </div>

                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i>
                        Batal
                    </button>

                    <button type="submit" id="btn-save-data" class="btn btn-primary rounded-pill px-4">
                        <i class="mdi mdi-content-save"></i>
                        Simpan Data
                    </button>

                </div>

            </form>
        </div>
    </div>
</div><!--End modal-->
