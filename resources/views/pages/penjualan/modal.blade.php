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
                {{ csrf_field() }} {{ method_field('POST') }}
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

                                <!-- Kode Pelanggan -->
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">
                                        Kode Pengguna
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-account-key"></i>
                                        </span>
                                        <input type="text" class="form-control" id="kd_pengguna" name="kd_pengguna"
                                            readonly>
                                    </div>
                                </div>

                                <!-- Nama Pelanggan -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">
                                        Nama Pengguna <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-target-account"></i>
                                        </span>
                                        <input type="text" class="form-control" id="nm_pengguna" name="nm_pengguna"
                                            placeholder="Masukkan username">
                                    </div>
                                </div>

                                 <!-- Nama Pelanggan -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-text-account"></i>
                                        </span>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>

                                 <!-- Nama Pelanggan -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">
                                        Password <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-form-textbox-password"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Masukkan password">
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">
                                        Alamat
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-map-marker"></i>
                                        </span>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat supplier"></textarea>
                                    </div>
                                </div>

                                <!-- WhatsApp -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        WhatsApp
                                    </label>

                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="mdi mdi-whatsapp"></i>
                                        </span>
                                        <input type="text" class="form-control" id="telepon" name="telepon"
                                            placeholder="Contoh: 081234567890">
                                    </div>
                                </div>

                                   <div class="col-md-6">
                                    <label class="form-label small fw-semibold">
                                        Level
                                    </label>

                                    <select class="form-select" id="level" name="level">
                                        <option value="">Pilih Level</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Operator">Operator</option>
                                        
                                        
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                

                <div class="modal-footer bg-light">

                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
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
