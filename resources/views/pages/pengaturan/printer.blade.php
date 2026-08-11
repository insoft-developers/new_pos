@extends('master')

@section('content')

<div class="content-page">

    <div class="content">

        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">

                <div class="flex-grow-1">

                    <h4 class="fs-18 fw-semibold m-0">
                        Pengaturan Printer
                    </h4>

                    <small class="text-muted">
                        Atur printer yang digunakan untuk mencetak transaksi
                    </small>

                </div>

                <div class="text-end">

                    <ol class="breadcrumb m-0 py-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Pengaturan
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Printer
                        </li>

                    </ol>

                </div>

            </div>


            {{-- CONTENT --}}
            <div class="row">

                <div class="col-lg-8 col-xl-6">

                    <div class="card shadow-sm border-0">

                        {{-- CARD HEADER --}}
                        <div class="card-header bg-white border-bottom">

                            <div class="d-flex align-items-center">

                                <div
                                    class="avatar-sm rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3">

                                    <i class="mdi mdi-printer-outline fs-22"></i>

                                </div>

                                <div>

                                    <h5 class="mb-0 fw-bold">
                                        Pengaturan Printer
                                    </h5>

                                    <small class="text-muted">
                                        Pilih jenis printer yang digunakan
                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- CARD BODY --}}
                        <div class="card-body">

                            <form id="form-printer">

                                @csrf


                                {{-- JENIS PRINTER --}}
                                <div class="mb-4">

                                    <label
                                        for="printer_setting"
                                        class="form-label fw-semibold">

                                        Printer yang Digunakan

                                    </label>


                                    <select
                                        name="printer_setting"
                                        id="printer_setting"
                                        class="form-select form-insoft">

                                        <option value="">
                                            -- Pilih Printer --
                                        </option>


                                        <option
                                            value="besar"
                                            {{ ($pengaturan->printer_setting ?? '') == 'besar' ? 'selected' : '' }}>

                                            Printer Besar

                                        </option>


                                        <option
                                            value="kecil"
                                            {{ ($pengaturan->printer_setting ?? '') == 'kecil' ? 'selected' : '' }}>

                                            Printer Kecil (Thermal 58mm)

                                        </option>

                                    </select>


                                    <div class="form-text">

                                        <i class="mdi mdi-information-outline"></i>

                                        Printer besar digunakan untuk kertas biasa,
                                        sedangkan printer kecil untuk struk 58mm.

                                    </div>

                                </div>



                                {{-- INFO PRINTER --}}
                                <div class="row g-3 mb-4">


                                    {{-- PRINTER BESAR --}}
                                    <div class="col-md-6">

                                        <div class="border rounded p-3 h-100">

                                            <div class="d-flex align-items-center mb-2">

                                                <div
                                                    class="avatar-sm rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2">

                                                    <i class="mdi mdi-printer fs-20"></i>

                                                </div>

                                                <strong>
                                                    Printer Besar
                                                </strong>

                                            </div>


                                            <small class="text-muted">

                                                Digunakan untuk mencetak
                                                dokumen atau laporan dengan
                                                printer ukuran kertas besar.

                                            </small>

                                        </div>

                                    </div>


                                    {{-- PRINTER KECIL --}}
                                    <div class="col-md-6">

                                        <div class="border rounded p-3 h-100">

                                            <div class="d-flex align-items-center mb-2">

                                                <div
                                                    class="avatar-sm rounded bg-success-subtle text-success d-flex align-items-center justify-content-center me-2">

                                                    <i class="mdi mdi-printer-pos fs-20"></i>

                                                </div>

                                                <strong>
                                                    Printer Kecil
                                                </strong>

                                            </div>


                                            <small class="text-muted">

                                                Digunakan untuk mencetak
                                                struk thermal ukuran 58mm.

                                            </small>

                                        </div>

                                    </div>


                                </div>



                                {{-- BUTTON --}}
                                <div class="d-flex justify-content-end">

                                    <button
                                        type="submit"
                                        id="btn-simpan"
                                        class="btn btn-primary">

                                        <i class="mdi mdi-content-save-outline me-1"></i>

                                        Simpan Pengaturan

                                    </button>

                                </div>


                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection



@push('scripts')

<script>

$(document).ready(function () {


    $('#form-printer').submit(function (e) {

        e.preventDefault();


        let button = $('#btn-simpan');


        button.prop('disabled', true);

        button.html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Menyimpan...
        `);


        $.ajax({

            url: "{{ route('pengaturan.printer.update') }}",

            type: "POST",

            data: $(this).serialize(),


            success: function (response) {


                if (response.success) {


                    Swal.fire({

                        icon: 'success',

                        title: 'Berhasil',

                        text: response.message,

                        timer: 1500,

                        showConfirmButton: false

                    });

                }

            },


            error: function (xhr) {


                let message = 'Terjadi kesalahan.';


                if (xhr.responseJSON) {

                    if (xhr.responseJSON.message) {

                        message = xhr.responseJSON.message;

                    }


                    if (xhr.responseJSON.errors) {

                        let errors = xhr.responseJSON.errors;

                        message = Object.values(errors)
                            .flat()
                            .join('<br>');

                    }

                }


                Swal.fire({

                    icon: 'error',

                    title: 'Gagal',

                    html: message

                });

            },


            complete: function () {


                button.prop('disabled', false);


                button.html(`

                    <i class="mdi mdi-content-save-outline me-1"></i>

                    Simpan Pengaturan

                `);

            }

        });

    });


});

</script>

@endpush