<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('pembelian_table') }}",
            data: function(d) {
                d.tanggal_dari = $('#filter_tanggal_dari').val();
                d.tanggal_sampai = $('#filter_tanggal_sampai').val();
                d.supplier = $('#filter_supplier').val();
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
                data: 'kd_supplier',
                name: 'kd_supplier'
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
                data: 'total_pembelian',
                name: 'total_pembelian'
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


    function detailPembelian(nota) {

        $('#detail-loading').show();
        $('#detail-content').hide();

        $('#detail-item-list').html('');

        $('#modal-detail-pembelian').modal('show');

        $.ajax({

            url: "{{ url('/pembelian') }}" + "/" + encodeURIComponent(nota) + '/detail',

            type: 'GET',

            dataType: 'json',

            success: function(response) {

                if (!response.success) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message || 'Detail pembelian tidak ditemukan.'
                    });

                    $('#modal-detail-pembelian').modal('hide');

                    return;
                }

                let data = response.data;

                // Header
                $('#detail-nota').text(data.nota ?? '-');
                $('#detail-tanggal').text(data.tanggal ?? '-');
                $('#detail-supplier').text(data.supplier ?? 'Umum');
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
                               
                            </td>

                            <td class="text-end fw-semibold">
                                ${formatRupiah(item.subtotal)}
                            </td>

                            <td class="text-end fw-semibold">
                                ${formatRupiah(item.diskon)}
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

               


                $('#detail-loading').hide();
                $('#detail-content').fadeIn(150);

            },

            error: function(xhr) {

                $('#detail-loading').hide();

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Data',
                    text: xhr.responseJSON?.message ||
                        'Terjadi kesalahan saat mengambil detail pembelian.'
                });

                $('#modal-detail-pembelian').modal('hide');
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
        $('#filter_supplier').val('');
        $('#filter_kasir').val('');
        table.ajax.reload();
    });


    $('#btn-export-excel').on('click', function() {
        exportPembelian('excel');
    });

    $('#btn-export-pdf').on('click', function() {
        exportPembelian('pdf');
    });

    function exportPembelian(type) {
        let params = new URLSearchParams({
            tanggal_dari: $('#filter_tanggal_dari').val(),
            tanggal_sampai: $('#filter_tanggal_sampai').val(),
            supplier: $('#filter_supplier').val(),
            kasir: $('#filter_kasir').val()
        });
        let url;
        if (type === 'excel') {
            url = "{{ url('pembelian/export/excel') }}";
        } else {
            url = "{{ url('pembelian/export/pdf') }}";
        }
        window.open(url + '?' + params.toString(), '_blank');
    }

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
                    url: "{{ url('pembelian') }}"+"/"+id,
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

    
</script>
