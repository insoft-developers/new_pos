<style>
    /* ============================================================
       GENERAL
    ============================================================ */

    .pos-page {

        background: #f5f7f9;

        min-height: calc(100vh - 60px);

    }


    /* ============================================================
       HEADER
    ============================================================ */

    .pos-header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        background: #fff;

        border-radius: 14px;

        padding: 16px 20px;

        box-shadow:
            0 2px 12px rgba(0, 0, 0, .04);

    }

    .pos-logo {

        width: 46px;

        height: 46px;

        border-radius: 12px;

        background: #e9f7ef;

        color: #198754;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 25px;

    }

    .nota-box {

        text-align: right;

    }

    .nota-label {

        font-size: 10px;

        letter-spacing: 1px;

        color: #98a2ad;

        font-weight: 700;

    }

    .nota-number {

        font-size: 17px;

        font-weight: 800;

        color: #198754;

    }


    /* ============================================================
       CUSTOMER
    ============================================================ */

    .customer-card {

        display: flex;

        align-items: center;

        gap: 15px;

        background: #fff;

        padding: 13px 18px;

        border-radius: 14px;

        box-shadow:
            0 2px 12px rgba(0, 0, 0, .04);

    }

    .customer-icon {

        width: 42px;

        height: 42px;

        border-radius: 10px;

        background: #eef7f2;

        color: #198754;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 21px;

    }

    .customer-content {

        width: 300px;

    }

    .customer-label {

        font-size: 10px;

        font-weight: 700;

        color: #98a2ad;

        letter-spacing: .5px;

        margin-bottom: 3px;

    }

    .customer-select {

        border: 0;

        padding: 0;

        font-weight: 600;

        color: #343a40;

        width: 100%;

        outline: none;

    }

    .customer-divider {

        width: 1px;

        height: 38px;

        background: #e9ecef;

    }

    .date-content {

        width: 170px;

    }

    .date-input {

        border: 0;

        padding: 0;

        font-weight: 600;

        color: #343a40;

        outline: none;

    }

    .customer-add {

        border: 1px solid #e4e8eb;

        font-weight: 600;

    }


    /* ============================================================
       CART
    ============================================================ */

    .cart-card {

        background: #fff;

        border-radius: 14px;

        overflow: hidden;

        box-shadow:
            0 2px 12px rgba(0, 0, 0, .04);

    }

    .cart-header {

        padding: 17px 20px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        border-bottom: 1px solid #edf0f2;

    }

    .cart-title {

        font-weight: 750;

        font-size: 15px;

    }

    .cart-title i {

        color: #198754;

        margin-right: 5px;

    }

    .cart-subtitle {

        font-size: 11px;

        color: #98a2ad;

        margin-top: 3px;

    }

    .btn-add-product {

        background: #198754;

        color: white;

        border: 0;

        border-radius: 9px;

        padding: 9px 15px;

        font-weight: 650;

    }

    .btn-add-product:hover {

        background: #157347;

        color: white;

    }

    .pos-table thead th {

        background: #f8fafb;

        color: #8b96a1;

        font-size: 10px;

        font-weight: 750;

        letter-spacing: .5px;

        padding: 11px 12px;

        border: 0;

    }

    .pos-table tbody td {

        padding: 12px;

        border-color: #f0f2f4;

        font-size: 13px;

    }

    .cart-footer {

        padding: 11px 18px;

        border-top: 1px solid #edf0f2;

        display: flex;

        justify-content: space-between;

        color: #98a2ad;

        font-size: 11px;

    }

    .item-count {

        background: #eef7f2;

        color: #198754;

        padding: 4px 9px;

        border-radius: 20px;

        font-weight: 700;

    }


    /* ============================================================
       EMPTY
    ============================================================ */

    .empty-state {

        padding: 55px 20px;

        text-align: center;

    }

    .empty-icon {

        width: 70px;

        height: 70px;

        border-radius: 50%;

        background: #f1f4f6;

        color: #b4bdc5;

        display: flex;

        align-items: center;

        justify-content: center;

        margin: auto;

        font-size: 34px;

    }

    .empty-title {

        margin-top: 14px;

        font-weight: 700;

        color: #495057;

    }

    .empty-description {

        font-size: 12px;

        color: #adb5bd;

        margin-top: 4px;

    }


    /* ============================================================
       PAYMENT
    ============================================================ */

    .payment-card {

        background: #fff;

        border-radius: 14px;

        overflow: hidden;

        box-shadow:
            0 2px 12px rgba(0, 0, 0, .04);

    }

    .payment-header {

        padding: 17px 20px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        border-bottom: 1px solid #edf0f2;

    }

    .payment-title {

        font-size: 15px;

        font-weight: 750;

    }

    .payment-header>i {

        color: #198754;

        font-size: 21px;

    }

    .payment-body {

        padding: 18px;

    }

    .payment-row {

        display: flex;

        justify-content: space-between;

        align-items: center;

        margin-bottom: 11px;

        font-size: 13px;

        color: #7c8791;

    }

    .payment-row strong {

        color: #343a40;

    }

    .discount-value {

        color: #dc3545 !important;

    }

    .payment-line {

        border-top: 1px dashed #dfe3e6;

        margin: 16px 0;

    }

    .grand-total {

        display: flex;

        justify-content: space-between;

        align-items: center;

    }

    .grand-label {

        font-size: 11px;

        font-weight: 800;

        color: #495057;

    }

    .grand-caption {

        font-size: 10px;

        color: #adb5bd;

        margin-top: 2px;

    }

    .grand-value {

        font-size: 25px;

        font-weight: 850;

        color: #198754;

    }

    .payment-input-section {

        margin-top: 20px;

        margin-bottom: 14px;

    }

    .payment-input-section label,
    .payment-label {

        display: block;

        font-size: 11px;

        font-weight: 700;

        color: #68737d;

        margin-bottom: 6px;

    }

    .payment-input {

        display: flex;

        align-items: center;

        border: 1px solid #dfe4e8;

        border-radius: 9px;

        overflow: hidden;

    }

    .payment-input span {

        background: #f7f8f9;

        padding: 10px 12px;

        color: #6c757d;

        font-weight: 600;

    }

    .payment-input input {

        border: 0;

        outline: none;

        width: 100%;

        padding: 10px;

        text-align: right;

        font-size: 17px;

        font-weight: 750;

    }

    .payment-select {

        border-radius: 8px;

    }

    .change-box {

        display: flex;

        justify-content: space-between;

        align-items: center;

        background: #f1faf5;

        border: 1px solid #d9f1e3;

        border-radius: 10px;

        padding: 11px 13px;

    }

    .change-label {

        font-size: 10px;

        font-weight: 800;

        color: #198754;

    }

    .change-caption {

        font-size: 10px;

        color: #8ba598;

    }

    .change-box strong {

        color: #198754;

        font-size: 16px;

    }

    .btn-save {

        width: 100%;

        border: 0;

        border-radius: 10px;

        padding: 12px;

        background: #198754;

        color: white;

        font-weight: 700;

        font-size: 14px;

    }

    .btn-save:hover {

        background: #157347;

        color: white;

    }


    /* ============================================================
       MODAL
    ============================================================ */

    .product-modal {

        border: 0;

        border-radius: 15px;

        overflow: hidden;

    }

    .product-modal-header {

        padding: 17px 20px;

        border-bottom: 1px solid #edf0f2;

    }

    .modal-product-icon {

        width: 42px;

        height: 42px;

        border-radius: 10px;

        background: #eaf7f0;

        color: #198754;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 21px;

    }

    .product-search-area {

        padding: 15px 20px;

        background: #fafbfc;

        border-bottom: 1px solid #edf0f2;

    }

    .search-box {

        height: 40px;

        background: white;

        border: 1px solid #dfe4e8;

        border-radius: 8px;

        display: flex;

        align-items: center;

        padding: 0 11px;

    }

    .search-box>i {

        color: #98a2ad;

        font-size: 19px;

        margin-right: 8px;

    }

    .search-box input {

        border: 0;

        outline: 0;

        width: 100%;

        font-size: 13px;

    }

    .search-box kbd {

        font-size: 9px;

        color: #98a2ad;

    }

    .product-table-wrapper {

        max-height: 480px;

        overflow-y: auto;

    }

    .product-table thead th {

        position: sticky;

        top: 0;

        z-index: 2;

        background: #f8fafb;

        font-size: 10px;

        color: #89939d;

        letter-spacing: .5px;

        border: 0;

        padding: 11px 14px;

    }

    .product-table tbody td {

        padding: 11px 14px;

        font-size: 13px;

        border-color: #f0f2f4;

    }

    .product-select-row {

        cursor: pointer;

        transition: .15s;

    }

    .product-select-row:hover {

        background: #f3faf6;

    }

    .product-modal-price {

        color: #198754;

        font-weight: 750;

    }

    .stock-good {

        color: #198754;

        font-weight: 700;

    }

    .stock-low {

        color: #dc3545;

        font-weight: 700;

    }

    .product-modal-footer {

        padding: 12px 20px;

        border-top: 1px solid #edf0f2;

        display: flex;

        justify-content: space-between;

        align-items: center;

    }


    /* ============================================================
       MOBILE
    ============================================================ */

    @media(max-width: 768px) {

        .customer-card {

            flex-wrap: wrap;

        }

        .customer-content {

            width: calc(100% - 60px);

        }

        .customer-divider {

            display: none;

        }

        .date-content {

            width: 100%;

        }

        .customer-add {

            width: 100%;

        }

        .cart-header {

            gap: 10px;

            flex-direction: column;

            align-items: flex-start;

        }

        .btn-add-product {

            width: 100%;

        }

        .grand-value {

            font-size: 20px;

        }

    }

    /* ============================================================ CART PRODUCT ============================================================ */
    .cart-item-row {
        transition: background .15s ease;
    }

    .cart-item-row:hover {
        background: #fafcfb;
    }

    /* NOMOR */
    .cart-number {
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f4f6;
        color: #7b8791;
        font-size: 11px;
        font-weight: 700;
    }

    /* NAMA PRODUK */
    .cart-product-name {
        font-size: 14px;
        font-weight: 750;
        color: #263238;
        line-height: 1.4;
    }

    /* DETAIL PRODUK */
    .cart-product-detail {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 5px;
        font-size: 11px;
        color: #8b969f;
    }

    .cart-product-code {
        color: #198754;
        font-weight: 650;
    }

    .cart-product-separator {
        color: #c5cbd0;
    }

    .cart-price {
        color: #5f6b74;
        font-weight: 600;
    }

    /* ============================================================ QTY ============================================================ */
    .cart-qty {
        display: inline-flex;
        align-items: center;
        border: 1px solid #e1e5e8;
        border-radius: 6px;
        overflow: hidden;
        background: #fff;
    }

    .qty-btn {
        width: 23px;
        height: 22px;
        padding: 0;
        border: 0;
        background: #f7f8f9;
        color: #68737c;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .qty-btn:hover {
        background: #e9f7ef;
        color: #198754;
    }

    .qty-input {
        width: 38px;
        height: 22px;
        border: 0;
        outline: none;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: #343a40;
    }

    /* hilangkan arrow input number */
    .qty-input::-webkit-inner-spin-button,
    .qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cart-unit {
        color: #68737c;
        font-weight: 600;
    }

    /* ============================================================ DISCOUNT ============================================================ */
    .discount-input {
        display: flex;
        align-items: center;
        border: 1px solid #e0e4e7;
        border-radius: 7px;
        overflow: hidden;
        background: #fff;
    }

    .discount-input span {
        padding: 6px 7px;
        background: #f7f8f9;
        color: #98a2ad;
        font-size: 10px;
        font-weight: 700;
    }

    .discount-input input {
        width: 100%;
        min-width: 90px;
        border: 0;
        outline: none;
        padding: 6px 8px;
        text-align: right;
        font-size: 12px;
        font-weight: 600;
    }

    /* ============================================================ SUBTOTAL ============================================================ */
    .cart-subtotal {
        font-size: 15px;
        font-weight: 800;
        color: #198754;
    }

    .cart-discount-info {
        margin-top: 3px;
        font-size: 10px;
        color: #dc3545;
        font-weight: 600;
    }

    /* ============================================================ DELETE ============================================================ */
    .btn-delete-item {
        width: 32px;
        height: 32px;
        border: 0;
        border-radius: 8px;
        background: #fff1f2;
        color: #dc3545;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-delete-item:hover {
        background: #dc3545;
        color: #fff;
    }

    .price-type-wrapper {

    display: flex;

    align-items: center;

    gap: 7px;

    margin-top: 7px;

}


.price-type-label {

    font-size: 10px;

    color: #9aa3aa;

}


.price-switch {

    display: inline-flex;

    padding: 2px;

    background: #f1f3f5;

    border-radius: 6px;

    gap: 2px;

}


.price-switch-btn {

    border: 0;

    background: transparent;

    color: #7a858d;

    font-size: 10px;

    font-weight: 600;

    padding: 3px 9px;

    border-radius: 5px;

    cursor: pointer;

    transition: all .15s ease;

}


.price-switch-btn:hover {

    color: #198754;

}


.price-switch-btn.active {

    background: #198754;

    color: #fff;

    box-shadow: 0 1px 3px rgba(0,0,0,.08);

}


.price-switch-btn.active.reseller {

    background: #0d6efd;

}
</style>
