<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Struk {{ $penjualan->nota }}</title>

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
        }

        body {
            font-family: "Courier New", monospace;
            font-size: 12px;
            color: #000;
        }

        .receipt {
            width: 58mm;
            padding: 3mm 2mm;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
        }

        .title {
            margin-top: 5px;
            font-size: 13px;
            font-weight: bold;
        }

        .line {
            white-space: nowrap;
            overflow: hidden;
            margin: 5px 0;
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

        .section-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .item {
            margin-bottom: 4px;
        }

        .item-name {
            font-weight: normal;
            word-break: break-word;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            padding-left: 4mm;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .total {
            font-weight: bold;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            font-weight: bold;
        }

        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
        }

        .btn-print {
            padding: 8px 15px;
            background: #0d6efd;
            color: white;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
        }

        @media print {

            .print-button {
                display: none;
            }

            html,
            body {
                width: 58mm;
            }

            .receipt {
                width: 58mm;
            }

        }

        @media screen {

            body {
                margin: 20px auto;
                box-shadow: 0 0 8px rgba(0,0,0,.2);
            }

        }

    </style>

</head>

<body>


{{-- TOMBOL PRINT --}}

<div class="print-button">

    <button
        onclick="window.print()"
        class="btn-print">

        🖨 Cetak

    </button>

</div>


<div class="receipt">


    {{-- HEADER --}}

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


    <div class="line">
        =================================
    </div>


    {{-- INFORMASI TRANSAKSI --}}

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
            :
            {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}
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


    <div class="line">
        =================================
    </div>


    {{-- DETAIL --}}

    <div class="section-title">
        DETAIL BELANJA
    </div>

    <div class="line">
        ---------------------------------
    </div>


    @foreach($items as $index => $item)

        <div class="item">

            <div class="item-name">

                {{ $index + 1 }}.
                {{ $item->nm_barang }}

            </div>


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


            @if($item->disk > 0)

                <div class="item-row">

                    <span>
                        Diskon
                    </span>

                    <span>
                        -Rp {{ number_format(
                            $item->disk,
                            0,
                            ',',
                            '.'
                        ) }}
                    </span>

                </div>

            @endif


            <div class="item-row">

                <span>
                    Total
                </span>

                <span>
                    Rp {{ number_format(
                        $item->total,
                        0,
                        ',',
                        '.'
                    ) }}
                </span>

            </div>

        </div>


        @if(!$loop->last)

            <div class="line">
                ---------------------------------
            </div>

        @endif

    @endforeach


    <div class="line">
        ---------------------------------
    </div>


    {{-- RINGKASAN --}}

    <div class="section-title">
        RINGKASAN PEMBAYARAN
    </div>

    <div class="line">
        ---------------------------------
    </div>


    <div class="summary-row">

        <span>
            Subtotal
        </span>

        <span>
            Rp {{ number_format(
                $penjualan->subtotal,
                0,
                ',',
                '.'
            ) }}
        </span>

    </div>


    <div class="summary-row">

        <span>
            Total Diskon
        </span>

        <span>
            -Rp {{ number_format(
                $penjualan->total_discount,
                0,
                ',',
                '.'
            ) }}
        </span>

    </div>


    <div class="line">
        ---------------------------------
    </div>


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


    <div class="line">
        =================================
    </div>


    {{-- FOOTER --}}

    <div class="footer">
        TERIMA KASIH
    </div>

    <div class="footer">
        TELAH BERBELANJA DI KAMI
    </div>


    <div class="line">
        =================================
    </div>


</div>


<script>

    window.onload = function () {

        setTimeout(function () {

            window.print();

        }, 300);

    };

</script>

</body>
</html>