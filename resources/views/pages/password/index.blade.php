
@extends('master')

@section('content')

<div class="content-page">

    <div class="content">

        <div class="container-fluid">

            {{-- HEADER --}}
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">

                <div class="flex-grow-1">

                    <h4 class="fs-18 fw-semibold m-0">
                        Ganti Password
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
                            Ganti Password
                        </li>

                    </ol>

                </div>

            </div>


            <div class="row">

                <div class="col-lg-7">

                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-white border-bottom">

                            <h5 class="mb-0 fw-bold">

                                <i class="mdi mdi-lock-reset text-primary me-1"></i>

                                Ganti Password

                            </h5>

                            <small class="text-muted">
                                Gunakan password baru untuk meningkatkan keamanan akun.
                            </small>

                        </div>


                        <div class="card-body">

                            <form id="form-password">

                                @csrf


                                {{-- PASSWORD LAMA --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Password Lama
                                    </label>

                                    <div class="input-group">

                                        <input type="password"
                                               name="password_lama"
                                               id="password_lama"
                                               class="form-insoft"
                                               placeholder="Masukkan password lama"
                                               required>

                                        <button type="button"
                                                class="btn btn-light"
                                                onclick="togglePassword('password_lama', this)">

                                            <i class="mdi mdi-eye-outline"></i>

                                        </button>

                                    </div>

                                </div>


                                {{-- PASSWORD BARU --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Password Baru
                                    </label>

                                    <div class="input-group">

                                        <input type="password"
                                               name="password"
                                               id="password"
                                               class="form-insoft"
                                               placeholder="Masukkan password baru"
                                               minlength="6"
                                               required>

                                        <button type="button"
                                                class="btn btn-light"
                                                onclick="togglePassword('password', this)">

                                            <i class="mdi mdi-eye-outline"></i>

                                        </button>

                                    </div>

                                    <small class="text-muted">
                                        Minimal 6 karakter.
                                    </small>

                                </div>


                                {{-- KONFIRMASI PASSWORD --}}

                                <div class="mb-4">

                                    <label class="form-label">
                                        Konfirmasi Password Baru
                                    </label>

                                    <div class="input-group">

                                        <input type="password"
                                               name="password_confirmation"
                                               id="password_confirmation"
                                               class="form-insoft"
                                               placeholder="Ulangi password baru"
                                               required>

                                        <button type="button"
                                                class="btn btn-light"
                                                onclick="togglePassword('password_confirmation', this)">

                                            <i class="mdi mdi-eye-outline"></i>

                                        </button>

                                    </div>

                                </div>


                                {{-- BUTTON --}}

                                <div class="d-flex justify-content-end">

                                    <button type="submit"
                                            id="btn-password"
                                            class="btn btn-primary">

                                        <i class="mdi mdi-content-save-outline me-1"></i>

                                        Simpan Password

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- INFORMASI --}}

                <div class="col-lg-5">

                    <div class="card shadow-sm border-0">

                        <div class="card-body">

                            <div class="d-flex">

                                <div class="me-3">

                                    <div class="avatar-sm">

                                        <div class="avatar-title rounded bg-primary-subtle text-primary">

                                            <i class="mdi mdi-shield-lock-outline fs-20"></i>

                                        </div>

                                    </div>

                                </div>


                                <div>

                                    <h6 class="fw-bold mb-2">
                                        Tips Keamanan
                                    </h6>

                                    <ul class="text-muted mb-0 ps-3">

                                        <li class="mb-2">
                                            Gunakan password minimal 6 karakter.
                                        </li>

                                        <li class="mb-2">
                                            Jangan menggunakan password yang mudah ditebak.
                                        </li>

                                        <li>
                                            Jangan berikan password kepada orang lain.
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
@include('pages.password.js')
@endpush
