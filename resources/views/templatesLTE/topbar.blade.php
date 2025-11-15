<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-info navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="ml-3"><span class="d-flex">
                <img src="/img/logo.png" width="30">
                <h3 class="adopd ml-3 ">ADMIN E-ABSENSI
                    {{ config('global.nama_lain') }}
                </h3>
            </span>
        </li>
        <li class="nav-item ml-4">
            <img src="/img/yaibettah2.png" width="40">
        </li>
    </ul>



    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge" style="display: none">
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Notifications</span>
                <div class="dropdown-divider"></div>

                <a href="" class="dropdown-item">

                    <i class="fas fa-users mr-2"></i>
                    paket data absensi!
                    <span class="float-right text-muted text-sm">hours</span>
                </a>
                <div class="dropdown-divider"></div>

            </div>

        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell mr-2"></i>
                <span class="badge badge-danger navbar-badge mr-2" style="display: none">

                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    Notifications
                </span>
                <div class="dropdown-divider"></div>

                <a href="" class="dropdown-item">

                    <i class="fas fa-users mr-2"></i>
                    Pegawai Mutasi ke OPD Anda!
                    <span class="float-right text-muted text-sm">
                        hours
                    </span>
                </a>
                <div class="dropdown-divider"></div>

            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-cogs"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Profile Admin</span>
                <div class="dropdown-divider"></div>

                <a class="btn dropdown-item" data-toggle="modal" data-target="#exampleModal">

                    <i class="fas fa-user mr-2"></i> Change Password

                </a>
                <a href="/logout" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                </a>
                <div class="dropdown-divider"></div>

            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="exampleModalLabel">Admin Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="/admin/changePass" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="oldPass">Password Lama</label>
                        <input type="password" class="form-control" id="oldPass" name="passlama" required>
                        <div class="form-group form-check float-right">
                            <input type="checkbox" class="form-check-input" id="showOld">
                            <label class="form-check-label" for="showOld">show password</label>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="passbaru">Password Baru</label>
                        <input type="password" minlength="6" maxlength="10" class="form-control" id="passbaru"
                            name="passbaru" required>
                        <div class="form-group form-check float-right">
                            <input type="checkbox" class="form-check-input" id="showBaru">
                            <label class="form-check-label" for="showBaru">show password</label>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Change</button>
            </div>
            </form>
        </div>
    </div>
</div>