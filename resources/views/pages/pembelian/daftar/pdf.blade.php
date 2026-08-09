<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">

    <title>Daftar Pembelian</title>

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
            DAFTAR PEMBELIAN
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



        $supplier = "";

        if(!empty($supplier_nama)) {
            $cust = \App\Models\Supplier::where('kd_supplier', $supplier_nama)->first();
            $supplier = $cust->nm_supplier ?? '';
        } else {
            $supplier = 'Semua Supplier';
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
        $totalPembelian = 0;
       

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
                Supplier
            </td>

            <td>
                {{ $supplier }}
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
                    Supplier
                </th>

                <th width="10%">
                    Subtotal
                </th>

                <th width="9%">
                    Diskon
                </th>

                <th width="11%">
                    Pembelian
                </th>

                <th width="10%">
                    Kasir
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($pembelian as $index => $row)

                @php

                    $subtotal =
                        (float) ($row->subtotal ?? 0);

                    $discount =
                        (float) ($row->total_discount ?? 0);

                    $belanja =
                        (float) ($row->total_pembelian ?? 0);


                    $totalSubtotal += $subtotal;

                    $totalDiscount += $discount;

                    $totalPembelian += $belanja;


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
                        {{ $row->supplier?->nm_supplier ?? '-' }}
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

                    <td>
                        {{ $row->kasir?->nama ?? '-' }}
                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="8
                        class="text-center"
                    >
                        Tidak ada data pembelian
                    </td>

                </tr>

            @endforelse


            {{-- ==================================
                 TOTAL
            =================================== --}}

            @if($pembelian->count() > 0)

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
                        {{ number_format($totalPembelian, 0, ',', '.') }}
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