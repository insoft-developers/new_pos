
@extends('master')

@section('content')

<div class="content-page">

    <div class="content">

        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">

                <div class="flex-grow-1">

                    <h4 class="fs-18 fw-semibold m-0">
                        Profil Saya
                    </h4>

                </div>

                <div class="text-end">

                    <ol class="breadcrumb m-0 py-0">

                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">
                                Pengaturan
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            Profil
                        </li>

                    </ol>

                </div>

            </div>


            {{-- FORM PROFIL --}}
            <div class="row">

                <div class="col-lg-8">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white border-bottom">

                            <h5 class="mb-0 fw-bold">

                                <i class="mdi mdi-account-edit-outline text-primary me-1"></i>

                                Edit Profil

                            </h5>

                            <small class="text-muted">
                                Perbarui informasi profil Anda
                            </small>

                        </div>


                        <div class="card-body">

                            <form id="form-profil">

                                @csrf


                                {{-- NAMA --}}
                                <input type="hidden" id="kd_pengguna" name="kd_pengguna">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama Lengkap
                                    </label>

                                    <input type="text"
                                           name="nama"
                                           id="nama"
                                           class="form-insoft2"
                                           required>

                                </div>


                                {{-- USERNAME --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Username
                                    </label>

                                    <input type="text"
                                           name="nm_pengguna"
                                           id="nm_pengguna"
                                           class="form-insoft2"
                                           readonly>

                                </div>


                                {{-- EMAIL --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Alamat
                                    </label>

                                    <textarea
                                           name="alamat"
                                           id="alamat"
                                           class="form-insoft2"
                                           ></textarea>

                                </div>


                                {{-- NOMOR HP --}}

                                <div class="mb-4">

                                    <label class="form-label">
                                        Nomor HP
                                    </label>

                                    <input type="text"
                                           name="telepon"
                                           id="telepon"
                                           class="form-insoft2"
                                           >

                                </div>


                                <div class="mb-4">

                                    <label class="form-label">
                                        Level
                                    </label>

                                    <input readonly type="text"
                                           name="level"
                                           id="level"
                                           class="form-insoft2"
                                           >

                                </div>


                                {{-- SIMPAN --}}

                                <div class="d-flex ">

                                    <button type="submit"
                                            id="btn-simpan"
                                            class="btn btn-primary">

                                        <i class="mdi mdi-content-save-outline me-1"></i>

                                        Simpan Perubahan

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
    @include('pages.profile.js')
@endpush
