<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="/img/logo.png" alt="AdminLTE Logo" class="brand-image img-fluid elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light text-white ml-2">e-absensi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/storage/foto_pegawai/{{ $foto_admin }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#">{{ $admin->admin_absen }}</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class=" mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->
                <li class="nav-item {{ Request::is('dashboard*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Dashboard Absensi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/dashboard/absenMasuk"
                                class="nav-link {{ Request::is('dashboard/absenMasuk*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Absen Masuk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/dashboard/absenPulang"
                                class="nav-link {{ Request::is('dashboard/absenPulang*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Absensi Pulang</p>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a href="/dashboard/absenHarian"
                                class="nav-link {{ Request::is('dashboard/absenHarian*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Rekap Absensi Harian</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/dashboard/unconfirm"
                                class="nav-link {{ Request::is('dashboard/unconfirm*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Un_Confirmed</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('kepegawaian*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('kepegawaian*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Kepegawaian
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/kepegawaian/pegawaiAsn"
                                class="nav-link {{ Request::is('kepegawaian/pegawaiAsn*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Daftar ASN</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/kepegawaian/nonAsn"
                                class="nav-link {{ Request::is('kepegawaian/nonAsn*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Daftar Non-ASN</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/kepegawaian/mutasiKeluar"
                                class="nav-link {{ Request::is('kepegawaian/mutasiKeluar') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Mutasi Keluar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/kepegawaian/mutasiMasuk"
                                class="nav-link {{ Request::is('kepegawaian/mutasiMasuk') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Mutasi Masuk</p>
                                @if($notifMutasi->count())
                                <span class="badge badge-warning right">{{ $notifMutasi->count() }}</span>
                                @endif
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('shift*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('shift*') ? 'active' : '' }}">
                        <i class="nav-icon far fa-clock"></i>
                        <p>
                            Shifting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/shift/pengaturan"
                                class="nav-link {{ Request::is('shift/pengaturan*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Pengaturan Shift</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/shift/absen"
                                class="nav-link {{ Request::is('shift/absen*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="far fa-circle nav-icon ml-2" style="font-size: .7rem"></i>
                                <p>Absen Shift</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('rekapitulasi*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('rekapitulasi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard"></i>
                        <p>
                            Rekapitulasi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/rekapitulasi/asn"
                                class="nav-link {{ Request::is('rekapitulasi/asn*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="nav-icon fas fa-caret-right"></i>
                                <p>Rekap Absensi ASN</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/rekapitulasi/NonAsn"
                                class="nav-link {{ Request::is('rekapitulasi/Non*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="nav-icon fas fa-caret-right"></i>
                                <p>Rekap Absensi Non-ASN</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/rekapitulasi/Apel"
                                class="nav-link {{ Request::is('rekapitulasi/Apel*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="nav-icon fas fa-caret-right"></i>
                                <p>Rekap Apel</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/rekapitulasi/tpp"
                                class="nav-link {{ Request::is('rekapitulasi/tpp*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="nav-icon fas fa-caret-right"></i>
                                <p>Rekap TPP Kehadiran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/rekapitulasi/dinasLuar"
                                class="nav-link {{ Request::is('rekapitulasi/dinasLuar*') ? 'active' : '' }}"
                                style="font-size: .9rem">
                                <i class="nav-icon fas fa-caret-right"></i>
                                <p>Rekap Dinas Luar</p>
                            </a>
                        </li>

                    </ul>
                </li>
                
            </ul>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>