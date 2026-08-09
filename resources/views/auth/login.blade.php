<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Log In | i-Kasir </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Drezoc - Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="MyraStudio" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('template') }}/assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('template') }}/assets/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('template') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="{{ asset('template') }}/assets/js/config.js"></script>

    <style>
        .login-card {
            width: 380px;
            max-width: 100%;
        }

        body {
            background: #f5f6f8 !important;
        }

        .account-pages {
            background-image: none !important;
            background: #f5f6f8 !important;
        }

        .auth-fluid {
            background-image: none !important;
        }

        .auth-fluid-right,
        .auth-fluid-left {
            display: none !important;
        }

        .auth-fluid {
            min-height: 100vh;
        }

        .card {
            width: 380px;
            max-width: 100%;
            margin: auto;
        }
    </style>
</head>

<body>
    <div>

        <div class="container-fluid">

            <div class="row justify-content-center align-items-center min-vh-100">

                <div class="col-11 col-sm-8 col-md-5 col-lg-4 col-xl-3">

                    <div class="card shadow-sm border-0">

                        <div class="card-body p-4">

                            {{-- LOGO --}}
                            <div class="text-center mb-4">

                                <a href="{{ url('/') }}" class="logo-light d-inline-block">

                                    <img src="{{ asset('images') }}/logo2.png"
                                        alt="{{ env('NAMA_TOKO') }}" height="85">

                                </a>

                            </div>


                            {{-- JUDUL --}}
                            <div class="text-center mb-4">

                                <h1 class="h5 mb-1">
                                    Selamat Datang
                                </h1>

                                <p class="text-muted mb-0">
                                    Silakan login untuk melanjutkan
                                </p>

                            </div>


                            {{-- ERROR LOGIN --}}
                            @if (session('error'))
                                <div class="alert alert-danger py-2">
                                    {{ session('error') }}
                                </div>
                            @endif


                            {{-- VALIDATION ERROR --}}
                            @if ($errors->any())
                                <div class="alert alert-danger py-2">

                                    {{ $errors->first() }}

                                </div>
                            @endif


                            {{-- FORM LOGIN --}}
                            <form action="{{ route('login.process') }}" method="POST">

                                @csrf


                                {{-- USERNAME --}}
                                <div class="form-group mb-3">

                                    <label class="form-label" for="nm_pengguna">
                                        Username
                                    </label>

                                    <input class="form-control" type="text" id="nm_pengguna" name="nm_pengguna"
                                        value="{{ old('nm_pengguna') }}" required autofocus autocomplete="username"
                                        placeholder="Masukkan username">

                                </div>


                                {{-- PASSWORD --}}
                                <div class="form-group mb-3">

                                    <label class="form-label" for="password">
                                        Password
                                    </label>

                                    <input class="form-control" type="password" id="password" name="password" required
                                        autocomplete="current-password" placeholder="Masukkan password">

                                </div>


                                {{-- REMEMBER --}}
                                <div class="form-group mb-3">

                                    <div>

                                        <input class="form-check-input" type="checkbox" id="checkbox-signin">

                                        <label class="form-check-label ms-2" for="checkbox-signin">
                                            Ingat saya
                                        </label>

                                    </div>

                                </div>


                                {{-- BUTTON --}}
                                <div class="form-group mb-0">

                                    <button class="btn btn-primary w-100" type="submit">

                                        <i class="mdi mdi-login me-1"></i>
                                        Login

                                    </button>

                                </div>

                            </form>


                            {{-- FOOTER --}}
                            <div class="text-center mt-4">

                                <small class="text-muted">
                                    © {{ date('Y') }}
                                    {{ env('NAMA_TOKO') }}
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- App js -->
    <script src="{{ asset('template') }}/assets/js/vendor.min.js"></script>
    <script src="{{ asset('template') }}/assets/js/app.js"></script>

</body>

</html>
