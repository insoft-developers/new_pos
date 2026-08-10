 <div class="main-menu">
            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a href="index.html" class="logo-light">
                    <img src="{{ asset('images/logo3.png') }}" alt="logo" class="logo-lg" height="48">
                    <img src="{{ asset('images/logo3.png') }}" alt="small logo" class="logo-sm" height="24">
                </a>

                <!-- Brand Logo Dark -->
                <a href="index.html" class="logo-dark">
                    <img src="{{ asset('images/logo3.png') }}" alt="dark logo" class="logo-lg" height="48">
                    <img src="{{ asset('images/logo3.png') }}" alt="small logo" class="logo-sm" height="24">
                </a>
            </div>

            <!--- Menu -->
            <div data-simplebar>
                <ul class="app-menu">

                    
                    <li class="menu-title"></li>
                    <li class="menu-item">
                        <a href="{{ url('/') }}" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="airplay "></i></span>
                            <span class="menu-text"> Dashboard </span>
                            
                        </a>
                    </li>

                    <li class="menu-title">Menu Utama</li>

                   
                    <li class="menu-item">
                        <a href="#menuExpages" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="database"></i></span>
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
                            <span class="menu-icon"><i data-lucide="shopping-cart"></i></span>
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
                        <a href="#menuPembelian" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="shopping-bag"></i></span>
                            <span class="menu-text"> Pembelian </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuPembelian">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="{{ url('pembelian/create') }}" class="menu-link">
                                        <span class="menu-text">Tambah Pembelian</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('pembelian') }}" class="menu-link">
                                        <span class="menu-text">Daftar Pembelian</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>


                    <li class="menu-item">
                        <a href="#menuKeuangan" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="dollar-sign"></i></span>
                            <span class="menu-text"> Keuangan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="menuKeuangan">
                            <ul class="sub-menu">
                                <li class="menu-item">
                                    <a href="{{ url('piutang') }}" class="menu-link">
                                        <span class="menu-text">Piutang</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ url('pembayaran') }}" class="menu-link">
                                        <span class="menu-text">Pembayaran</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>

                    <li class="menu-item">
                        <a href="#menuExtendedui" data-bs-toggle="collapse" class="menu-link waves-effect">
                            <span class="menu-icon"><i data-lucide="pie-chart"></i></span>
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
                            <span class="menu-icon"><i data-lucide="settings"></i></span>
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