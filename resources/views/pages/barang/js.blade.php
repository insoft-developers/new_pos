<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,

        ajax: '{{ route('barang.table') }}',
        order: [
            [1, 'desc']
        ],
        columns: [{
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'kd_barang',
                name: 'kd_barang'
            },


            {
                data: 'nm_barang',
                name: 'nm_barang'
            },
            {
                data: 'kd_kategori',
                name: 'kd_kategori'
            },
            {
                data: 'satuan',
                name: 'satuan'
            },

            {
                data: 'stok',
                name: 'stok'
            },

            {
                data: 'harga_beli',
                name: 'harga_beli'
            },
            {
                data: 'harga_jual',
                name: 'harga_jual'
            },
            {
                data: 'harga_reseller',
                name: 'harga_reseller'
            },


            {
                data: 'barcode',
                name: 'barcode',
                visible: false,
            },
            {
                data: 'konversi',
                name: 'konversi',
                visible: false,
            },
            {
                data: 'kd_supplier',
                name: 'kd_supplier',
                visible: false,
            },


        ]
    });

    function addData() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data Barang");
        $("#stok").removeAttr("readonly");
        resetForm();
        $("#modal-add").modal("show");
    }

    function editData(kdBarang) {
        save_method = "edit";
        $("#stok").attr("readonly", true);
        $('input[name=_method]').val('PATCH');
        $.ajax({
            url: "{{ url('/barang_edit') }}" + "/" + kdBarang,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-add').modal("show");
                $('.modal-title').text("Edit Data Barang");
                $('#kd_barang').val(data.kd_barang);
                $("#barcode").val(data.barcode);
                $("#nm_barang").val(data.nm_barang);
                $("#stok").val(data.stok);
                $("#kd_kategori").val(data.kd_kategori);
                $("#kd_supplier").val(data.kd_supplier);
                $("#harga_beli").val(data.harga_beli);
                $("#harga_jual").val(data.harga_jual);
                $("#konversi").val(data.konversi);
                $("#harga_reseller").val(data.harga_reseller);
                $("#satuan").val(data.satuan);

            }
        })
    }


    $("#form-add").submit(function(e) {
        e.preventDefault();
        // loading("btn-save-data");
        // var id = $('#id').val();
        if (save_method == "add") url = "{{ url('barang_simpan') }}";
        else url = "{{ url('barang_update') }}";
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData($('#modal-add form')[0]),
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    $('#modal-add').modal('hide');
                    reloadTable();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        scrollbarPadding: false,
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).map(e => e[0]).join('<br>');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        html: msg
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + xhr.responseJSON?.message
                    });
                }
            },
            complete: function() {
                // $('#btn-save-data').prop('disabled', false).text('Save');
            }

        });
    });

    function deleteData(kdBarang) {
        Swal.fire({
            title: 'Are sure?',
            text: "This data will be deleted permanently",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('barang_delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kd_barang:kdBarang
                    },
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success');
                        reloadTable();
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON.message || 'Terjadi kesalahan.',
                            'error');
                    }
                });
            }
        });
    }

    function reloadTable() {
        table.ajax.reload(null, false);
    }

    function resetForm() {
        $('#form-add')[0].reset();
    }


    $('#source_name').on('keyup', function() {
        let text = $(this).val();

        let slug = text
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '') // hapus karakter aneh
            .replace(/\s+/g, '-') // spasi jadi -
            .replace(/-+/g, '-'); // hindari --

        $('#slug').val(slug);
    });
</script>
