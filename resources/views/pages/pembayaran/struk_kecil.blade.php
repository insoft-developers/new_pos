<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Kwitansi {{ $pembayaran->no_pembayaran }}
    </title>

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
            font-size: 11px;
            line-height: 1.25;
        }


        /* =========================
           RECEIPT
        ========================= */

        .kwitansi {
            width: 58mm;
            padding: 3mm 2.5mm;
            margin: 0 auto;
        }


        /* =========================
           HEADER
        ========================= */

        .header {
            text-align: center;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .store-address {
            font-size: 10px;
            line-height: 13px;
            margin-top: 2px;
        }

        .store-phone {
            font-size: 10px;
            margin-top: 2px;
        }

        .title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 5px;
        }


        /* =========================
           LINE
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
            font-size: 10px;
        }

        .info-row {
            display: flex;
            width: 100%;
            line-height: 17px;
        }

        .label {
            width: 25mm;
            flex-shrink: 0;
        }

        .separator {
            width: 3mm;
            flex-shrink: 0;
        }

        .value {
            flex: 1;
            word-break: break-word;
        }


        /* =========================
           JUMLAH PEMBAYARAN
        ========================= */

        .amount {
            border: 1px solid #000;
            text-align: center;
            padding: 5px 3px;
            margin: 7px 0;
        }

        .amount-label {
            font-size: 9px;
        }

        .amount-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 3px;
        }


        /* =========================
           SISA PIUTANG
        ========================= */

        .sisa {
            font-weight: bold;
        }


        /* =========================
           KETERANGAN
        ========================= */

        .keterangan {
            margin-top: 5px;
            font-size: 10px;
            line-height: 15px;
        }


        /* =========================
           TANDA TANGAN
        ========================= */

        .signature {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }

        .signature-space {
            height: 30px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            word-break: break-word;
        }


        /* =========================
           FOOTER
        ========================= */

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 9px;
        }


        /* =========================
           PRINT BUTTON
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


        /* =========================
           SCREEN
        ========================= */

        @media screen {

            body {
                margin: 20px auto;
                box-shadow: 0 0 8px rgba(0, 0, 0, .2);
            }

        }


        /* =========================
           PRINT
        ========================= */

        @media print {

            html,
            body {
                width: 58mm;
                margin: 0;
                padding: 0;
            }

            .kwitansi {
                width: 58mm;
                padding: 3mm 2.5mm;
            }

            .print-button {
                display: none;
            }

        }

    </style>

</head>


<body>


{{-- =========================
     TOMBOL PRINT
========================= --}}

<div class="print-button">

    <button
        onclick="window.print()"
        class="btn-print">

        🖨 Cetak

    </button>

</div>


<div class="kwitansi">


    {{-- =========================
         HEADER
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
            Telp/WA: {{ env('HP_TOKO') }}
        </div>

        <div class="title">
            KWITANSI PEMBAYARAN
        </div>

    </div>


    <div class="line">
        =================================
    </div>


    {{-- =========================
         INFORMASI PEMBAYARAN
    ========================= --}}

    <div class="info">


        <div class="info-row">

            <div class="label">
                No. Pembayaran
            </div>

            <div class="separator">
                :
            </div>

            <div class="value">
                {{ $pembayaran->no_pembayaran }}
            </div>

        </div>


        <div class="info-row">

            <div class="label">
                Tanggal
            </div>

            <div class="separator">
                :
            </div>

            <div class="value">
                {{ \Carbon\Carbon::parse(
                    $pembayaran->tanggal
                )->format('d-m-Y') }}
            </div>

        </div>


        <div class="info-row">

            <div class="label">
                Pelanggan
            </div>

            <div class="separator">
                :
            </div>

            <div class="value">
                {{ $pembayaran->customer?->nm_pelanggan ?? '' }}
            </div>

        </div>


        <div class="info-row">

            <div class="label">
                No. Nota
            </div>

            <div class="separator">
                :
            </div>

            <div class="value">
                {{ $pembayaran->nota }}
            </div>

        </div>


        <div class="info-row">

            <div class="label">
                Nilai Nota
            </div>

            <div class="separator">
                :
            </div>

            <div class="value">
                Rp {{ number_format(
                    $pembayaran->nilai_nota,
                    0,
                    ',',
                    '.'
                ) }}
            </div>

        </div>


    </div>


    <div class="line">
        ---------------------------------
    </div>


    {{-- =========================
         JUMLAH PEMBAYARAN
    ========================= --}}

    <div class="amount">

        <div class="amount-label">
            JUMLAH PEMBAYARAN
        </div>

        <div class="amount-value">
            Rp {{ number_format(
                $pembayaran->pembayaran,
                0,
                ',',
                '.'
            ) }}
        </div>

    </div>


    {{-- =========================
         SISA PIUTANG
    ========================= --}}

    <div class="info">

        <div class="info-row">

            <div class="label">
                Sisa Piutang
            </div>

            <div class="separator">
                :
            </div>

            <div class="value sisa">
                Rp {{ number_format(
                    $pembayaran->sisa,
                    0,
                    ',',
                    '.'
                ) }}
            </div>

        </div>

    </div>


    {{-- =========================
         KETERANGAN
    ========================= --}}

    @if(!empty($pembayaran->keterangan))

        <div class="line">
            ---------------------------------
        </div>

        <div class="keterangan">

            <strong>
                Keterangan:
            </strong>

            {{ $pembayaran->keterangan }}

        </div>

    @endif


    <div class="line">
        ---------------------------------
    </div>


    {{-- =========================
         TANDA TANGAN
    ========================= --}}

    <div class="signature">

        <div>
            Kasir
        </div>

        <div class="signature-space"></div>

        <div class="signature-name">
            {{ $pembayaran->kasir?->nama ?? '-' }}
        </div>

    </div>


    <div class="line">
        =================================
    </div>


    {{-- =========================
         FOOTER
    ========================= --}}

    <div class="footer">

        Terima kasih atas pembayaran Anda.

    </div>


</div>


<script>

    window.onload = function() {

        setTimeout(function() {

            window.print();

        }, 300);

    };

</script>


</body>

</html>