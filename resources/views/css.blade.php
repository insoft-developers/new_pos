<style>
    .pull-right {
        float: right !important;
    }

    .btn-tambah {
        float: right !important;
        margin-top: -20px !important;
    }

    .nama-toko {
        font-size: 26px;
        font-weight: bold;
        margin-top: -10px;
    }

    .alamat-toko {
        display: block;
        position: relative;
        left: -228px;
        top: 25px;
        color: #3f87fd;
    }

    .form-insoft {
        padding: 2px 10px 2px 14px;
        border: 2px solid whitesmoke;
        width: 50%;
    }


    .form-insoft2 {
        padding: 2px 10px 2px 14px;
        border: 2px solid whitesmoke;
        width: 50%;
        display: block;
    }
</style>

@if ($view != 'dashboard')
    <style>
        .card {
            border-radius: 12px;
        }

        .card-header {
            padding: 18px 20px;
        }

        /* Table */
        #list-table {
            font-size: 12px;
        }

        #list-table thead th {
            background: white;
            color: black;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
            padding: 10px 8px;
        }

        #list-table tbody td {
            padding: 3px 8px 3px 8px;
            vertical-align: middle;
        }

        #list-table tbody tr:hover {
            background: #f8fbff;
        }

        /* DataTables Search */
        .dataTables_filter input {
            border-radius: 20px;
            padding: .35rem .8rem;
        }

        /* Button */
        .btn-tambah {
            padding: 6px 18px;
            font-weight: 600;
        }

        /* Badge stok */
        .badge-stock {
            font-size: 11px;
        }


        /* Chrome, Safari, Edge */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .row .card-body {
            width: 78vw !important;
        }

        .text-end .breadcrumb {
            padding-right: 20px !important;
        }
    </style>
@endif
