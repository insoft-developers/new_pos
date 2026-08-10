<style>
    .detail-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px;
    }

    .detail-box small {
        display: block;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .detail-box strong {
        display: block;
        font-size: 14px;
    }


    .payment-info-box {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 14px;
        background: #f8f9fa;
    }

    .payment-info-box small {
        display: block;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .payment-info-box strong {
        display: block;
        font-size: 18px;
    }


    .payment-info-box.danger {
        background: #fff5f5;
        border-color: #f1caca;
    }

    .payment-info-box.danger strong {
        color: #dc3545;
    }


    .payment-result {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        background: #f8f9fa;
    }

    .payment-result-row {
        display: flex;
        justify-content: space-between;
        padding: 6px 0;
    }

    .payment-result-row.total {
        border-top: 1px solid #dee2e6;
        margin-top: 6px;
        padding-top: 12px;
        font-size: 16px;
    }

    .payment-result-row.total strong {
        color: #198754;
    }
</style>



<div class="modal fade" id="modal-pembayaran" tabindex="-1" aria-labelledby="modalDetailPembelianLabel" aria-hidden="true">


    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">
            <form id="form-pembayaran">

                <div class="modal-header">

                    <div>
                        <h5 class="modal-title">
                            <i class="mdi mdi-cash-plus me-1"></i>
                            Tambah Pembayaran
                        </h5>

                        <small class="text-muted">
                            Pembayaran piutang pelanggan
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">



                    @csrf

                    <input type="hidden" name="nota" id="pembayaran-nota">

                    <input type="hidden" name="pelanggan" id="pembayaran-pelanggan">

                    <input type="hidden" name="nilai_nota" id="pembayaran-nilai-nota">


                    {{-- INFORMASI NOTA --}}
                    <div class="card border shadow-none mb-3">

                        <div class="card-header bg-white">

                            <h6 class="mb-0">
                                <i class="mdi mdi-receipt-text-outline me-1"></i>
                                Informasi Piutang
                            </h6>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-4">

                                    <div class="detail-box">

                                        <small>No. Nota</small>

                                        <strong id="pembayaran-display-nota">
                                            -
                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="detail-box">

                                        <small>Pelanggan</small>

                                        <strong id="pembayaran-display-pelanggan">
                                            -
                                        </strong>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <div class="detail-box">

                                        <small>Tanggal</small>

                                        <strong id="pembayaran-display-tanggal">
                                            -
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- RINGKASAN PIUTANG --}}
                    <div class="row g-3 mb-3">

                        <div class="col-md-4">

                            <div class="payment-info-box">

                                <small>
                                    Nilai Nota
                                </small>

                                <strong id="pembayaran-display-nilai">
                                    Rp 0
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="payment-info-box">

                                <small>
                                    Sudah Dibayar
                                </small>

                                <strong id="pembayaran-display-sudah">
                                    Rp 0
                                </strong>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="payment-info-box danger">

                                <small>
                                    Sisa Piutang
                                </small>

                                <strong id="pembayaran-display-sisa">
                                    Rp 0
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- FORM PEMBAYARAN --}}
                    <div class="card border shadow-none">

                        <div class="card-header bg-white">

                            <h6 class="mb-0">
                                <i class="mdi mdi-cash me-1"></i>
                                Pembayaran
                            </h6>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                {{-- NOMINAL --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Nominal Pembayaran
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input type="text" class="form-control form-control-lg text-end"
                                            id="pembayaran-nominal-display" placeholder="0" autocomplete="off">

                                        <input type="hidden" name="pembayaran" id="pembayaran-nominal">

                                    </div>

                                    <div class="invalid-feedback" id="pembayaran-error">
                                        Nominal pembayaran tidak valid.
                                    </div>

                                </div>


                                {{-- TANGGAL --}}
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Tanggal Pembayaran
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="date" class="form-control form-control-lg" name="tanggal"
                                        value="{{ date('Y-m-d') }}" required>

                                </div>


                                {{-- KETERANGAN --}}
                                <div class="col-md-12">

                                    <label class="form-label">
                                        Keterangan
                                    </label>

                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Pembayaran cicilan pertama..."></textarea>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- HASIL PEMBAYARAN --}}
                    <div class="payment-result mt-3">

                        <div class="payment-result-row">

                            <span>
                                Sisa sebelum pembayaran
                            </span>

                            <strong id="pembayaran-sisa-sebelum">
                                Rp 0
                            </strong>

                        </div>


                        <div class="payment-result-row">

                            <span>
                                Pembayaran
                            </span>

                            <strong id="pembayaran-hasil-bayar">
                                Rp 0
                            </strong>

                        </div>


                        <div class="payment-result-row total">

                            <span>
                                Sisa setelah pembayaran
                            </span>

                            <strong id="pembayaran-sisa-setelah">
                                Rp 0
                            </strong>

                        </div>

                    </div>



                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        <i class="mdi mdi-close me-1"></i>
                        Batal

                    </button>


                    <button type="submit" class="btn btn-primary" id="btn-simpan-pembayaran">

                        <i class="mdi mdi-content-save me-1"></i>
                        Simpan Pembayaran

                    </button>

                </div>
            </form>

        </div>

    </div>

</div>
