<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <title>Daftar Penjualan</title>

    <style>

        @page {
            margin: 25px 30px 30px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        /* ==============================
           HEADER TOKO
        ============================== */

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .nama-toko {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .alamat {
            font-size: 10px;
            margin-bottom: 8px;
        }

        .judul {
            font-size: 15px;
            font-weight: bold;
        }


        /* ==============================
           FILTER
        ============================== */

        .filter {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .filter td {
            border: 1px solid #ccc;
            padding: 5px;
        }

        .filter .label {
            width: 120px;
            font-weight: bold;
            background: #eeeeee;
        }


        /* ==============================
           TABLE PENJUALAN
        ============================== */

        .table-penjualan {
            width: 100%;
            border-collapse: collapse;
        }

        .table-penjualan th {
            background: #e9ecef;
            border: 1px solid #999;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
        }

        .table-penjualan td {
            border: 1px solid #aaa;
            padding: 5px 4px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }


        /* ==============================
           TOTAL
        ============================== */

        .total-row td {
            background: #eeeeee;
            font-weight: bold;
            border: 1px solid #999;
            padding: 6px 4px;
        }


        /* ==============================
           FOOTER
        ============================== */

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #666;
            text-align: right;
        }

    </style>

</head>

<body>


    {{-- ==========================================
         HEADER
    =========================================== --}}

    <div class="header">

        <div class="nama-toko">
            {{ env('NAMA_TOKO') }}
        </div>

        <div class="alamat">
            {{ env('ALAMAT_TOKO1') }}
        </div>

        <div class="judul">
            DAFTAR PENJUALAN
        </div>

    </div>


    {{-- ==========================================
         FILTER
    =========================================== --}}

    @php

        // ------------------------------------------
        // TANGGAL
        // ------------------------------------------

        $tanggalDari = 'Semua';

        if (!empty($tanggal_dari)) {
            $tanggalDari = date(
                'd-m-Y',
                strtotime($tanggal_dari)
            );
        }

        $tanggalSampai = 'Semua';

        if (!empty($tanggal_sampai)) {
            $tanggalSampai = date(
                'd-m-Y',
                strtotime($tanggal_sampai)
            );
        }


        // ------------------------------------------
        // CUSTOMER
        // ------------------------------------------



        $customer = "";

        if(!empty($customer_nama)) {
            $cust = \App\Models\Pelanggan::where('kd_pelanggan', $customer_nama)->first();
            $customer = $cust->nm_pelanggan ?? '';
        } else {
            $customer = 'Semua Customer';
        }


        // ------------------------------------------
        // STATUS
        // ------------------------------------------

        $statusDisplay = $status ?? 'Semua';

        if (!empty($statusDisplay)) {

            $statusDisplay = ucwords(
                str_replace(
                    '_',
                    ' ',
                    $statusDisplay
                )
            );

        }


        // ------------------------------------------
        // KASIR
        // ------------------------------------------

        $kasir = "";

        if(!empty($kasir_nama)) {
            $usr = \App\Models\Pengguna::where('kd_pengguna', $kasir_nama)->first();
            $kasir = $usr->nama ?? '';
        } else {
            $kasir = 'Semua Kasir';
        }


        // ------------------------------------------
        // TOTAL
        // ------------------------------------------

        $totalSubtotal = 0;
        $totalDiscount = 0;
        $totalPenjualan = 0;
        $totalPembayaran = 0;
        $totalKembali = 0;

    @endphp


    <table class="filter">

        <tr>

            <td class="label">
                Periode
            </td>

            <td>
                {{ $tanggalDari }}
                s/d
                {{ $tanggalSampai }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Customer
            </td>

            <td>
                {{ $customer }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Status Pembayaran
            </td>

            <td>
                {{ $statusDisplay }}
            </td>

        </tr>


        <tr>

            <td class="label">
                Kasir
            </td>

            <td>
                {{ $kasir }}
            </td>

        </tr>

    </table>


    {{-- ==========================================
         TABEL PENJUALAN
    =========================================== --}}

    <table class="table-penjualan">

        <thead>

            <tr>

                <th width="4%">
                    No
                </th>

                <th width="8%">
                    Tanggal
                </th>

                <th width="10%">
                    Nota
                </th>

                <th width="15%">
                    Pelanggan
                </th>

                <th width="10%">
                    Subtotal
                </th>

                <th width="9%">
                    Diskon
                </th>

                <th width="11%">
                    Penjualan
                </th>

                <th width="10%">
                    Bayar
                </th>

                <th width="10%">
                    Kembali
                </th>

                <th width="7%">
                    Status
                </th>

                <th width="10%">
                    Kasir
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($penjualan as $index => $row)

                @php

                    $subtotal =
                        (float) ($row->subtotal ?? 0);

                    $discount =
                        (float) ($row->total_discount ?? 0);

                    $belanja =
                        (float) ($row->belanja ?? 0);

                    $bayar =
                        (float) ($row->bayar ?? 0);

                    $kembali =
                        (float) ($row->kembali ?? 0);


                    $totalSubtotal += $subtotal;

                    $totalDiscount += $discount;

                    $totalPenjualan += $belanja;

                    $totalPembayaran += $bayar;

                    $totalKembali += $kembali;

                @endphp


                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>


                    <td class="text-center">
                        {{ $row->tanggal }}
                    </td>


                    <td>
                        {{ $row->nota }}
                    </td>


                    <td>
                        {{ $row->pelanggan?->nm_pelanggan ?? '-' }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($subtotal, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($discount, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($belanja, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($bayar, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($kembali, 0, ',', '.') }}
                    </td>


                    <td class="text-center">
                        {{ $row->status_pembayaran ?? '-' }}
                    </td>


                    <td>
                        {{ $row->kasir?->nama ?? '-' }}
                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="11"
                        class="text-center"
                    >
                        Tidak ada data penjualan
                    </td>

                </tr>

            @endforelse


            {{-- ==================================
                 TOTAL
            =================================== --}}

            @if($penjualan->count() > 0)

                <tr class="total-row">

                    <td
                        colspan="4"
                        class="text-right"
                    >
                        TOTAL
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalSubtotal, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalDiscount, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalPenjualan, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalPembayaran, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalKembali, 0, ',', '.') }}
                    </td>


                    <td>
                    </td>


                    <td>
                    </td>

                </tr>

            @endif

        </tbody>

    </table>


    {{-- ==========================================
         FOOTER
    =========================================== --}}

    <div class="footer">

        Dicetak:
        {{ date('d-m-Y H:i') }}

    </div>


</body>
</html>