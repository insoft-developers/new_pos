<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="index.html" class="logo-light">
                    <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo-lg"
                        height="20">
                    <img src="{{ asset('images/logo.png') }}" alt="small logo" class="logo-sm"
                        height="20">
                </a>

                <!-- Brand Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <img src="{{ asset('images/logo.png') }}" alt="dark logo" class="logo-lg"
                        height="20">
                    <img src="{{asset('images/logo.png') }}" alt="small logo" class="logo-sm"
                        height="20">
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu waves-effect waves-dark rounded-circle">
                <i class="mdi mdi-menu"></i>
            </button>
            <h5 class="nama-toko">{{ env('NAMA_TOKO') }}</h5>
            <p class="alamat-toko">{{ env('ALAMAT_TOKO1') }} - {{ env('ALAMAT_TOKO2') }} </p>
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-2">
           
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-dark" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('template') }}/assets/images/users/avatar-1.jpg" alt="user-image"
                        class="rounded-circle">
                    <span class="ms-1 d-none d-md-inline-block">
                        {{ session('nm_pengguna') }} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-lucide="user" class="font-size-16 me-2"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-lucide="settings" class="font-size-16 me-2"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="pages-lock-screen.html" class="dropdown-item notify-item">
                        <i data-lucide="lock" class="font-size-16 me-2"></i>
                        <span>Lock Screen</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf

                        <button type="submit"
                            class="dropdown-item notify-item border-0 bg-transparent w-100 text-start">
                            <i data-lucide="log-out" class="font-size-16 me-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                    


                </div>
            </li>

        </ul>
    </div>
</div>
<!-- ========== Topbar End ========== -->
