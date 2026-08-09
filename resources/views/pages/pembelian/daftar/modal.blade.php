<style>

.detail-box {
    background: #f8f9fc;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
}

.detail-box small {
    display: block;
    color: #6c757d;
    font-size: 12px;
    margin-bottom: 4px;
}

.detail-box strong {
    display: block;
    font-size: 14px;
}

.detail-summary {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    background: #fff;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    font-size: 14px;
}

.summary-row.total {
    border-top: 1px dashed #dee2e6;
    border-bottom: 1px dashed #dee2e6;
    margin: 5px 0;
    padding: 12px 0;
    font-size: 16px;
}

#detail-item-list td {
    vertical-align: middle;
}

</style>


<div class="modal fade" id="modal-detail-pembelian" tabindex="-1"
    aria-labelledby="modalDetailPembelianLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modalDetailPembelianLabel">
                        <i class="mdi mdi-receipt-text-outline me-1"></i>
                        Detail Pembelian
                    </h5>

                    <small class="text-muted" id="detail-subtitle">
                        Detail transaksi
                    </small>
                </div>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <!-- Loading -->
                <div id="detail-loading" class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                    <div class="mt-2 text-muted">
                        Memuat detail transaksi...
                    </div>
                </div>

                <!-- Content -->
                <div id="detail-content" style="display:none;">

                    <!-- Informasi transaksi -->
                    <div class="row g-3 mb-4">

                        <div class="col-md-3">
                            <div class="detail-box">
                                <small>No. Nota</small>
                                <strong id="detail-nota">-</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="detail-box">
                                <small>Tanggal</small>
                                <strong id="detail-tanggal">-</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="detail-box">
                                <small>Supplier</small>
                                <strong id="detail-supplier">-</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="detail-box">
                                <small>Kasir</small>
                                <strong id="detail-kasir">-</strong>
                            </div>
                        </div>

                    </div>


                    <!-- Item -->
                    <div class="card border shadow-none mb-3">

                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="mdi mdi-cart-outline me-1"></i>
                                Item Pembelian
                            </h6>
                        </div>

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-bordered table-hover mb-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Barang</th>
                                            <th width="80">Satuan</th>
                                            <th width="70" class="text-end">Qty</th>
                                            <th width="120" class="text-end">Harga</th>
                                            <th width="120" class="text-end">Subtotal</th>
                                            <th width="120" class="text-end">Diskon</th>
                                            <th width="120" class="text-end">Total</th>
                                        </tr>
                                    </thead>

                                    <tbody id="detail-item-list">
                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>


                    <!-- Ringkasan -->
                    <div class="row justify-content-end">

                        <div class="col-md-5">

                            <div class="detail-summary">

                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <strong id="detail-subtotal">
                                        Rp 0
                                    </strong>
                                </div>

                                <div class="summary-row">
                                    <span>Diskon</span>
                                    <strong id="detail-diskon">
                                        Rp 0
                                    </strong>
                                </div>

                                <div class="summary-row total">
                                    <span>Total Pembelian</span>
                                    <strong id="detail-total">
                                        Rp 0
                                    </strong>
                                </div>

                               

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    <i class="mdi mdi-close me-1"></i>
                    Tutup

                </button>

            </div>

        </div>

    </div>
</div>