<script>
    var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('piutang.table') }}",
            data: function(d) {
                d.tanggal_dari = $('#filter_tanggal_dari').val();
                d.tanggal_sampai = $('#filter_tanggal_sampai').val();
                d.customer = $('#filter_customer').val();
                d.tempo = $('#filter_tempo').val();
                d.status = $('#filter_status').val();
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
                data: 'belanja',
                name: 'belanja'
            },
            {
                data: 'bayar',
                name: 'bayar'
            },
            {
                data: 'sisa',
                name: 'sisa'
            },

            {
                data: 'tempo_hari',
                name: 'tempo_hari'
            },
            {
                data: 'jatuh_tempo',
                name: 'jatuh_tempo'
            },


            {
                data: 'kd_user',
                name: 'kd_user'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
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
        $('#filter_customer').val('');
        $('#filter_tempo').val('');
        $('#filter_status').val('');
        table.ajax.reload();
    });


    $('#btn-export-excel').on('click', function() {
        exportPiutang('excel');
    });

    $('#btn-export-pdf').on('click', function() {
        exportPiutang('pdf');
    });

    function exportPiutang(type) {
        let params = new URLSearchParams({
            tanggal_dari: $('#filter_tanggal_dari').val(),
            tanggal_sampai: $('#filter_tanggal_sampai').val(),
            customer: $('#filter_customer').val(),
            tempo: $('#filter_tempo').val(),
            status: $('#filter_status').val()
        });
        let url;
        if (type === 'excel') {
            url = "{{ url('piutang/export/excel') }}";
        } else {
            url = "{{ url('piutang/export/pdf') }}";
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
                    url: "{{ url('pembelian') }}" + "/" + id,
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


    function pembayaran(id) {

        $.ajax({
            url: "{{ url('piutang_list') }}" + "/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                console.log(data);

                $('#pembayaran-nota').val(data.nota);

                $("#pembayaran-display-tanggal").text(data.tanggal);

                $('#pembayaran-pelanggan').val(data.kd_pelanggan);

                $('#pembayaran-nilai-nota').val(data.belanja);

                $('#pembayaran-display-nota').text(data.nota);

                $('#pembayaran-display-pelanggan').text(data.nm_pelanggan);

                $('#pembayaran-display-nilai').text(
                    formatRupiah(data.belanja)
                );

                $('#pembayaran-display-sudah').text(
                    formatRupiah(data.bayar)
                );

                // Simpan angka asli
                $('#pembayaran-display-sisa')
                    .data('value', data.sisa)
                    .text(formatRupiah(data.sisa));

                // Reset input
                $('#pembayaran-nominal-display').val('');
                $('#pembayaran-nominal').val(0);

                // Reset hasil
                $('#pembayaran-sisa-sebelum').text(
                    formatRupiah(data.sisa)
                );

                $('#pembayaran-hasil-bayar').text(
                    formatRupiah(0)
                );

                $('#pembayaran-sisa-setelah').text(
                    formatRupiah(data.sisa)
                );


                $("#modal-pembayaran").modal("show");
            }
        });


    }



    function angkaRupiah(value) {
        if (!value) return 0;

        return parseInt(
            value.toString().replace(/\D/g, ''),
            10
        ) || 0;
    }


    $('#pembayaran-nominal-display').on('input', function() {

        // Ambil angka yang diketik
        let pembayaran = angkaRupiah($(this).val());

        // Format kembali input
        $(this).val(
            pembayaran.toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.')
        );

        // Simpan angka murni untuk dikirim ke controller
        $('#pembayaran-nominal').val(pembayaran);

        // Ambil sisa piutang sebelum pembayaran
        let sisaSebelum = angkaRupiah(
            $('#pembayaran-display-sisa').data('value')
        );

        // Hitung sisa setelah pembayaran
        let sisaSetelah = sisaSebelum - pembayaran;

        // Jangan sampai minus
        if (sisaSetelah < 0) {
            sisaSetelah = 0;
        }

        // Tampilkan hasil
        $('#pembayaran-sisa-sebelum').text(
            formatRupiah(sisaSebelum)
        );

        $('#pembayaran-hasil-bayar').text(
            formatRupiah(pembayaran)
        );

        $('#pembayaran-sisa-setelah').text(
            formatRupiah(sisaSetelah)
        );

    });


    $("#form-pembayaran").submit(function(e) {

        e.preventDefault();

        $.ajax({

            url: "{{ url('pembayaran') }}",

            type: "POST",

            data: $(this).serialize(),

            success: function(response) {

                if (response.success) {

                    // Tutup modal
                    $("#modal-pembayaran").modal("hide");

                    // Tanya apakah ingin cetak
                    Swal.fire({

                        title: 'Pembayaran Berhasil',

                        text: 'Apakah Anda ingin mencetak kwitansi pembayaran?',

                        icon: 'success',

                        showCancelButton: true,

                        confirmButtonText: 'Cetak Kwitansi',

                        cancelButtonText: 'Tidak',

                        confirmButtonColor: '#3085d6',

                        cancelButtonColor: '#6c757d',

                    }).then((result) => {

                        if (result.isConfirmed) {

                            // Buka cetak di TAB BARU
                            window.open(
                                "{{ url('pembayaran/struk') }}/" + response.nomor,
                                "_blank"
                            );

                        }

                        // Setelah memilih Cetak / Tidak
                        // halaman sekarang tetap refresh
                        window.location = "{{ url('pembayaran') }}";

                    });

                } else {

                    Swal.fire(
                        'Gagal!',
                        response.message || 'Pembayaran gagal disimpan.',
                        'error'
                    );

                }

            },

            error: function(xhr) {

                let message = 'Terjadi kesalahan saat menyimpan pembayaran.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire(
                    'Gagal!',
                    message,
                    'error'
                );

            }

        });

    });





</script>
