
<style>
    @page {
        size: Letter;
        margin: 10mm;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: Arial, Helvetica, sans-serif;
        color: #111;
    }

    .receipt {
        width: 100%;
        min-height: 139.7mm;
        padding: 10mm;
        margin: 0 auto;
    }

    .header {
        text-align: center;
        margin-bottom: 12px;
    }

    .store-name {
        font-size: 22px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .store-address {
        font-size: 12px;
        margin-top: 4px;
    }

    .store-phone {
        font-size: 12px;
        margin-top: 2px;
    }

    .document-title {
        font-size: 16px;
        font-weight: 700;
        margin-top: 10px;
        text-transform: uppercase;
    }

    .line {
        border-top: 1px dashed #222;
        margin: 10px 0;
    }

    .info {
        display: grid;
        grid-template-columns: 80px 1fr 90px 1fr;
        font-size: 12px;
        line-height: 20px;
    }

    .info-label {
        font-weight: 600;
    }

    .items {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        font-size: 12px;
    }

    .items th {
        border-bottom: 1px solid #222;
        padding: 6px 4px;
        text-align: left;
    }

    .items td {
        padding: 6px 4px;
        vertical-align: top;
    }

    .items .qty {
        width: 55px;
        text-align: center;
    }

    .items .price {
        width: 110px;
        text-align: right;
    }

    .items .total {
        width: 120px;
        text-align: right;
    }

    .product-name {
        font-weight: 600;
    }

    .product-detail {
        font-size: 10px;
        color: #555;
        margin-top: 2px;
    }

    .summary {
        width: 300px;
        margin-left: auto;
        margin-top: 10px;
        font-size: 13px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
    }

    .summary-row.total {
        border-top: 1px solid #222;
        border-bottom: 1px solid #222;
        margin-top: 5px;
        padding: 8px 0;
        font-size: 16px;
        font-weight: 700;
    }

    .payment {
        margin-top: 10px;
        font-size: 13px;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        padding: 3px 0;
    }

    .status {
        text-align: center;
        margin-top: 12px;
        font-size: 14px;
        font-weight: 700;
    }

    .footer {
        text-align: center;
        margin-top: 25px;
        font-size: 12px;
    }

    .footer strong {
        display: block;
        margin-bottom: 4px;
    }

    @media print {
        body {
            margin: 0;
        }

        .receipt {
            min-height: 139.7mm;
        }
    }
</style>


<div class="receipt">

    {{-- HEADER TOKO --}}
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

        <div class="document-title">
            Bukti Pembelian
        </div>

    </div>


    <div class="line"></div>


    {{-- INFORMASI PEMBELIAN --}}
    <div class="info">

        <div class="info-label">
            Nota
        </div>

        <div>
            {{ $pembelian->nota }}
        </div>


        <div class="info-label">
            Tanggal
        </div>

        <div>
            {{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d/m/Y') }}
        </div>


        <div class="info-label">
            Supplier
        </div>

        <div>
            {{ $supplier->nm_supplier ?? $pembelian->kd_supplier }}
        </div>


        <div class="info-label">
            Kode
        </div>

        <div>
            {{ $pembelian->kd_supplier }}
        </div>


        <div class="info-label">
            Kasir
        </div>

        <div>
            {{ $pembelian->kasir?->nama ?? '' }}
        </div>


        @if(!empty($pembelian->jatuh_tempo))

            <div class="info-label">
                Jatuh Tempo
            </div>

            <div>
                {{ \Carbon\Carbon::parse($pembelian->jatuh_tempo)->format('d/m/Y') }}
            </div>

        @endif

    </div>


    <div class="line"></div>


    {{-- DETAIL BARANG --}}
    <table class="items">

        <thead>
            <tr>

                <th>
                    Barang
                </th>

                <th class="qty">
                    Qty
                </th>

                <th class="price">
                    Harga
                </th>

                <th class="total">
                    Total
                </th>

            </tr>
        </thead>


        <tbody>

        @foreach($items as $item)

            <tr>

                <td>

                    <div class="product-name">
                        {{ $item->nm_barang }}
                    </div>

                    <div class="product-detail">

                        {{ $item->kd_barang }}

                        @if(!empty($item->satuan))
                            • {{ $item->satuan }}
                        @endif

                        
                    </div>

                    
                    @if(isset($item->diskon) && $item->diskon > 0)

                        <div class="product-detail">

                            Diskon:
                            Rp {{ number_format($item->diskon, 0, ',', '.') }}

                        </div>

                    @endif

                </td>


                <td class="qty">

                    {{ number_format($item->jumlah, 0, ',', '.') }}

                </td>


                <td class="price">

                    Rp {{ number_format($item->harga, 0, ',', '.') }}

                </td>


                <td class="total">

                    Rp {{ number_format($item->total, 0, ',', '.') }}

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>


    <div class="line"></div>


    {{-- RINGKASAN --}}
    <div class="summary">

        <div class="summary-row">

            <span>
                Subtotal
            </span>

            <span>
                Rp {{ number_format($pembelian->subtotal, 0, ',', '.') }}
            </span>

        </div>


        @if(isset($pembelian->total_discount) && $pembelian->total_discount > 0)

            <div class="summary-row">

                <span>
                    Diskon
                </span>

                <span>
                    Rp {{ number_format($pembelian->total_discount, 0, ',', '.') }}
                </span>

            </div>

        @endif


        @if(isset($pembelian->ongkir) && $pembelian->ongkir > 0)

            <div class="summary-row">

                <span>
                    Ongkir
                </span>

                <span>
                    Rp {{ number_format($pembelian->ongkir, 0, ',', '.') }}
                </span>

            </div>

        @endif


        <div class="summary-row total">

            <span>
                TOTAL
            </span>

            <span>
                Rp {{ number_format($pembelian->total_pembelian, 0, ',', '.') }}
            </span>

        </div>

    </div>


  
    <div class="line"></div>


    {{-- FOOTER --}}
    <div class="footer">

        <strong>
            Pembelian Barang
        </strong>

        Dokumen ini merupakan bukti pembelian barang dari supplier.

    </div>

</div>

