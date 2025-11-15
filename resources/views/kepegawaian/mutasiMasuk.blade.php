<?php
use App\Http\Controllers\PegawaiController;
$cek = new PegawaiController();
?>
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
                    <h3>DAFTAR PEGAWAI MUTASI MASUK KE {{ config('global.nama_lain') }}</h3>
                </div>

                <table class="table table-bordered" style="font-size: .8rem;" id="mutasiKeluar">
                    <thead style="background-color: darkseagreen">
                        <tr>
                            <th>No</th>
                            <th>Asal OPD</th>
                            <th>Nama/NIP</th>
                            <th>Pangkat/Gol</th>
                            <th>Jabatan_Asal</th>
                            <th>Unit Organisasi Asal</th>
                            <th>TMT_Mutasi</th>
                            <th>Akhir_Absen</th>
                            <th>Respon</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $dt)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dt->asal_opd }}</td>
                                <td>{{ $dt->nama }} <br>NIP.{{ $dt->nip }}</td>
                                <td>{{ $dt->pangkat }}</td>
                                <td>{{ $dt->jabatan }}</td>
                                <td>{{ $dt->unit_organisasi }}</td>
                                <td>{{ $dt->tmt_mutasi }}</td>
                                <td>{{ $dt->akhir_absen }}</td>
                                <td class="text-center">
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal"
                                            data-target="#tambah_{{ $loop->iteration }}">Terima</button>
                                        <button class="btn btn-sm btn-danger tolakMutasi"
                                            idtolak={{ $dt->id }}>Tolak</button>
                                    </div>
                                </td>
                                <!-- Modal Tambah Pegawai-->
                                <div class="modal fade" id="tambah_{{ $loop->iteration }}" data-backdrop="static"
                                    data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                                <h5 class="modal-title text-center" id="staticBackdropLabel">Form
                                                    Terima/Penempatan Pegawai Mutasi ke {{ config('global.nama_lain') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body pr-4">
                                                <form action="/kepegawaian/tambahAsn" method="post"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="mutasi_masuk" value=true>
                                                    <input type="hidden" name="tmt_mutasi" value="{{ $dt->tmt_mutasi }}">
                                                    <div class="form-row">
                                                        <div class="col ">
                                                            <div class="form-group row">
                                                                <label for="nama"
                                                                    class="col-sm-3 col-form-label text-right">Nama</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control"
                                                                        id="nama" name="nama"
                                                                        value="{{ $dt->nama }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="nip"
                                                                    class="col-sm-3 col-form-label text-right">NIP</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" minlength="18" maxlength="18"
                                                                        onkeypress="return hanyaAngka (event)"
                                                                        class="form-control" id="nip" name="nip"
                                                                        value="{{ $dt->nip }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col ">
                                                            <div class="form-group row">
                                                                <label for="jenis_asn"
                                                                    class="col-sm-3 col-form-label text-right">Jenis
                                                                    ASN</label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select" id="jenis_asn"
                                                                        name="jenis_asn" required>
                                                                        <option value="" selected disabled>--pilih--
                                                                        </option>
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
                                                                    <select class="custom-select" id="pangkat"
                                                                        name="pangkat" readonly>
                                                                        <option>{{ $dt->pangkat }}</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="uker"
                                                                    class="col-sm-3 col-form-label text-right">Unit
                                                                    Kerja</label>
                                                                <div class="col-sm-9">
                                                                    <input type="hidden" name="kode_pd"
                                                                        value="{{ config('global.kode_pd') }}">
                                                                    <input type="text" class="form-control"
                                                                        id="uker" name="uker"
                                                                        value="{{ config('global.nama_pd') }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="uorg"
                                                                    class="col-sm-3 col-form-label text-right">Unit
                                                                    Org</label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select" id="uorg"
                                                                        name="uorg" required>
                                                                        <option value="" selected disabled>-- pilih
                                                                            --</option>
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
                                                                    <select class="selectpicker" id="jabatan"
                                                                        name="jabatan" data-live-search="true"
                                                                        data-style="custom-select" required>
                                                                        <option value="" selected disabled>-- pilih
                                                                            --</option>
                                                                        @foreach ($pokjab as $jabt)
                                                                            <option>{{ $jabt->nama_jabatan }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="jenjab"
                                                                    class="col-sm-3 col-form-label text-right">Jenis
                                                                    Jbt</label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select" id="jenjab"
                                                                        name="jenjab" required>
                                                                        <option value="" selected disabled>--pilih--
                                                                        </option>
                                                                        <option>Struktural</option>
                                                                        <option>Fungsional Khusus</option>
                                                                        <option>Fungsional Umum</option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="status_jbt"
                                                                    class="col-sm-3 col-form-label text-right">Status
                                                                    Jbt</label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select" id="status_jbt"
                                                                        name="status_jbt" required>
                                                                        <option value="Def">Definitif</option>
                                                                        <option value="Plt">Pelaksana Tugas (Plt)
                                                                        </option>
                                                                        <option value="Plh">Pelaksana Harian (Plh)
                                                                        </option>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="tpt_lain"
                                                                    class="col-sm-3 col-form-label text-right">Sub
                                                                    Unit</label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select" id="tpt_lain"
                                                                        name="tpt_lain">
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
                                                                    <select class="custom-select" id="opd_lain"
                                                                        name="opd_lain">
                                                                        <option value="|">Tidak Ada</option>
                                                                        @foreach ($opdlain as $opd)
                                                                            <option
                                                                                value="{{ $opd->kode_pd }}|{{ $opd->nama_lain }}">
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
                                                                    <input type="text" class="form-control tmt_absen"
                                                                        name="tmt_absen" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="col ">
                                                            <div class="form-group row">
                                                                <label for="jenkel"
                                                                    class="col-sm-3 col-form-label text-right">Jenis
                                                                    Kel</label>
                                                                <div class="col-sm-9">
                                                                    <select class="custom-select" id="jenkel"
                                                                        name="jenkel" readonly>
                                                                        <option>{{ $dt->jenkel }}</option>
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
                                                                        <input type="text" class="form-control"
                                                                            id="tpp" name="tpp" required>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group row">
                                                                <label for="norut"
                                                                    class="col-sm-3 col-form-label text-right">No
                                                                    Urut</label>
                                                                <div class="col-sm-3">
                                                                    <input type="number" class="form-control"
                                                                        id="norut" name="norut"
                                                                        value="{{ $pegawaiAsn->pluck('norut')->max() + 1 }}"
                                                                        required>

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
                                                                    <input type="file" class="form-control"
                                                                        id="foto_pegawai" placeholder="nominal rupiah"
                                                                        name="foto_pegawai">
                                                                    <img src="{{ asset('storage/foto_pegawai/no_image.png') }}"
                                                                        width="150" class="mt-2 float-right"
                                                                        id="gb_pegawai">
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" name="tambo"
                                                    class="btn btn-primary">Tambah</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>

                </table>




            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript" src="/js/jquery.mask.js"></script>
    <script>
        function showFoto(foto) {
            var path = '/storage/foto_pegawai/' + foto;

            Swal.fire({
                imageUrl: path,
                imageWidth: 480,
                imageHeight: 540,
                imageAlt: 'Custom image',
            })
        }

        $('#mutasiKeluar').DataTable();

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

        $('.tmt_absen').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
        });
        $('.tpp').mask('000.000.000', {
            reverse: true
        });
        $('.tolakMutasi').on('click', function() {
            var yakin = confirm('anda yakin tolak mutasi pegawai');
            if (yakin) {
                var idTolak = $(this).attr('idTolak');
                $.get('/kepegawaian/tolakMutasi', {
                    id: idTolak
                }).done(function(hasil) {
                    if (hasil == 1) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'berhasil ditolak',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(function() {
                            location.reload();
                        })

                    }
                });
            }

        });

        $(".foto_pegawai").change(function(event) {
            var fn = $(this).val();
            getURL(this, fn);
        });


        function getURL(input, fn) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var filename = fn;
                filename = filename.substring(filename.lastIndexOf('\\') + 1);
                var cekgb = filename.substring(filename.lastIndexOf('.') + 1);
                var size = input.files[0]['size'];

                if (cekgb == 'jpg' || cekgb == 'JPG' || cekgb == 'png' || cekgb == 'jpeg' || cekgb == 'jfif') {
                    if (input.files[0]['size'] > 204800) {
                        alert('ukuran file lebih dari 200 Kb');
                        $('.foto_pegawai').val("");

                    } else {

                        reader.onload = function(e) {
                            debugger;
                            $('.gb_pegawai').attr('src', e.target.result);
                            $('.gb_pegawai').hide();
                            $('.gb_pegawai').fadeIn(500);

                        }
                        reader.readAsDataURL(input.files[0]);

                    }

                } else {
                    alert("file harus berjenis 'jpg' , 'jpeg', 'png', atau 'jfif'");
                    $('.foto_pegawai').val("");
                    $('.gb_pegawai').attr('src', '/storage/foto_pegawai/no_image.png');


                }

                // reader.readAsDataURL(input.files[0]);
            }

        }
    </script>
@endpush
