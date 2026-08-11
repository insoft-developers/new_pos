<style>
    @page {
        size: 58mm auto;
        margin: 0;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 58mm;
        background: #fff;
        color: #000;
    }

    body {
        font-family: "Courier New", monospace;
        font-size: 12px;
        line-height: 1.25;
    }

    .receipt {
        width: 58mm;
        padding: 3mm 2.5mm;
        margin: 0 auto;
    }


    /* =========================
       HEADER
    ========================= */

    .header {
        text-align: center;
        margin-bottom: 5px;
    }

    .store-name {
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .store-address {
        font-size: 11px;
        margin-top: 2px;
    }

    .store-phone {
        font-size: 11px;
        margin-top: 2px;
    }

    .document-title {
        font-size: 13px;
        font-weight: bold;
        margin-top: 5px;
        text-transform: uppercase;
    }


    /* =========================
       GARIS
    ========================= */

    .line {
        white-space: nowrap;
        overflow: hidden;
        margin: 5px 0;
    }


    /* =========================
       INFORMASI
    ========================= */

    .info {
        width: 100%;
        font-size: 11px;
        line-height: 18px;
    }

    .info-row {
        display: flex;
        width: 100%;
    }

    .info-label {
        width: 23mm;
        flex-shrink: 0;
    }

    .info-value {
        flex: 1;
        word-break: break-word;
    }


    /* =========================
       ITEM
    ========================= */

    .items {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        table-layout: fixed;
    }

    .items th {
        border-bottom: 1px dashed #000;
        padding: 3px 1px;
        text-align: left;
        font-weight: bold;
    }

    .items td {
        padding: 4px 1px;
        vertical-align: top;
    }

    .items .barang {
        width: 39%;
    }

    .items .qty {
        width: 13%;
        text-align: center;
    }

    .items .price {
        width: 23%;
        text-align: right;
    }

    .items .total {
        width: 25%;
        text-align: right;
    }

    .product-name {
        font-weight: bold;
        word-break: break-word;
    }

    .product-detail {
        font-size: 9px;
        margin-top: 2px;
        word-break: break-word;
    }


    /* =========================
       RINGKASAN
    ========================= */

    .summary {
        width: 100%;
        margin-top: 5px;
        font-size: 11px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 2px 0;
    }

    .summary-row span:last-child {
        text-align: right;
    }

    .summary-row.total {
        border-top: 1px dashed #000;
        border-bottom: 1px dashed #000;
        margin-top: 3px;
        padding: 5px 0;
        font-size: 13px;
        font-weight: bold;
    }


    /* =========================
       FOOTER
    ========================= */

    .footer {
        text-align: center;
        margin-top: 8px;
        font-size: 10px;
    }

    .footer strong {
        display: block;
        margin-bottom: 3px;
    }


    /* =========================
       PRINT
    ========================= */

    .print-button {
        position: fixed;
        top: 10px;
        right: 10px;
    }

    .btn-print {
        padding: 8px 15px;
        border: 0;
        border-radius: 5px;
        background: #0d6efd;
        color: #fff;
        cursor: pointer;
        font-size: 14px;
    }


    @media screen {

        body {
            margin: 20px auto;
            box-shadow: 0 0 8px rgba(0, 0, 0, .2);
        }

    }


    @media print {

        html,
        body {
            width: 58mm;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 58mm;
            padding: 3mm 2.5mm;
        }

        .print-button {
            display: none;
        }

    }


    .item-row {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding-left: 4mm;
        line-height: 17px;
    }

    .product-name {
        font-weight: bold;
        word-break: break-word;
        line-height: 16px;
    }

    .product-detail {
        font-size: 9px;
        margin-top: 1px;
        margin-bottom: 2px;
        padding-left: 4mm;
    }

    .line {
        white-space: nowrap;
        overflow: hidden;
        margin: 5px 0;
    }
</style>


{{-- TOMBOL PRINT --}}
<div class="print-button">

    <button onclick="window.print()" class="btn-print">

        🖨 Cetak

    </button>

</div>


<div class="receipt">


    {{-- =========================
         HEADER TOKO
    ========================= --}}

    <div class="header">

        <div class="store-name">
            {{ env('NAMA_TOKO') }}
        </div>

        <div class="store-address">
            {{ env('ALAMAT_TOKO1') }}
        </div>

        <div class="store-address">
            {{ env('ALAMAT_TOKO2') }}
        </div>

        <div class="store-phone">
            HP: {{ env('HP_TOKO') }}
        </div>

        <div class="document-title">
            BUKTI PEMBELIAN
        </div>

    </div>


    <div class="line">
        =================================
    </div>


    {{-- =========================
         INFORMASI PEMBELIAN
    ========================= --}}

    <div class="info">


        <div class="info-row">

            <div class="info-label">
                Nota
            </div>

            <div class="info-value">
                : {{ $pembelian->nota }}
            </div>

        </div>


        <div class="info-row">

            <div class="info-label">
                Tanggal
            </div>

            <div class="info-value">
                :
                {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d-m-Y') }}
            </div>

        </div>


        <div class="info-row">

            <div class="info-label">
                Supplier
            </div>

            <div class="info-value">
                :
                {{ $supplier->nm_supplier ?? $pembelian->kd_supplier }}
            </div>

        </div>


        <div class="info-row">

            <div class="info-label">
                Kode
            </div>

            <div class="info-value">
                :
                {{ $pembelian->kd_supplier }}
            </div>

        </div>


        <div class="info-row">

            <div class="info-label">
                Kasir
            </div>

            <div class="info-value">
                :
                {{ $pembelian->kasir?->nama ?? '' }}
            </div>

        </div>


        @if (!empty($pembelian->jatuh_tempo))
            <div class="info-row">

                <div class="info-label">
                    Jatuh Tempo
                </div>

                <div class="info-value">
                    :
                    {{ \Carbon\Carbon::parse($pembelian->jatuh_tempo)->format('d-m-Y') }}
                </div>

            </div>
        @endif


    </div>


    <div class="line">
        =================================
    </div>


    {{-- =========================
         DETAIL BARANG
    ========================= --}}

    <div class="section-title">
        DETAIL PEMBELIAN
    </div>

    <div class="line">
        ---------------------------------
    </div>


    @foreach ($items as $index => $item)
        {{-- NAMA BARANG --}}

        <div class="product-name">

            {{ $index + 1 }}.
            {{ $item->nm_barang }}

        </div>


        {{-- KODE + SATUAN --}}

        <div class="product-detail">

            {{ $item->kd_barang }}

            @if (!empty($item->satuan))
                • {{ $item->satuan }}
            @endif

        </div>


        {{-- QTY + HARGA --}}

        <div class="item-row">

            <span>
                {{ number_format($item->jumlah, 0, ',', '.') }}
                x
                Rp {{ number_format($item->harga, 0, ',', '.') }}
            </span>

            <span>
                Rp
                {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
            </span>

        </div>


        {{-- DISKON --}}

        @if (isset($item->diskon) && $item->diskon > 0)
            <div class="item-row">

                <span>
                    Diskon
                </span>

                <span>
                    -Rp
                    {{ number_format($item->diskon, 0, ',', '.') }}
                </span>

            </div>
        @endif


        {{-- TOTAL ITEM --}}

        <div class="item-row">

            <span>
                Total
            </span>

            <span>
                Rp
                {{ number_format($item->total, 0, ',', '.') }}
            </span>

        </div>


        {{-- PEMISAH ANTAR BARANG --}}

        @if (!$loop->last)
            <div class="line">
                ---------------------------------
            </div>
        @endif
    @endforeach

    <div class="line">
        ---------------------------------
    </div>


    {{-- =========================
         RINGKASAN
    ========================= --}}

    <div class="summary">


        <div class="summary-row">

            <span>
                Subtotal
            </span>

            <span>
                Rp
                {{ number_format($pembelian->subtotal, 0, ',', '.') }}
            </span>

        </div>


        @if (isset($pembelian->total_discount) && $pembelian->total_discount > 0)
            <div class="summary-row">

                <span>
                    Diskon
                </span>

                <span>
                    -Rp
                    {{ number_format($pembelian->total_discount, 0, ',', '.') }}
                </span>

            </div>
        @endif


        @if (isset($pembelian->ongkir) && $pembelian->ongkir > 0)
            <div class="summary-row">

                <span>
                    Ongkir
                </span>

                <span>
                    Rp
                    {{ number_format($pembelian->ongkir, 0, ',', '.') }}
                </span>

            </div>
        @endif


        <div class="summary-row total">

            <span>
                TOTAL
            </span>

            <span>
                Rp
                {{ number_format($pembelian->total_pembelian, 0, ',', '.') }}
            </span>

        </div>


    </div>


    <div class="line">
        =================================
    </div>


    {{-- =========================
         FOOTER
    ========================= --}}

    <div class="footer">

        <strong>
            PEMBELIAN BARANG
        </strong>

        Dokumen ini merupakan bukti pembelian barang dari supplier.

    </div>


    <div class="line">
        =================================
    </div>


</div>
