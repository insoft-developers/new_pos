<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Master Barang</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #111;
        }

        .report {
            width: 100%;
        }

        /* =========================
           HEADER
        ========================= */

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .store-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .store-address {
            font-size: 10px;
            margin-top: 3px;
        }

        .report-title {
            font-size: 15px;
            font-weight: bold;
            margin-top: 10px;
        }

        .report-subtitle {
            font-size: 10px;
            margin-top: 3px;
            color: #555;
        }

        .line {
            border-top: 1px solid #222;
            margin: 8px 0;
        }

        /* =========================
           FILTER
        ========================= */

        .filter-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .filter-table td {
            padding: 3px 5px;
        }

        .filter-label {
            width: 80px;
            font-weight: bold;
        }

        /* =========================
           TABLE BARANG
        ========================= */

        .items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .items th {
            border: 1px solid #222;
            background: #e9ecef;
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .items td {
            border: 1px solid #555;
            padding: 5px 4px;
            vertical-align: middle;
        }

        .items tbody tr {
            page-break-inside: avoid;
        }

        /* Lebar kolom */

        .col-kode {
            width: 8%;
        }

        .col-barcode {
            width: 10%;
        }

        .col-nama {
            width: 20%;
        }

        .col-kategori {
            width: 9%;
        }

        .col-harga {
            width: 9%;
        }

        .col-satuan {
            width: 6%;
        }

        .col-stok {
            width: 6%;
        }

        .col-konversi {
            width: 7%;
        }

        .col-supplier {
            width: 9%;
        }

        .col-reseller {
            width: 10%;
        }

        /* =========================
           ALIGNMENT
        ========================= */

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .nowrap {
            white-space: nowrap;
        }

        /* =========================
           TOTAL
        ========================= */

        .total-row td {
            font-weight: bold;
            background: #e9ecef;
            border-top: 2px solid #222;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #555;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .clearfix {
            clear: both;
        }
    </style>

</head>

<body>

    <div class="report">

        {{-- =========================
         HEADER TOKO
    ========================= --}}

        <div class="header">

            <div class="store-name">
                {{ env('NAMA_TOKO') }}
            </div>

            <div class="store-address">
                {{ env('ALAMAT_TOKO1') }}<br>
                {{ env('ALAMAT_TOKO2') }}
            </div>

            <div class="report-title">
                MASTER BARANG
            </div>

            <div class="report-subtitle">
                Daftar Master Barang
            </div>

        </div>


        <div class="line"></div>


        {{-- =========================
         FILTER
    ========================= --}}

        <table class="filter-table">

            <tr>

                <td class="filter-label">
                    Kategori
                </td>

                <td>
                    :
                    {{ $kategori ?? 'Semua Kategori' }}
                </td>

                <td class="filter-label">
                    Supplier
                </td>

                <td>
                    :
                    {{ $supplier ?? 'Semua Supplier' }}
                </td>

                <td class="filter-label">
                    Stok
                </td>

                <td>
                    :
                    {{ $stok ?? 'Semua Stok' }}
                </td>

            </tr>

        </table>


        {{-- =========================
         TABLE BARANG
    ========================= --}}

        <table class="items">

            <thead>

                <tr>

                    <th class="col-kode">
                        Kode Barang
                    </th>

                    <th class="col-barcode">
                        Barcode
                    </th>

                    <th class="col-nama">
                        Nama Barang
                    </th>

                    <th class="col-kategori">
                        Kategori
                    </th>

                    <th class="col-harga">
                        Harga Beli
                    </th>

                    <th class="col-harga">
                        Harga Jual
                    </th>

                    <th class="col-satuan">
                        Satuan
                    </th>

                    <th class="col-stok">
                        Stok
                    </th>

                    <th class="col-konversi">
                        Konversi
                    </th>

                    <th class="col-supplier">
                        Supplier
                    </th>

                    <th class="col-reseller">
                        Harga Reseller
                    </th>

                </tr>

            </thead>


            <tbody>

                @php

                    $totalStok = 0;
                    $totalHargaBeli = 0;
                    $totalHargaJual = 0;
                    $totalHargaReseller = 0;

                @endphp


                @foreach ($barang as $item)
                    @php

                        $totalStok += $item->stok ?? 0;

                        $totalHargaBeli += $item->harga_beli ?? 0;

                        $totalHargaJual += $item->harga_jual ?? 0;

                        $totalHargaReseller += $item->harga_reseller ?? 0;

                    @endphp


                    <tr>

                        <td class="text-center">
                            {{ $item->kd_barang }}
                        </td>

                        <td class="text-center">
                            {{ $item->barcode }}
                        </td>

                        <td class="text-left">
                            {{ strtoupper($item->nm_barang) }}
                        </td>

                        <td class="text-center">
                            {{ $item->kd_kategori }}
                        </td>

                        <td class="text-right nowrap">
                            Rp {{ number_format($item->harga_beli ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="text-right nowrap">
                            Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                            {{ $item->satuan }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->stok ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->konversi ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                            {{ $item->kd_supplier }}
                        </td>

                        <td class="text-right nowrap">
                            Rp {{ number_format($item->harga_reseller ?? 0, 0, ',', '.') }}
                        </td>

                    </tr>
                @endforeach


                {{-- TOTAL --}}

                <tr class="total-row">

                    <td colspan="4" class="text-right">
                        TOTAL
                    </td>

                    <td class="text-right nowrap">
                        Rp {{ number_format($totalHargaBeli, 0, ',', '.') }}
                    </td>

                    <td class="text-right nowrap">
                        Rp {{ number_format($totalHargaJual, 0, ',', '.') }}
                    </td>

                    <td></td>

                    <td class="text-right">
                        {{ number_format($totalStok, 0, ',', '.') }}
                    </td>

                    <td></td>

                    <td></td>

                    <td class="text-right nowrap">
                        Rp {{ number_format($totalHargaReseller, 0, ',', '.') }}
                    </td>

                </tr>

            </tbody>

        </table>


        {{-- =========================
         FOOTER
    ========================= --}}

        <div class="footer">

            <div class="footer-left">
                Dicetak:
                {{ now()->format('d/m/Y H:i') }}
            </div>

            <div class="footer-right">
                Total Barang:
                {{ number_format($barang->count(), 0, ',', '.') }}
                barang
            </div>

            <div class="clearfix"></div>

        </div>

    </div>

</body>

</html>
