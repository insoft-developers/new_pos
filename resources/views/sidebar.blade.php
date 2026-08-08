 <div class="main-menu">
            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="index.html" class="logo-light">
                    <img src="{{ asset('template') }}/assets/images/logo-light.png" alt="logo" class="logo-lg" height="18">
                    <img src="{{ asset('template') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="24">
                </a>

                <!-- Brand Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <img src="{{ asset('template') }}/assets/images/logo-dark.png" alt="dark logo" class="logo-lg" height="18">
                    <img src="{{ asset('template') }}/assets/images/logo-sm.png" alt="small logo" class="logo-sm" height="24">
                </a>
            </div>

            <!--- Menu -->
            <div data-simplebar>
                <ul class="app-menu">

                    <li class="menu-title">Menu</li>

                    <li class="menu-item">
                        <a href="{{ url('/') }}" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="airplay "></i></span>
                            <span class="menu-text"> Home </span>
                            <span class="badge bg-info rounded-pill ms-auto">3</span>
                        </a>
                    </li>

                    <li class="menu-title"></li>
                    <li class="menu-item">
                        <a href="#menuExpages" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="copy"></i></span>
                            <span class="menu-text"> Master Data </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuExpages">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="{{ url('/barang') }}" class="menu-link">
                                        <span class="menu-text">Data Barang</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('/pelanggan') }}" class="menu-link">
                                        <span class="menu-text">Data Pelanggan</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('supplier') }}" class="menu-link">
                                        <span class="menu-text">Data Supplier</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('pengguna') }}" class="menu-link">
                                        <span class="menu-text">Data Pengguna</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>


                    <li class="menu-item">
                        <a href="#menuComponentsui" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="briefcase"></i></span>
                            <span class="menu-text"> Penjualan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuComponentsui">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="{{ url('pos') }}" class="menu-link">
                                        <span class="menu-text">POS</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('penjualan') }}" class="menu-link">
                                        <span class="menu-text">Daftar Penjualan</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>

                    <li class="menu-item">
                        <a href="#menuExtendedui" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="layers-3"></i></span>
                            <span class="menu-text"> Laporan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuExtendedui">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="components-range-slider.html" class="menu-link">
                                        <span class="menu-text">Laporan Penjualan</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="components-sweet-alert.html" class="menu-link">
                                        <span class="menu-text">Laporan Pembelian</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="components-loading-buttons.html" class="menu-link">
                                        <span class="menu-text">Laporan Pembayaran</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="components-loading-buttons.html" class="menu-link">
                                        <span class="menu-text">Laporan Piutang</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="menu-item">
                        <a href="#menuIcons" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="box"></i></span>
                            <span class="menu-text"> Pengaturan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuIcons">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="icons-boxicons.html" class="menu-link">
                                        <span class="menu-text">Perusahaan</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="icons-lucide.html" class="menu-link">
                                        <span class="menu-text">Profil</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="icons-mdi.html" class="menu-link">
                                        <span class="menu-text">Ganti Password</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                   
                </ul>
            </div>
        </div>