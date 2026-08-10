<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>Daftar Pembayaran</title>

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

        .table-piutang {
            width: 100%;
            border-collapse: collapse;
        }

        .table-piutang th {
            background: #e9ecef;
            border: 1px solid #999;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
        }

        .table-piutang td {
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
            DAFTAR PEMBAYARAN
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
            $tanggalDari = date('d-m-Y', strtotime($tanggal_dari));
        }

        $tanggalSampai = 'Semua';

        if (!empty($tanggal_sampai)) {
            $tanggalSampai = date('d-m-Y', strtotime($tanggal_sampai));
        }

        // ------------------------------------------
        // CUSTOMER
        // ------------------------------------------

        $pelanggan = '';

        if (!empty($customer)) {
            $cust = \App\Models\Pelanggan::where('kd_pelanggan', $customer)->first();
            $pelanggan = $cust->nm_pelanggan ?? '';
        } else {
            $pelanggan = 'Semua Pelanggan';
        }

        // ------------------------------------------
        // KASIR
        // ------------------------------------------

       

        // ------------------------------------------
        // TOTAL
        // ------------------------------------------

        $totalNilai = 0;
        $totalBayar = 0;
        $totalSisa = 0;

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
                {{ $pelanggan }}
            </td>

        </tr>

        

    </table>


    {{-- ==========================================
         TABEL PENJUALAN
    =========================================== --}}

    <table class="table-piutang">

        <thead>

            <tr>

                <th width="4%">
                    No
                </th>

                <th width="8%">
                    Tanggal
                </th>

                <th width="10%">
                    No Pembayaran
                </th>

                <th width="10%">
                    Nota
                </th>

                <th width="15%">
                    Pelanggan
                </th>

                <th width="10%">
                    Nilai
                </th>

                <th width="9%">
                    Bayar
                </th>

                <th width="11%">
                    Sisa
                </th>

               
                <th width="10%">
                    Kasir
                </th>
                <th width="10%">
                    Keterangan
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($pembayaran as $index => $row)

                @php

                    $nilai = (float) ($row->nilai_nota ?? 0);

                    $bayar = (float) ($row->pembayaran ?? 0);

                    $sisa = (float) ($row->sisa ?? 0);

                    $totalNilai += $nilai;

                    $totalBayar += $bayar;

                    $totalSisa += $sisa;
                @endphp


                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>


                    <td class="text-center">
                        {{ !empty($row->tanggal) ? date('d-m-Y', strtotime($row->tanggal)) : '' }}
                    </td>


                    <td>
                        {{ $row->no_pembayaran }}
                    </td>

                     <td>
                        {{ $row->nota }}
                    </td>



                    <td>
                        {{ $row->customer?->nm_pelanggan ?? '-' }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($nilai, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($bayar, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($sisa, 0, ',', '.') }}
                    </td>
                   

                    <td>
                        {{ $row->kasir?->nama ?? '-' }}
                    </td>
                    <td>
                        {{ $row->keterangan ?? '' }}
                    </td>

                </tr>


            @empty

                <tr>

                    <td colspan="9" class="text-center">
                        Tidak ada data pembayaran
                    </td>

                </tr>
            @endforelse


            {{-- ==================================
                 TOTAL
            =================================== --}}

            @if ($pembayaran->count() > 0)
                <tr class="total-row">

                    <td colspan="5" class="text-right">
                        TOTAL
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalNilai, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalBayar, 0, ',', '.') }}
                    </td>


                    <td class="text-right">
                        Rp
                        {{ number_format($totalSisa, 0, ',', '.') }}
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
