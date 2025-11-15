@extends('templatesLTE.main')
@section('content')
    {{-- notifikasi --}}
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1000
            })
        </script>
    @endif

    {{-- notifikasi --}}
    @if (session()->has('fail'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('fail') }}',
                showConfirmButton: true
            })
        </script>
    @endif
    {{-- End Notifikasi --}}

    <div class="row">
        <div class="col-md-12 p-0 mx-0">
            <!-- Card Body -->
            <div class="card-body bg-white">
                <div class="alert alert-warning mb-2 text-center">
                    <h3>DAFTAR ASN, TAHUN {{ $tahun }}</h3>
                </div>
                <div class="d-flex">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-sm btn-primary mt-2 mb-4" data-toggle="modal"
                        data-target="#tambagPegawai">
                        Tambah Data ASN
                    </button>
                    <button type="button" class="btn btn-sm btn-primary mt-2 mb-4 ml-2" data-toggle="modal"
                        data-target="#tandaTangan">
                        Penandatangan
                    </button>
                    <form action="/cetakDaftarAsn" method="get" enctype="multipart/form-data" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger mt-2 mb-4 ml-2"><img src="/img/pdf.png"
                            width="18px">&ensp; Export PDF</button>
                    </form>
                </div>        

                <!-- Modal Tambah Pegawai-->
                <div class="modal fade" id="tambagPegawai" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title text-center" id="staticBackdropLabel">Form Tambah Data Pegawai
                                    ASN </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body pr-4">
                                <form action="/kepegawaian/tambahAsn" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">
                                        <div class="col ">
                                            <div class="form-group row">
                                                <label for="nama"
                                                    class="col-sm-3 col-form-label text-right">Nama</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="nama" name="nama"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="nip" class="col-sm-3 col-form-label text-right">NIP</label>
                                                <div class="col-sm-9">
                                                    <input type="text" minlength="18" maxlength="18"
                                                        onkeypress="return hanyaAngka (event)" class="form-control"
                                                        id="nip" name="nip" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col ">
                                            <div class="form-group row">
                                                <label for="jenis_asn" class="col-sm-3 col-form-label text-right">Jenis
                                                    ASN</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="jenis_asn" name="jenis_asn" required>
                                                        <option value="" selected disabled>--pilih--</option>
                                                        <option>PNS</option>
                                                        <option>PPPK</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="pangkat"
                                                    class="col-sm-3 col-form-label text-right">Pangkat/Gol</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="pangkat" name="pangkat" required>
                                                        <option value="" selected disabled>--pilih--</option>
                                                        <option>Pembina Utama (IV/e)</option>
                                                        <option>Pembina Utama Madya (IV/d)</option>
                                                        <option>Pembina Utama Muda (IV/c)</option>
                                                        <option>Pembina Tingkat I (IV/b)</option>
                                                        <option>Pembina (IV/a)</option>

                                                        <option>Penata Tingkat I (III/d)</option>
                                                        <option>Penata (III/c)</option>
                                                        <option>Penata Muda Tingkat I (III/b)</option>
                                                        <option>Penata Muda (III/a)</option>

                                                        <option>Pengatur Tingkat I (II/d)</option>
                                                        <option>Pengatur (II/c)</option>
                                                        <option>Pengatur Muda Tingkat I (II/b)</option>
                                                        <option>Pengatur Muda (II/a)</option>

                                                        <option>Juru Tingkat I (I/d)</option>
                                                        <option>Juru (I/c)</option>
                                                        <option>Juru Muda Tingkat I (I/b)</option>
                                                        <option>Juru Muda (I/a)</option>

                                                        <option>PPPK (XVII)</option>
                                                        <option>PPPK (XVI)</option>
                                                        <option>PPPK (XV)</option>
                                                        <option>PPPK (XIV)</option>
                                                        <option>PPPK (XIII)</option>

                                                        <option>PPPK (XII)</option>
                                                        <option>PPPK (XI)</option>
                                                        <option>PPPK (X)</option>
                                                        <option>PPPK (IX)</option>
                                                        <option>PPPK (VIII)</option>

                                                        <option>PPPK (VII)</option>
                                                        <option>PPPK (VI)</option>
                                                        <option>PPPK (V)</option>
                                                        <option>PPPK (IV)</option>
                                                        <option>PPPK (III)</option>
                                                        <option>PPPK (II)</option>
                                                        <option>PPPK (I)</option>

                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="uker" class="col-sm-3 col-form-label text-right">Unit
                                                    Kerja</label>
                                                <div class="col-sm-9">
                                                    <input type="hidden" name="kode_pd"
                                                        value="{{ config('global.kode_pd') }}">
                                                    <input type="text" class="form-control" id="uker" name="uker"
                                                        value="{{ config('global.nama_pd') }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="uorg" class="col-sm-3 col-form-label text-right">Unit
                                                    Org</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="uorg" name="uorg" required>
                                                        <option value="" selected disabled>-- pilih --</option>
                                                        @foreach ($unors as $unor)
                                                            <option
                                                                value="{{ $unor->kode_unit }}|{{ $unor->unit_organisasi }}|{{ $unor->lat }}|{{ $unor->lot }}">
                                                                {{ $unor->unit_organisasi }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="form-row">


                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="jabatan"
                                                    class="col-sm-3 col-form-label text-right">Jabatan</label>
                                                <div class="col-sm-9">
                                                    <select class="selectpicker"
                                                        id="jabatan"
                                                        name="jabatan"
                                                        data-live-search="true"
                                                        data-style="custom-select"
                                                        required>                                                       
                                                        <option value="" selected disabled>-- pilih --</option>
                                                        @foreach ($pokjab as $jabt)
                                                            <option>{{ $jabt->nama_jabatan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="jenjab" class="col-sm-3 col-form-label text-right">Jenis
                                                    Jbt</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="jenjab" name="jenjab" required>
                                                        <option value="" selected disabled>--pilih--</option>
                                                        <option>Struktural</option>
                                                        <option>Fungsional Khusus</option>
                                                        <option>Fungsional Umum</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="status_jbt" class="col-sm-3 col-form-label text-right">Status
                                                    Jbt</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="status_jbt" name="status_jbt"
                                                        required>
                                                        <option value="Def">Definitif</option>
                                                        <option value="Plt">Pelaksana Tugas (Plt)</option>
                                                        <option value="Plh">Pelaksana Harian (Plh)</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="tpt_lain" class="col-sm-3 col-form-label text-right">Sub
                                                    Unit</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="tpt_lain" name="tpt_lain">
                                                        <option value="|">Tidak Ada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="opd_lain"
                                                    class="col-sm-3 col-form-label text-right">Plt.<small>OPD_Lain</small></label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="opd_lain" name="opd_lain">
                                                        <option value="|">Tidak Ada</option>
                                                        @foreach ($opdlain as $opd)
                                                            <option value="{{ $opd->kode_pd }}|{{ $opd->nama_lain }}">
                                                                {{ $opd->nama_lain }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="tmt_absen"
                                                    class="col-sm-3 col-form-label text-right">TMT_Absen</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="tmt_absen"
                                                        name="tmt_absen" autocomplete="off" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col ">
                                            <div class="form-group row">
                                                <label for="jenkel" class="col-sm-3 col-form-label text-right">Jenis
                                                    Kel</label>
                                                <div class="col-sm-9">
                                                    <select class="custom-select" id="jenkel" name="jenkel" required>
                                                        <option value="" selected disabled>--pilih--</option>
                                                        <option>Laki-laki</option>
                                                        <option>Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="tpp"
                                                    class="col-sm-3 col-form-label text-right">TPP</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">Rp.</div>
                                                        </div>
                                                        <input type="text" class="form-control" id="tpp"
                                                            name="tpp" required>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="norut" class="col-sm-3 col-form-label text-right">No
                                                    Urut</label>
                                                <div class="col-sm-3">
                                                    <input type="number" class="form-control" id="norut"
                                                        name="norut"
                                                        value="{{ $pegawaiAsn->pluck('norut')->max() + 1 }}" required>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col"></div>
                                        <div class="col">
                                            <div class="form-group row">
                                                <label for="foto"
                                                    class="col-sm-3 col-form-label text-right">foto</label>
                                                <div class="col-sm-9">
                                                    <input type="file" class="form-control" id="foto_pegawai"
                                                        placeholder="nominal rupiah" name="foto_pegawai">
                                                    <img src="{{ asset('storage/foto_pegawai/no_image.png') }}"
                                                        width="150" class="mt-2 float-right" id="gb_pegawai">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col"></div>
                                    </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="tambo" class="btn btn-primary">Tambah</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Penandatangtan -->
                <div class="modal fade" id="tandaTangan" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title text-center" id="staticBackdropLabel">Penandatangan Rekap Absensi
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body pr-4">
                                <form action="/kepegawaian/tambahPenandatangan" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <label for="kepala">Kepala
                                            OPD</label>
                                        <select class="custom-select" id="kepala" name="kepala" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            @foreach ($pegawaiAsn as $peg)
                                                <option value="{{ $peg->nip }},{{ $peg->nama }}"
                                                    {{ $peg->nip == $admin->nip_kepala ? 'selected' : '' }}>
                                                    {{ $peg->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nip_kepala">NIP Kepala OPD</label>
                                        <input type="text" class="form-control" name="nip_kepala" id="nip_kepala"
                                            value="{{ $admin->nip_kepala ?? '' }}" required readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="sekretaris">Sekretaris</label>
                                        <select class="custom-select" id="sekretaris" name="sekretaris" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            @foreach ($pegawaiAsn as $peg)
                                                <option value="{{ $peg->nip }},{{ $peg->nama }}"
                                                    {{ $peg->nip == $admin->nip_sekretaris ? 'selected' : '' }}>
                                                    {{ $peg->nama }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="form-group">
                                        <label for="nip_sekretaris">NIP Sekretaris</label>
                                        <input type="text" class="form-control" name="nip_sekretaris"
                                            id="nip_sekretaris" value="{{ $admin->nip_sekretaris ?? '' }}" required
                                            readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="admin_absen">Admin
                                            Absensi</label>
                                        <select class="custom-select" id="admin_absen" name="admin_absen" required>
                                            <option value="" selected disabled>--pilih--</option>
                                            @foreach ($pegawaiAsn as $peg)
                                                <option value="{{ $peg->nip }},{{ $peg->nama }}"
                                                    {{ $peg->nip == $admin->nip_admin ? 'selected' : '' }}>
                                                    {{ $peg->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nip_admin">NIP Admin Absensi</label>
                                        <input type="text" class="form-control" name="nip_admin" id="nip_admin"
                                            value="{{ $admin->nip_admin ?? '' }}" required readonly>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="tambo" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>




                <div id="testPegawai"></div>



                <table class="table table-bordered" style="font-size: .8rem;" id="daftarAsn">
                    <thead style="background-color: rgb(176, 152, 118)">
                        <tr class="text-dark">
                            <th>Norut</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Pangkat</th>
                            <th>Jabatan</th>
                            <th>Unit Organisasi <br>Tpt Tugas Lain</th>
                            <th>TMT_Absen</th>
                            <th>TPP <br>(Rp)</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                </table>

            </div>
        </div>

        <!-- Modal Update -->
        <div class="modal fade" id="updateAsn" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-center" id="staticBackdropLabel">Form Update Data
                            Pegawai
                            ASN </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="/kepegawaian/updateAsn" method="post" enctype="multipart/form-data"
                        class="formUpdate">
                        @csrf
                        <div class="modal-body pr-4" id="isiUpdate">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                            <button type="submit" name="tambo" class="btn btn-primary">UPDATE</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>


        <!-- Modal Mutasi -->
        <div class="modal fade" id="mutasiPegawai" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title alert alert-warning text-center" id="staticBackdropLabel">FORM MUTASI
                            PEGAWAI
                            / PENSIUN / BERHENTI KAB.LAMPUNG UTARA
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="badge badge-success d-flex text-wrap ml-3 mr-4">
                        <p style="color: white; font-size: 13px;">Anda disarankan Memproses Mutasi Setelah Jam Pulang (atau
                            setelah yg bersangkutan mengisi absen pulang terlebih dahulu..!!)</p>
                    </div>
                    <form action="/kepegawaian/prosesMutasi" method="post">
                        @csrf
                        <div class="modal-body" id="body_mutasi">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                            <button type="submit" class="btn btn-primary">SUBMIT</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    @endsection

    @push('script')
        <script type="text/javascript" src="/js/jquery.mask.js"></script>
        <script>
            $('#daftarAsn').DataTable({
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route('dataAsn') }}',
                columns: [{
                        data: 'norut',
                        name: 'norut',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'nip',
                        name: 'nip',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'pangkat',
                        name: 'pangkat',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'unit_organisasi',
                        name: 'unit_organisasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tmt_absen',
                        name: 'tmt_absen',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'tpp',
                        name: 'tpp',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ],
                search: {
                    "regex": true
                }
            });

            $('#kepala').on('change', function() {
                var nip = $(this).val();
                nip = nip.split(',');
                $('#nip_kepala').val(nip[0]);
            });
            $('#sekretaris').on('change', function() {
                var nip = $(this).val();
                nip = nip.split(',');
                $('#nip_sekretaris').val(nip[0]);
            });
            $('#admin_absen').on('change', function() {
                var nip = $(this).val();
                nip = nip.split(',');
                $('#nip_admin').val(nip[0]);
            });
        </script>
        <script>
            function gbAbsen(foto) {
                var path = '/storage/foto_pegawai/' + foto;

                Swal.fire({
                    imageUrl: path,
                    imageWidth: 480,
                    imageHeight: 540,
                    imageAlt: 'Custom image',
                })
            };

            function updatePegawai(id) {

                $.ajax({
                    'type': 'get',
                    'url': '/cobaPegawai',
                    'data': {
                        'nama': 'redho',
                        'istri': 'yesi',
                        'id': id
                    },
                    success: function(hasil) {
                        $('#updateAsn').modal('show');
                        $('#isiUpdate').html(hasil);
                    }
                });
            }

            function mutasiPegawai(id) {

                $.ajax({
                    'type': 'get',
                    'url': '/dataMutasiPegawai',
                    'data': {
                        'id': id
                    },
                    success: function(hasil) {
                        $('#mutasiPegawai').modal('show');
                        $('#body_mutasi').html(hasil);
                    }
                });
            }

            $('#uorg').on('change', function() {
                var uorg = $(this).val();
                var kode_uorg = uorg.split('|');
                $.get("/kepegawaian/ajaxTpt", {
                    'kode_uorg': kode_uorg[0]
                }).done(function(hasil) {
                    $('#tpt_lain').html(hasil);

                });
            });

            $('.uorg').on('change', function() {

                var uorg = $(this).val();
                var kode_uorg = uorg.split('|');
                var form = $(this).parents('.form-row');
                var rowSub = form.siblings('.rowSub');
                var tpt = rowSub.find('.tpt_lain');

                $.get("/kepegawaian/ajaxTpt", {
                    'kode_uorg': kode_uorg[0]
                }).done(function(hasil) {
                    tpt.html("");
                    tpt.html(hasil);

                });
            });



            function hanyaAngka(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))

                    return false;
                return true;
            }
            $('#tpp').mask('000.000.000', {
                reverse: true
            });
            $('.tpp').mask('000.000.000', {
                reverse: true
            });
            $('.edtpp').mask('000.000.000', {
                reverse: true
            });
            $('#tmt_absen').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });

            $('.edtmt_absen').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });

            $("#foto_pegawai").change(function(event) {
                getURL(this);
            });


            function getURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    var filename = $("#foto_pegawai").val();
                    filename = filename.substring(filename.lastIndexOf('\\') + 1);
                    var cekgb = filename.substring(filename.lastIndexOf('.') + 1);
                    var size = input.files[0]['size'];

                    if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif') {
                        if (input.files[0]['size'] > 204800) {
                            alert('ukuran file lebih dari 200 Kb');
                            $('#foto_pegawai').val("");

                        } else {

                            reader.onload = function(e) {
                                debugger;
                                $('#gb_pegawai').attr('src', e.target.result);
                                $('#gb_pegawai').hide();
                                $('#gb_pegawai').fadeIn(500);

                            }
                            reader.readAsDataURL(input.files[0]);

                        }

                    } else {
                        alert("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
                        $('#foto_pegawai').val("");
                        $('#gb_pegawai').attr('src', '../img/foto_pegawai/no_image.png');


                    }

                    // reader.readAsDataURL(input.files[0]);
                }

            }

            $(".edfoto_pegawai").change(function(event) {
                var edgb = $(this).siblings('.edgb');
                var filename = $(this).val();
                getURL2(this, filename, edgb);
            });


            function getURL2(input, nfile, edgb) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    var filename = nfile;
                    filename = filename.substring(filename.lastIndexOf('\\') + 1);
                    var cekgb = filename.substring(filename.lastIndexOf('.') + 1);
                    if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif' || cekgb ==
                        'PNG') {

                        if (input.files[0]['size'] > 204800) {
                            alert('ukuran file lebih dari 200 Kb');
                            $('.edfoto_pegawai').val("");
                        } else {

                            reader.onload = function(e) {
                                debugger;
                                edgb.attr('src', e.target.result);
                                edgb.hide();
                                edgb.fadeIn(500);
                            }
                            reader.readAsDataURL(input.files[0]);
                        }

                    } else {
                        alert("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
                        $('.edfoto_pegawai').val("");
                        $('.edgb').attr('src', '../img/foto_pegawai/no_image.png');


                    }

                    // reader.readAsDataURL(input.files[0]);
                }




            }


            function hapusPegawai(id) {
                var idpeg = id;
                var cls = $('.hapus_' + id);
                var tr = cls.parents('tr');

                var yakin = confirm('apakah anda yakin?');
                if (yakin) {
                    $.ajax({
                        'type': 'get',
                        'url': '/kepegawaian/hapusAsn',
                        'data': 'idpeg=' + idpeg,
                        success: function(hasil) {
                            if (hasil == 1) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'data berhasil dihapus',
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                tr.remove();
                            }
                        }
                    });
                }
            }
        </script>
        <script>
            $(function() {
            $('.selectpicker').selectpicker();
            });
        </script>

    @endpush
