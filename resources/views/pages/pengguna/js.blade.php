<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,

        ajax: '{{ route('pengguna.table') }}',
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
                data: 'kd_pengguna',
                name: 'kd_pengguna'
            },


            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'alamat',
                name: 'alamat'
            },
            {
                data: 'telepon',
                name: 'telepon'
            },
            {
                data: 'level',
                name: 'level'
            },

        ]
    });

    function addData() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $(".modal-title").text("Tambah Data Pengguna");
        resetForm();
        $("#modal-add").modal("show");
    }

    function editData(id) {
        save_method = "edit";
        $('input[name=_method]').val('PATCH');
        $.ajax({
            url: "{{ url('/pengguna') }}" + "/" + id + "/edit",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#modal-add').modal("show");
                $('.modal-title').text("Edit Data Pengguna");
                $("#id").val(data.id);
                $('#kd_pengguna').val(data.kd_pengguna);
                $("#nm_pengguna").val(data.nm_pengguna);
                $("#nama").val(data.nama);
                $("#alamat").val(data.alamat);
                $("#password").val("");
                $("#telepon").val(data.telepon);
                $("#level").val(data.level);
                

            }
        })
    }


    $("#form-add").submit(function(e) {
        e.preventDefault();
        // loading("btn-save-data");
        var id = $('#id').val();
        if (save_method == "add") url = "{{ url('pengguna') }}";
        else url = "{{ url('pengguna') }}"+"/"+id;
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

    function deleteData(id) {
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
                    url: "{{ url('pengguna') }}"+"/"+id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
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


    
</script>
