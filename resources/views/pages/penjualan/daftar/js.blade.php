<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('penjualan_table') }}",
            data: function(d) {
                d.tanggal_dari = $('#filter_tanggal_dari').val();
                d.tanggal_sampai = $('#filter_tanggal_sampai').val();
                d.customer = $('#filter_customer').val();
                d.status = $('#filter_status').val();
                d.kasir = $('#filter_kasir').val();
            }
        },
        order: [
            [2, 'desc']
        ],
        columns: [{
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },


            {
                data: 'nota',
                name: 'nota'
            },
            {
                data: 'kd_pelanggan',
                name: 'kd_pelanggan'
            },
            {
                data: 'subtotal',
                name: 'subtotal'
            },
            {
                data: 'total_discount',
                name: 'total_discount'
            },
            {
                data: 'belanja',
                name: 'belanja'
            },
            {
                data: 'bayar',
                name: 'bayar'
            },
            {
                data: 'kembali',
                name: 'kembali'
            },

            {
                data: 'status',
                name: 'status'
            },

            {
                data: 'kd_user',
                name: 'kd_user'
            },


        ]
    });




    function reloadTable() {
        table.ajax.reload(null, false);
    }

    function resetForm() {
        $('#form-add')[0].reset();
    }


    function detailPenjualan(nota) {

        $('#detail-loading').show();
        $('#detail-content').hide();

        $('#detail-item-list').html('');

        $('#modal-detail-penjualan').modal('show');

        $.ajax({

            url: "{{ url('/penjualan') }}" + "/" + encodeURIComponent(nota) + '/detail',

            type: 'GET',

            dataType: 'json',

            success: function(response) {

                if (!response.success) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Detail penjualan tidak ditemukan.'
                    });

                    $('#modal-detail-penjualan').modal('hide');

                    return;
                }

                let data = response.data;

                // Header
                $('#detail-nota').text(data.nota ?? '-');
                $('#detail-tanggal').text(data.tanggal ?? '-');
                $('#detail-pelanggan').text(data.pelanggan ?? 'Umum');
                $('#detail-kasir').text(data.user ?? '');

                // Item
                let html = '';

                if (!data.items || data.items.length === 0) {

                    html = `
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Tidak ada item penjualan
                        </td>
                    </tr>
                `;

                } else {

                    data.items.forEach(function(item, index) {
                        let typeHarga = item.price_type == 1 ?
                            '<small><span class="badge bg-success">reguler</span></small>' :
                            '<small><span class="badge bg-danger">reseller</span></small>';

                        html += `
                        <tr>

                            <td class="text-center">
                                ${index + 1}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    ${item.nm_barang ?? '-'}
                                </div>

                                ${item.barcode ? `
                                    <small class="text-muted">
                                        ${item.barcode}
                                    </small>
                                ` : ''}
                            </td>

                            <td>
                                ${item.satuan ?? '-'}
                            </td>

                            <td class="text-end">
                                ${formatNumber(item.jumlah)}
                            </td>

                            <td class="text-end">
                                ${formatRupiah(item.harga)}
                                <br>
                                ${typeHarga}
                            </td>

                            <td class="text-end fw-semibold">
                                ${formatRupiah(item.subtotal)}
                            </td>

                            <td class="text-end fw-semibold">
                                ${formatRupiah(item.disk)}
                            </td>

                              <td class="text-end fw-semibold">
                                ${formatRupiah(item.total)}
                            </td>

                        </tr>
                    `;

                    });

                }

                $('#detail-item-list').html(html);


                // Summary
                $('#detail-subtotal').text(
                    formatRupiah(data.subtotal)
                );

                $('#detail-diskon').text(
                    formatRupiah(data.diskon)
                );

                $('#detail-total').text(
                    formatRupiah(data.total)
                );

                $('#detail-bayar').text(
                    formatRupiah(data.bayar)
                );

                $('#detail-kembali').text(
                    formatRupiah(data.kembali)
                );


                $('#detail-loading').hide();
                $('#detail-content').fadeIn(150);

            },

            error: function(xhr) {

                $('#detail-loading').hide();

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Data',
                    text: xhr.responseJSON?.message ||
                        'Terjadi kesalahan saat mengambil detail penjualan.'
                });

                $('#modal-detail-penjualan').modal('hide');
            }

        });
    }


    function formatRupiah(value) {

        value = parseFloat(value || 0);

        return 'Rp ' + value.toLocaleString('id-ID');
    }

    function formatNumber(value) {

        value = parseFloat(value || 0);

        return value.toLocaleString('id-ID');
    }


    $('#btn-filter').on('click', function() {
        table.ajax.reload();
    });
    $('#btn-reset').on('click', function() {
        $('#filter_tanggal_dari').val('');
        $('#filter_tanggal_sampai').val('');
        $('#filter_customer').val('');
        $('#filter_status').val('');
        $('#filter_kasir').val('');
        table.ajax.reload();
    });


    $('#btn-export-excel').on('click', function() {
        exportPenjualan('excel');
    });

    $('#btn-export-pdf').on('click', function() {
        exportPenjualan('pdf');
    });

    function exportPenjualan(type) {
        let params = new URLSearchParams({
            tanggal_dari: $('#filter_tanggal_dari').val(),
            tanggal_sampai: $('#filter_tanggal_sampai').val(),
            customer: $('#filter_customer').val(),
            status: $('#filter_status').val(),
            kasir: $('#filter_kasir').val()
        });
        let url;
        if (type === 'excel') {
            url = "{{ url('penjualan/export/excel') }}";
        } else {
            url = "{{ url('penjualan/export/pdf') }}";
        }
        window.open(url + '?' + params.toString(), '_blank');
    }



    function hapusPenjualan(nota) {
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
                    url: "{{ url('penjualan_hapus') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nota: nota
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
</script>
