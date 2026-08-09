<div
    class="modal fade"
    id="modal-barang"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="mdi mdi-package-variant me-1"></i>

                    Pilih Barang

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">


                {{-- SEARCH --}}

                <div class="row mb-3">

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="mdi mdi-magnify"></i>

                            </span>

                            <input
                                type="text"
                                id="search-barang"
                                class="form-control"
                                placeholder="Cari nama / barcode..."
                            >

                        </div>

                    </div>

                </div>


                {{-- TABLE --}}

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-hover table-sm"
                        id="table-barang"
                    >

                        <thead class="table-light">

                            <tr>

                                <th width="50">
                                    #
                                </th>

                                <th>
                                    Barcode
                                </th>

                                <th>
                                    Kode
                                </th>

                                <th>
                                    Nama Barang
                                </th>

                                <th>
                                    Satuan
                                </th>
                                <th>
                                    Stok
                                </th>

                                <th width="100">
                                    Pilih
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>