<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('penjualan.table') }}',
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
                        let typeHarga = item.price_type == 1 ? '<small><span class="badge bg-success">reguler</span></small>':'<small><span class="badge bg-danger">reseller</span></small>';

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
</script>
