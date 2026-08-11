
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <style>

        @page {
            size: Letter portrait;
            margin: 8mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #111;
        }

        .kwitansi {
            width: 100%;
            height: 125mm;
            border: 1px solid #222;
            padding: 6mm;
            page-break-inside: avoid;
            position: relative;
        }

        /* HEADER */

        .header {
            text-align: center;
        }

        .store-name {
            font-size: 17px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .store-address {
            font-size: 9px;
            line-height: 12px;
            margin-top: 2px;
        }

        .store-phone {
            font-size: 9px;
            margin-top: 1px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 6px;
        }

        .line {
            border-top: 1px solid #222;
            margin: 6px 0;
        }

        /* INFORMASI */

        .info {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            padding: 2px 0;
            vertical-align: top;
        }

        .label {
            width: 90px;
            font-weight: bold;
        }

        .separator {
            width: 10px;
            text-align: center;
        }

        /* PEMBAYARAN */

        .amount {
            border: 1px solid #222;
            text-align: center;
            padding: 5px;
            margin: 6px 0;
        }

        .amount-label {
            font-size: 8px;
        }

        .amount-value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 2px;
        }

        .sisa {
            font-weight: bold;
        }

        /* KETERANGAN */

        .keterangan {
            margin-top: 5px;
            font-size: 9px;
        }

        /* TANDA TANGAN */

        .signature {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .signature td {
            width: 50%;
            text-align: center;
        }

        .signature-space {
            height: 30px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer {
            position: absolute;
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

    </style>

</head>


<body onload="print()">

<div class="kwitansi">

    {{-- HEADER --}}

    <div class="header">

        <div class="store-name">
            {{ env('NAMA_TOKO') }}
        </div>

        <div class="store-address">
            {{ env('ALAMAT_TOKO1') }}<br>
            {{ env('ALAMAT_TOKO2') }}
        </div>

        <div class="store-phone">
            Telp/WA: {{ env('HP_TOKO') }}
        </div>

        <div class="title">
            KWITANSI PEMBAYARAN
        </div>

    </div>


    <div class="line"></div>


    {{-- INFORMASI PEMBAYARAN --}}

    <table class="info">

        <tr>
            <td class="label">No. Pembayaran</td>
            <td class="separator">:</td>
            <td>
                {{ $pembayaran->no_pembayaran }}
            </td>
        </tr>

        <tr>
            <td class="label">Tanggal</td>
            <td class="separator">:</td>
            <td>
                {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}
            </td>
        </tr>

        <tr>
            <td class="label">Pelanggan</td>
            <td class="separator">:</td>
            <td>
                {{ $pembayaran->customer?->nm_pelanggan ?? '' }}
            </td>
        </tr>

        <tr>
            <td class="label">No. Nota</td>
            <td class="separator">:</td>
            <td>
                {{ $pembayaran->nota }}
            </td>
        </tr>

        <tr>
            <td class="label">Nilai Nota</td>
            <td class="separator">:</td>
            <td>
                Rp {{ number_format($pembayaran->nilai_nota, 0, ',', '.') }}
            </td>
        </tr>

    </table>


    {{-- JUMLAH PEMBAYARAN --}}

    <div class="amount">

        <div class="amount-label">
            JUMLAH PEMBAYARAN
        </div>

        <div class="amount-value">
            Rp {{ number_format($pembayaran->pembayaran, 0, ',', '.') }}
        </div>

    </div>


    {{-- SISA PIUTANG --}}

    <table class="info">

        <tr>

            <td class="label">
                Sisa Piutang
            </td>

            <td class="separator">
                :
            </td>

            <td class="sisa">
                Rp {{ number_format($pembayaran->sisa, 0, ',', '.') }}
            </td>

        </tr>

    </table>


    {{-- KETERANGAN --}}

    @if(!empty($pembayaran->keterangan))

        <div class="keterangan">

            <strong>Keterangan:</strong>
            {{ $pembayaran->keterangan }}

        </div>

    @endif


    {{-- TANDA TANGAN --}}

    <table class="signature">

        <tr>

            <td>
                Pelanggan

                <div class="signature-space"></div>

                <div class="signature-name">
                    {{ $pembayaran->customer?->nm_pelanggan ?? '' }}
                </div>

            </td>

            <td>
                Kasir

                <div class="signature-space"></div>

                <div class="signature-name">
                    {{ $pembayaran->kasir?->nama ?? '-' }}
                </div>

            </td>

        </tr>

    </table>


    <div class="footer">
        Terima kasih atas pembayaran Anda.
    </div>

</div>

</body>

</html>
