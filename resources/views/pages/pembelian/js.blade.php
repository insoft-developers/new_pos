<script>
    let cart = [];


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    function formatRupiah(number) {
        number = parseFloat(number) || 0;

        return 'Rp ' + number.toLocaleString(
            'id-ID'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT ANGKA
    |--------------------------------------------------------------------------
    */

    function formatNumber(number) {
        return parseFloat(number) || 0;
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER CART
    |--------------------------------------------------------------------------
    */

    function renderCart() {

        const tbody = $('#item-list');

        tbody.empty();


        if (cart.length === 0) {

            tbody.html(`
            <tr id="empty-item">

                <td
                    colspan="8"
                    class="text-center text-muted py-4"
                >

                    <i class="mdi mdi-package-variant-closed fs-24 d-block mb-1"></i>

                    Belum ada barang

                </td>

            </tr>
        `);

            updateTotal();

            return;
        }


        cart.forEach(function(item, index) {

            const subtotal =
                item.jumlah * item.harga;


            const total =
                subtotal - item.diskon;


            tbody.append(`

             <tr class="row-item" data-index="${index}">

                <td class="text-center">
                    ${index + 1}
                </td>


                <td>

                    <div class="fw-semibold">
                        ${item.nm_barang}
                    </div>

                    <small class="text-muted">
                        ${item.barcode || ''}
                    </small>

                </td>


                <td>
                    ${item.satuan}
                </td>


                <td>

                    <input
                        type="number"
                        class="form-control form-control-sm text-center input-jumlah"
                        data-index="${index}"
                        value="${item.jumlah}"
                        min="1"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="form-control form-control-sm text-end input-harga"
                        data-index="${index}"
                        value="${item.harga}"
                        min="0"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="form-control form-control-sm text-end input-diskon"
                        data-index="${index}"
                        value="${item.diskon}"
                        min="0"
                    >

                </td>


                <td class="text-end fw-semibold item-total">

                    ${formatRupiah(total)}

                </td>


                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger btn-hapus"
                        data-index="${index}"
                    >

                        <i class="mdi mdi-delete"></i>

                    </button>

                </td>

            </tr>

        `);

        });


        updateTotal();

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TOTAL
    |--------------------------------------------------------------------------
    */

    function updateTotal() {

        let subtotal = 0;

        let discount = 0;


        cart.forEach(function(item) {

            subtotal +=
                item.jumlah * item.harga;

            discount +=
                item.diskon;

        });


        const total =
            subtotal - discount;


        $('#subtotal-display').text(
            formatRupiah(subtotal)
        );


        $('#discount-display').text(
            formatRupiah(discount)
        );


        $('#total-display').text(
            formatRupiah(total)
        );


        $('#items').val(
            JSON.stringify(cart)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH BARANG
    |--------------------------------------------------------------------------
    */

    $('#btn-tambah-barang').on(
        'click',
        function() {

            $('#modal-barang').modal('show');

            loadBarang();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | LOAD BARANG
    |--------------------------------------------------------------------------
    */

    function loadBarang(search = '') {

        $.ajax({

            url: "{{ route('barang.list') }}",

            type: "GET",

            data: {
                search: search
            },

            success: function(response) {

                let tbody =
                    $('#table-barang tbody');

                tbody.empty();


                if (
                    !response.data ||
                    response.data.length === 0
                ) {

                    tbody.html(`

                    <tr>

                        <td
                            colspan="6"
                            class="text-center text-muted py-3"
                        >
                            Barang tidak ditemukan
                        </td>

                    </tr>

                `);

                    return;
                }


                response.data.forEach(
                    function(item, index) {

                        tbody.append(`

                        <tr>

                            <td class="text-center">
                                ${index + 1}
                            </td>

                            <td>
                                ${item.barcode ?? '-'}
                            </td>

                            <td>
                                ${item.kd_barang ?? '-'}
                            </td>

                            <td>
                                ${item.nm_barang}
                            </td>

                            <td>
                                ${item.satuan ?? '-'}
                            </td>

                             <td>
                                ${item.stok ?? '0'}
                            </td>

                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm btn-pilih-barang"
                                    data-id="${item.kd_barang}"
                                >

                                    Pilih

                                </button>

                            </td>

                        </tr>

                    `);

                    }
                );

            },

            error: function() {

                alert(
                    'Gagal mengambil data barang'
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH BARANG
    |--------------------------------------------------------------------------
    */

    let searchTimer;

    $('#search-barang').on(
        'keyup',
        function() {

            clearTimeout(searchTimer);


            const search =
                $(this).val();


            searchTimer = setTimeout(
                function() {

                    loadBarang(search);

                },
                300
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PILIH BARANG
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-pilih-barang',
        function() {

            const kdBarang =
                $(this).data('id');


            $.ajax({

                url: "{{ url('pembelian/barang') }}/" +
                    kdBarang,

                type: "GET",

                success: function(item) {

                    /*
                     * Cek apakah barang
                     * sudah ada
                     */

                    const existing =
                        cart.findIndex(
                            x =>
                            x.kd_barang ==
                            item.kd_barang
                        );


                    if (existing !== -1) {

                        cart[existing].jumlah++;

                    } else {

                        cart.push({

                            kd_barang: item.kd_barang,

                            barcode: item.barcode ?? '',

                            nm_barang: item.nm_barang,

                            satuan: item.satuan ?? '',

                            jumlah: 1,

                            harga: parseFloat(
                                item.harga_beli ?? 0
                            ),

                            diskon: 0,

                        });

                    }


                    renderCart();


                    $('#modal-barang').modal(
                        'hide'
                    );

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | JUMLAH
    |--------------------------------------------------------------------------
    */

    $(document).on('input', '.input-jumlah', function() {

        const index = $(this).data('index');

        cart[index].jumlah =
            parseFloat($(this).val()) || 0;

        updateRowTotal(index);
        updateTotal();

    });


    /*
    |--------------------------------------------------------------------------
    | HARGA
    |--------------------------------------------------------------------------
    */

    $(document).on('input', '.input-harga', function() {

        const index = $(this).data('index');

        cart[index].harga =
            parseFloat($(this).val()) || 0;

        updateRowTotal(index);
        updateTotal();

    });


    /*
    |--------------------------------------------------------------------------
    | DISKON
    |--------------------------------------------------------------------------
    */

    $(document).on('input', '.input-diskon', function() {

        const index = $(this).data('index');

        cart[index].diskon =
            parseFloat($(this).val()) || 0;

        updateRowTotal(index);
        updateTotal();

    });


    function updateRowTotal(index) {
        const item = cart[index];

        const subtotal =
            item.jumlah * item.harga;

        const total =
            subtotal - item.diskon;

        $(`.row-item[data-index="${index}"] .item-total`)
            .text(formatRupiah(total));
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-hapus',
        function() {

            const index =
                $(this).data('index');


            cart.splice(
                index,
                1
            );


            renderCart();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#form-pembelian').on(
        'submit',
        function(e) {

            if (cart.length === 0) {

                e.preventDefault();

                alert(
                    'Silakan tambahkan barang terlebih dahulu.'
                );

                return false;
            }


            if (!$('#kd_supplier').val()) {

                e.preventDefault();

                alert(
                    'Silakan pilih supplier.'
                );

                return false;
            }


            $('#items').val(
                JSON.stringify(cart)
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    renderCart();
</script>
