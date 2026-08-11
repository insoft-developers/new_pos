<script>
    let cart = [];

    let productPage = 1;

    let productSearch = '';

    let searchTimer;


    /* ============================================================
       FORMAT RUPIAH
    ============================================================ */

    function rupiah(value) {
        return 'Rp ' +
            (parseInt(value) || 0)
            .toLocaleString('id-ID');
    }


    /* ============================================================
       MODAL
    ============================================================ */

    function openProductModal() {
        const modal =
            new bootstrap.Modal(
                document.getElementById('productModal')
            );

        $(".modal-title").text("Pilih Barang");

        modal.show();

        productSearch = '';

        $('#product-search').val('');

        loadProducts(1);
    }


    /* ============================================================
       LOAD PRODUCT
    ============================================================ */

    function loadProducts(page = 1) {
        productPage = page;

        $.ajax({

            url: "{{ route('barang.list') }}",

            type: "GET",

            data: {

                page: page,

                per_page: $('#product-per-page').val() || 20,

                search: productSearch

            },

            beforeSend: function() {

                $('#modal-product-list').html(`

                <tr>

                    <td colspan="6"
                        class="text-center py-5">

                        <div class="spinner-border spinner-border-sm text-success"></div>

                        <div class="small text-muted mt-2">
                            Memuat data barang...
                        </div>

                    </td>

                </tr>

            `);

            },

            success: function(response) {

                renderProducts(response);

            },

            error: function(xhr) {

                console.log(xhr.responseText);

                $('#modal-product-list').html(`

                <tr>

                    <td colspan="6"
                        class="text-center py-5 text-danger">

                        <i class="mdi mdi-alert-circle-outline fs-3"></i>

                        <div class="mt-2">
                            Gagal memuat barang
                        </div>

                    </td>

                </tr>

            `);

            }

        });
    }


    /* ============================================================
       RENDER PRODUCTS
    ============================================================ */

    function renderProducts(response) {
        let html = '';


        if (!response.data ||
            response.data.length === 0) {

            html = `

            <tr>

                <td colspan="6">

                    <div class="text-center py-5">

                        <i class="mdi mdi-package-variant-closed-outline fs-1 text-muted"></i>

                        <div class="fw-semibold mt-2">
                            Barang tidak ditemukan
                        </div>

                        <small class="text-muted">
                            Coba gunakan kata kunci lain
                        </small>

                    </div>

                </td>

            </tr>

        `;

        } else {

            response.data.forEach(function(product) {

                let stock =
                    parseInt(product.stok) || 0;


                let stockClass =
                    stock > 0 ?
                    'stock-good' :
                    'stock-low';


                html += `

                <tr class="product-select-row"
                    onclick='selectProduct(${JSON.stringify(product)})'>

                    <td>

                        <span class="badge bg-light text-dark">

                            ${product.kd_barang}

                        </span>

                    </td>

                    <td>

                        <div class="fw-semibold">

                            ${product.nm_barang}

                        </div>

                        <small class="text-muted">

                            ${product.barcode ?? ''}

                        </small>

                    </td>

                    <td class="text-center">

                        ${product.satuan ?? '-'}

                    </td>

                    <td class="text-center">

                        <span class="${stockClass}">

                            ${stock}

                        </span>

                    </td>

                    <td class="text-end">

                        <span class="product-modal-price">

                            ${rupiah(product.harga_jual)}

                        </span>

                    </td>

                    <td class="text-center">

                        <button type="button"
                                class="btn btn-sm btn-success"
                                onclick='event.stopPropagation(); selectProduct(${JSON.stringify(product)})'>

                            <i class="mdi mdi-plus"></i>

                        </button>

                    </td>

                </tr>

            `;

            });

        }


        $('#modal-product-list').html(html);

        renderPagination(response);
    }


    /* ============================================================
       PAGINATION
    ============================================================ */

    function renderPagination(response) {
        let current = response.current_page;

        let last = response.last_page;

        let html = `

        <ul class="pagination pagination-sm mb-0">

            <li class="page-item ${current <= 1 ? 'disabled' : ''}">

                <button class="page-link"
                        onclick="loadProducts(${current - 1})">

                    ‹

                </button>

            </li>

    `;


        let start =
            Math.max(1, current - 2);

        let end =
            Math.min(last, current + 2);


        for (let i = start; i <= end; i++) {

            html += `

            <li class="page-item ${i === current ? 'active' : ''}">

                <button class="page-link"
                        onclick="loadProducts(${i})">

                    ${i}

                </button>

            </li>

        `;

        }


        html += `

            <li class="page-item ${current >= last ? 'disabled' : ''}">

                <button class="page-link"
                        onclick="loadProducts(${current + 1})">

                    ›

                </button>

            </li>

        </ul>

    `;


        $('#product-pagination').html(html);


        $('#product-info').text(

            `Menampilkan ${response.from ?? 0}
        - ${response.to ?? 0}
        dari ${response.total ?? 0} barang`

        );
    }


    /* ============================================================
       SEARCH
    ============================================================ */

    $('#product-search').on(
        'input',
        function() {

            clearTimeout(searchTimer);

            productSearch =
                $(this).val().trim();


            searchTimer =
                setTimeout(
                    function() {

                        loadProducts(1);

                    },
                    300
                );

        }
    );


    /* ============================================================
       PER PAGE
    ============================================================ */

    $('#product-per-page').on(
        'change',
        function() {

            loadProducts(1);

        }
    );


    /* ============================================================
       SELECT PRODUCT
    ============================================================ */

    function selectProduct(product) {

        let existing =
            cart.find(
                item =>
                item.kd_barang ===
                product.kd_barang
            );


        if (existing) {

            existing.jumlah++;

        } else {

            cart.unshift({

                kd_barang: product.kd_barang,

                barcode: product.barcode ?? '',

                nm_barang: product.nm_barang,

                satuan: product.satuan ?? '-',

                harga: parseInt(product.harga_jual) || 0,

                jual: parseInt(product.harga_jual) || 0,

                modal: parseInt(product.harga_beli) || 0,

                reseller: parseInt(product.harga_reseller) || 0,

                jumlah: 1,

                disk: 0,

                price_type: 0

            });

        }


        renderCart();


        bootstrap.Modal
            .getInstance(
                document.getElementById('productModal')
            )
            .hide();

    }


    /* ============================================================
       CART
    ============================================================ */

    function renderCart() {
        const tbody = $('#cart-list');

        tbody.empty();

        if (cart.length === 0) {
            tbody.html(`
            <tr id="empty-cart">
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="mdi mdi-cart-outline"></i>
                        </div>

                        <div class="empty-title">
                            Belum ada barang
                        </div>

                        <div class="empty-description">
                            Tambahkan barang untuk memulai transaksi
                        </div>

                        <button type="button"
                                class="btn btn-outline-success mt-3"
                                onclick="openProductModal()">
                            <i class="mdi mdi-plus"></i>
                            Tambah Barang
                        </button>
                    </div>
                </td>
            </tr>
        `);

            $('#cart-count').text('0 Barang');

            calculateTotal();

            return;
        }

        cart.forEach(function(item, index) {

            const qty = parseInt(item.jumlah) || 0;
            const disk = parseInt(item.disk) || 0;

            const priceType = parseInt(item.price_type) || 1;

            const hargaUmum =
                parseInt(item.harga_jual) ||
                parseInt(item.jual) ||
                0;

            const hargaReseller =
                parseInt(item.harga_reseller) ||
                parseInt(item.reseller) ||
                0;

            const harga = priceType === 2 ?
                hargaReseller :
                hargaUmum;

            item.harga = harga;
            item.price_type = priceType;

            const subtotal = harga * qty;

            const total = Math.max(
                0,
                subtotal - disk
            );

            tbody.append(`
            <tr class="cart-item-row">

                <!-- NOMOR -->
                <td class="align-middle">
                    <span class="cart-number">
                        ${index + 1}
                    </span>
                </td>

                <!-- PRODUK -->
                <td class="align-middle">

                    <div class="cart-product-name">
                        ${item.nm_barang}
                    </div>

                    <div class="cart-product-detail">

                        <span class="cart-product-code">
                            ${item.kd_barang}
                        </span>

                        <span class="cart-product-separator">
                            •
                        </span>

                        <!-- QTY -->
                        <div class="cart-qty">

                            <button type="button"
                                    class="qty-btn"
                                    onclick="changeQty(${index}, -1)">
                                <i class="mdi mdi-minus"></i>
                            </button>

                            <input type="number"
                                   class="qty-input"
                                   value="${qty}"
                                   min="1"
                                   onchange="setQty(${index}, this.value)">

                            <button type="button"
                                    class="qty-btn"
                                    onclick="changeQty(${index}, 1)">
                                <i class="mdi mdi-plus"></i>
                            </button>

                        </div>

                        <span class="cart-unit">
                            ${item.satuan}
                        </span>

                        <span class="cart-product-separator">
                            ×
                        </span>

                        <span class="cart-price">
                            ${rupiah(harga)}
                        </span>

                    </div>

                   
                    <div class="price-type-wrapper">

                        <span class="price-type-label">
                            Harga:
                        </span>

                        <div class="price-switch">

                            <button type="button"
                                    class="price-switch-btn ${priceType === 1 ? 'active' : ''}"
                                    onclick="changePriceType(${index}, 1)">
                                Umum
                            </button>

                            <button type="button"
                                    class="price-switch-btn ${priceType === 2 ? 'active reseller' : ''}"
                                    onclick="changePriceType(${index}, 2)">
                                Reseller
                            </button>

                        </div>

                    </div>

                </td>

               
                <td class="align-middle">

                    <div class="discount-input">

                        <span>
                            Rp
                        </span>

                        <input type="number"
                               value="${disk}"
                               min="0"
                               onchange="setDiscount(${index}, this.value)">

                    </div>

                </td>

              
                <td class="text-end align-middle">

                    <div class="cart-subtotal">
                        ${rupiah(total)}
                    </div>

                    ${
                        disk > 0
                            ? `
                                <div class="cart-discount-info">
                                    Diskon ${rupiah(disk)}
                                </div>
                              `
                            : ''
                    }

                </td>

              
                <td class="align-middle">

                    <button type="button"
                            class="btn btn-delete-item"
                            onclick="removeItem(${index})">
                        <i class="mdi mdi-delete-outline"></i>
                    </button>

                </td>

            </tr>
        `);
        });

        $('#cart-count').text(`${cart.length} Barang`);

        calculateTotal();
    }



    /* ============================================================
       QTY
    ============================================================ */

    function changeQty(index, value) {

        cart[index].jumlah += value;


        if (cart[index].jumlah <= 0) {

            cart.splice(index, 1);

        }


        renderCart();

    }


    function setQty(index, value) {

        value =
            parseInt(value) || 1;


        if (value < 1)
            value = 1;


        cart[index].jumlah = value;

        renderCart();

    }


    /* ============================================================
       DISCOUNT
    ============================================================ */

    function setDiscount(index, value) {

        value =
            parseInt(value) || 0;


        let subtotal =
            cart[index].harga *
            cart[index].jumlah;


        if (value > subtotal)
            value = subtotal;


        cart[index].disk = value;

        renderCart();

    }


    /* ============================================================
       REMOVE
    ============================================================ */

    function removeItem(index) {

        cart.splice(index, 1);

        renderCart();

    }


    /* ============================================================
       CALCULATE
    ============================================================ */

    function calculateTotal() {

        let subtotal = 0;

        let discount = 0;


        cart.forEach(function(item) {

            subtotal +=
                item.harga *
                item.jumlah;


            discount +=
                item.disk;

        });


        let total =
            subtotal - discount;


        let bayar =
            parseInt($('#bayar').val()) || 0;


        let kembali =
            bayar > total ?
            bayar - total :
            0;


        $('#subtotal')
            .text(rupiah(subtotal));


        $('#total_discount')
            .text(rupiah(discount));


        $('#grand_total')
            .text(rupiah(total));


        $('#kembali')
            .text(rupiah(kembali));

    }


    $('#bayar').on(
        'input',
        calculateTotal
    );






    /* ============================================================
       SAVE
    ============================================================ */

    function saveTransaction() {

        if (cart.length === 0) {

            Swal.fire({
                icon: 'warning',
                title: 'Keranjang kosong',
                text: 'Silakan tambahkan barang terlebih dahulu.'
            });

            return;
        }


        let total =
            parseInt(
                $('#grand_total')
                .text()
                .replace(/[^\d]/g, '')
            ) || 0;


        let bayar =
            parseInt(
                $('#bayar').val()
            ) || 0;


        let sisa =
            total - bayar;


        /*
        |--------------------------------------------------------------------------
        | CEK PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        if (bayar < 0) {

            Swal.fire({
                icon: 'warning',
                title: 'Pembayaran tidak valid'
            });

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA KURANG BAYAR
        |--------------------------------------------------------------------------
        */

        let tempoHari = 0;


        if (sisa > 0) {

            tempoHari =
                parseInt(
                    $('#tempo_hari').val()
                ) || 0;


            if (![3, 7, 14, 21, 28]
                .includes(tempoHari)) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Pilih Jatuh Tempo',

                    text: 'Karena pembayaran kurang, silakan pilih tempo pembayaran.'

                });

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | HITUNG SUBTOTAL DAN DISKON
        |--------------------------------------------------------------------------
        */

        let subtotal = 0;

        let totalDiscount = 0;


        cart.forEach(function(item) {

            let qty =
                parseInt(item.jumlah) || 0;

            let harga =
                parseInt(item.harga) || 0;

            let disk =
                parseInt(item.disk) || 0;


            subtotal +=
                qty * harga;

            totalDiscount +=
                disk;
        });


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        let data = {

            _token: '{{ csrf_token() }}',

            kd_pelanggan: $('#kd_pelanggan').val(),

            tanggal: $('#tanggal').val() ||
                new Date().toISOString().split('T')[0],

            keterangan: $('#keterangan').val() || '',

            subtotal: subtotal,

            total_discount: totalDiscount,

            belanja: total,

            bayar: bayar,

            tempo_hari: tempoHari,

            items: cart
        };


        /*
        |--------------------------------------------------------------------------
        | TOMBOL LOADING
        |--------------------------------------------------------------------------
        */

        let button =
            $('#btn-save-data');


        button
            .prop('disabled', true)
            .html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Menyimpan...
        `);


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ route('penjualan.store') }}",

            type: 'POST',

            data: data,

            success: function(response) {

                if (response.success) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Transaksi Berhasil',
                        html: `
                <div class="mb-2">
                    Nota
                </div>

                <strong class="fs-4">
                    ${response.nota}
                </strong>
            `,
                        showCancelButton: true,
                        confirmButtonText: '<i class="mdi mdi-printer"></i> Cetak Struk',
                        cancelButtonText: 'Selesai',
                        reverseButtons: true
                    }).then(function(result) {

                        if (result.isConfirmed) {

                            window.open(
                                "{{ url('/penjualan/struk') }}/" + response.nota,
                                '_blank'
                            );


                        }

                        setTimeout(function() {
                            location.reload();
                        }, 300);


                        cart = [];

                        renderCart();

                        $('#bayar').val('');

                        $('#tempo_hari').val('');

                        $('#tempo-section').addClass('d-none');

                        $('#bayar-info').addClass('d-none');

                        calculateTotal();

                    });
                }
            },

            error: function(xhr) {

                let message = 'Terjadi kesalahan saat menyimpan transaksi.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            },

            complete: function() {

                button
                    .prop('disabled', false)
                    .html(`
            <i class="mdi mdi-content-save"></i>
            Simpan Transaksi
        `);

            }

        });

    }




    $('#metode_bayar').on('change', function() {
        let metode = $(this).val();

        if (metode === 'TEMPO') {
            $('#tempo-section')
                .removeClass('d-none');


            // $('#bayar')
            //     .val(0)
            //     .prop('disabled', true);

            // $('#bayar')
            //     .closest('.payment-input-section')
            //     .addClass('opacity-50');

        } else {
            $('#tempo-section')
                .addClass('d-none');

            $('#tempo_hari')
                .val('');

            $('#jatuh-tempo-info')
                .text('Pilih tempo pembayaran');

            $('#bayar')
                .prop('disabled', false);

            $('#bayar')
                .closest('.payment-input-section')
                .removeClass('opacity-50');
        }

        calculateTotal();
    });


    $('#tempo_hari').on('change', function() {
        let hari = parseInt($(this).val());

        if (!hari) {
            $('#jatuh-tempo-info')
                .text('Pilih tempo pembayaran');

            return;
        }


        let tanggal = new Date();

        tanggal.setDate(
            tanggal.getDate() + hari
        );


        let tanggalText =
            tanggal.toLocaleDateString(
                'id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }
            );


        $('#jatuh-tempo-info').html(`

            Jatuh tempo:

            <strong>
                ${tanggalText}
            </strong>

        `);
    });


    function checkPayment() {
        console.log('check payment');

        let total = $('#grand_total').text();

        total = total.replace(/[^\d]/g, '');

        total = parseInt(total) || 0;

        let bayar = parseInt($('#bayar').val()) || 0;

        let kurang = total - bayar;
        console.log(total);
        console.log(bayar);

        if (kurang > 0) {
            // Otomatis menjadi tempo
            $('#metode_bayar')
                .val('TEMPO');

            $('#tempo-section')
                .removeClass('d-none');

            // Tampilkan sisa hutang
            $('#sisa-hutang')
                .text(rupiah(kurang));

            $('#bayar-info')
                .removeClass('d-none');
        } else {
            // Lunas
            $('#metode_bayar')
                .val('CASH');

            $('#tempo-section')
                .addClass('d-none');

            $('#tempo_hari')
                .val('');

            $('#bayar-info')
                .addClass('d-none');
        }
    }

    function changePriceType(index, priceType) {
        const item = cart[index];

        console.log(item);

        if (!item) {
            return;
        }

        const hargaUmum =
            parseInt(item.harga_jual) ||
            parseInt(item.harga) ||
            0;

        const hargaReseller =
            parseInt(item.harga_reseller) ||
            hargaUmum;

        if (priceType === 2) {
            item.price_type = 2;
            item.harga = hargaReseller;
        } else {
            item.price_type = 1;
            item.harga = hargaUmum;
        }

        renderCart();
    }


    function addCustomer() {
        $(".modal-title").text('Tambah Pelanggan Baru');
        $("#modal-add-customer").modal("show");
    }


    $("#form-add-customer").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ url('pelanggan') }}",
            type: "POST",
            data: new FormData($('#modal-add-customer form')[0]),
            contentType: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    $('#modal-add-customer').modal('hide');

                    customerListRefresh(data.data);
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        scrollbarPadding: false,
                    });

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


    function customerListRefresh(data) {
        var html = '';
        data.forEach(function(item) {
            html += `<option value="${item.kd_pelanggan}">${item.nm_pelanggan}</option>`;
        });

        $("#kd_pelanggan").html(html);
    }
</script>
