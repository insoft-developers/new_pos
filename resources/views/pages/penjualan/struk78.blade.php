<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Struk {{ $penjualan->nota }}</title>

    <style>

        @page {
            size: 76mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 76mm;
            background: #fff;
        }

        body {
            font-family: "Courier New", monospace;
            font-size: 13px;
            color: #000;
        }

        /*
         * STRUK
         * Lebar kertas 76mm
         * Padding kiri-kanan 3mm
         * Area isi efektif ±72mm
         */
        .receipt {
            width: 76mm;
            margin: 0;
            padding: 8mm 8mm;
        }

        /*
         * HEADER
         */
        .center {
            text-align: center;
            width: 100%;
        }

        .store-name {
            font-size: 17px;
            font-weight: bold;
        }

        .title {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        /*
         * GARIS
         */
        .line {
            width: 100%;
            border-top: 1px dashed #000;
            margin: 5px 0;
            height: 1px;
        }

        .line-solid {
            width: 100%;
            border-top: 1px solid #000;
            margin: 5px 0;
            height: 1px;
        }

        /*
         * INFORMASI TRANSAKSI
         */
        .info-row {
            display: flex;
            width: 100%;
        }

        .info-label {
            width: 25mm;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            min-width: 0;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        /*
         * DETAIL BELANJA
         */
        .section-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .item {
            margin-bottom: 4px;
            width: 100%;
        }

        .item-name {
            font-weight: normal;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            padding-left: 3mm;
            gap: 3px;
        }

        .item-row span:first-child {
            flex: 1;
            min-width: 0;
            word-break: break-word;
        }

        .item-row span:last-child {
            white-space: nowrap;
            text-align: right;
            flex-shrink: 0;
        }

        /*
         * RINGKASAN PEMBAYARAN
         */
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            gap: 3px;
        }

        .summary-row span:first-child {
            flex: 1;
            min-width: 0;
        }

        .summary-row span:last-child {
            white-space: nowrap;
            text-align: right;
            flex-shrink: 0;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }

        /*
         * FOOTER
         */
        .footer {
            text-align: center;
            font-weight: bold;
            width: 100%;
        }

        /*
         * TOMBOL PRINT
         */
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
        }

        .btn-print {
            padding: 8px 15px;
            background: #0d6efd;
            color: white;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        /*
         * PRINT
         */
        @media print {

            .print-button {
                display: none;
            }

            html,
            body {
                width: 76mm;
                margin: 0;
                padding: 0;
            }

            .receipt {
                width: 76mm;
                margin: 0;
                padding: 5mm 5mm;
            }

        }

        /*
         * PREVIEW DI BROWSER
         */
        @media screen {

            body {
                margin: 20px auto;
                box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            }

        }

    </style>

</head>

<body>


{{-- ========================================================= --}}
{{-- TOMBOL PRINT --}}
{{-- ========================================================= --}}

<div class="print-button">

    <button
        onclick="window.print()"
        class="btn-print">

        🖨 Cetak

    </button>

</div>


{{-- ========================================================= --}}
{{-- STRUK --}}
{{-- ========================================================= --}}

<div class="receipt">


    {{-- ===================================================== --}}
    {{-- HEADER TOKO --}}
    {{-- ===================================================== --}}

    <div class="center">

        <div class="store-name">
            {{ env('NAMA_TOKO') }}
        </div>

        <div>
            {{ env('ALAMAT_TOKO1') }}
        </div>

        <div>
            {{ env('ALAMAT_TOKO2') }}
        </div>

        <div>
            HP: {{ env('HP_TOKO') }}
        </div>

        <div class="title">
            STRUK PENJUALAN
        </div>

    </div>


    <div class="line"></div>


    {{-- ===================================================== --}}
    {{-- INFORMASI TRANSAKSI --}}
    {{-- ===================================================== --}}

    <div class="info-row">

        <div class="info-label">
            Pelanggan
        </div>

        <div class="info-value">
            : {{ $pelanggan->nm_pelanggan ?? 'Cash' }}
        </div>

    </div>


    <div class="info-row">

        <div class="info-label">
            Telepon
        </div>

        <div class="info-value">
            : {{ $pelanggan->telepon ?? '-' }}
        </div>

    </div>


    <div class="info-row">

        <div class="info-label">
            No. Nota
        </div>

        <div class="info-value">
            : {{ $penjualan->nota }}
        </div>

    </div>


    <div class="info-row">

        <div class="info-label">
            Tanggal
        </div>

        <div class="info-value">
            : {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}
        </div>

    </div>


    <div class="info-row">

        <div class="info-label">
            Kasir
        </div>

        <div class="info-value">
            : {{ $penjualan->kasir?->nama ?? 'Kasir' }}
        </div>

    </div>


    <div class="line"></div>


    {{-- ===================================================== --}}
    {{-- DETAIL BELANJA --}}
    {{-- ===================================================== --}}

    <div class="section-title">
        DETAIL BELANJA
    </div>

    <div class="line"></div>


    @foreach($items as $index => $item)

        <div class="item">


            {{-- NAMA BARANG --}}
            <div class="item-name">
                {{ $item->nm_barang }}
            </div>


            {{-- JUMLAH x HARGA + TOTAL --}}
            <div class="item-row">

                <span>
                    {{ number_format($item->jumlah, 0, ',', '.') }}
                    x
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                </span>

                <span>
                    Rp {{ number_format(
                        $item->jumlah * $item->harga,
                        0,
                        ',',
                        '.'
                    ) }}
                </span>

            </div>


        </div>


        @if(!$loop->last)

            <div class="line"></div>

        @endif

    @endforeach


    <div class="line"></div>


    {{-- ===================================================== --}}
    {{-- RINGKASAN PEMBAYARAN --}}
    {{-- ===================================================== --}}

    <div class="section-title">
        RINGKASAN PEMBAYARAN
    </div>

    <div class="line"></div>


    {{-- TOTAL --}}
    <div class="summary-row total">

        <span>
            TOTAL
        </span>

        <span>
            Rp {{ number_format(
                $penjualan->belanja,
                0,
                ',',
                '.'
            ) }}
        </span>

    </div>


    {{-- PEMBAYARAN --}}
    <div class="summary-row">

        <span>
            Pembayaran
        </span>

        <span>
            Rp {{ number_format(
                $penjualan->bayar,
                0,
                ',',
                '.'
            ) }}
        </span>

    </div>


    {{-- KEMBALIAN --}}
    <div class="summary-row">

        <span>
            Kembalian
        </span>

        <span>
            Rp {{ number_format(
                $penjualan->kembali,
                0,
                ',',
                '.'
            ) }}
        </span>

    </div>


    <div class="line"></div>


    {{-- ===================================================== --}}
    {{-- FOOTER --}}
    {{-- ===================================================== --}}

    <div class="footer">
        TERIMA KASIH
    </div>

    <div class="footer">
        TELAH BERBELANJA DI KAMI
    </div>


    <div class="line"></div>


</div>


{{-- ========================================================= --}}
{{-- AUTO PRINT --}}
{{-- ========================================================= --}}

<script>

    window.onload = function () {

        setTimeout(function () {

            window.print();

        }, 300);

    };

</script>


</body>
</html>