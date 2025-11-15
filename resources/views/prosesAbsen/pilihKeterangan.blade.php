@extends('templates.main')
@section('content')
    <?php
    use Illuminate\Support\Facades\Crypt;
    use Carbon\Carbon;
    ?>

    <style>
        .wfo {
            background: linear-gradient(to right, #D6B7B7, #FAFAD6);
        }

        .wfh {
            background: linear-gradient(to right, #D1F9F5, #FAFAD6);
        }

        .tmk {
            background: linear-gradient(to right, #F6DA8E, #FAFAD6);
        }

        .gbab {
            width: 100px;
            height: 75px;
        }

        #submenu {
            background-color: rgb(52, 64, 74);
        }

        a {
            text-decoration: none;
        }

        a:hover {
            background-color: #8CD486;
            text-decoration: none;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <section>
                <h3 align="center" id="menu" class="alert alert-success mb-0" style="position: relative"><i
                        class="fas fa-bars float-left"></i>PILIH KETERANGAN
                </h3>
                <div id="submenu" class="pt-4 pr-4 mt-0"
                    style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                    <ul style=" list-style-type: none;">
                        <li class="mb-2 pr-4"><a href="/rekapPerorangan" class="text-white"><i
                                    class="fas fa-list-alt"></i>&emsp;Rekap Individu</a>
                        </li>
                        <li class="mb-2 pr-4"><a href="/logout" class="text-white"><i
                                    class="fas fa-sign-out-alt"></i>&emsp;Log
                                Out</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
        <div class="col mt-3" id="wrap">
            <div class="card">
                <center>
                    <form action="/absensi/cekAbsen" method="post">
                        @csrf
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item wfo">
                                <h4>Work From Office</h4>
                                <button class="btn btn-success mb-2 mr-2" type="submit" name="apel" value="true"><img
                                        class="gbab" src="/img/apel_pagi.jpg"><br>Apel_Pagi</button>
                                <button class="btn btn-success mb-2" type="submit" name="wfomasuk" value="true"><img
                                        class="gbab" src="/img/wfomasuk3.jpg"><br>Masuk</button>
                                <button class="btn btn-info ml-2 mb-2" type="submit" name="wfopulang" value="true">
                                    <img class="gbab" src="/img/wfopulang2.jpeg"><br>Pulang</button>
                            </li>
                            <li class="list-group-item wfh" style="display: none;">
                                <h4>Work From Home</h4>
                                <button class="btn btn-success" type="submit" name="wfhmasuk"><img class="gbab"
                                        src="/img/wfh.png"><br>Absen Masuk</button>
                                <button class="btn btn-info ml-2" type="submit" name="wfhpulang"><img class="gbab"
                                        src="/img/wfh.png"><br>Absen Selesai</button>
                            </li>
                            <li class="list-group-item tmk">
                                <h4>Tidak Masuk Kantor</h4>
                                <button class="btn btn-success " type="submit" name="dinasluar" value="true"><img
                                        class="gbab" src="/img/dl3.png"><br>Dinas Luar</button>
                                <button class="btn btn-info ml-2" type="submit" name="sakit" value="true"><img
                                        class="gbab" src="/img/sakit2.png"><br>Sakit</button><br><br>
                                <button class="btn btn-success " type="submit" name="izin" value="true"><img
                                        class="gbab" src="/img/izin.jpg"><br>Izin</button>
                                <!-- <button class="btn btn-info ml-2" type="submit" name="cuti" value="true"><img class="gbab"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        src="/img/cuti2.jpg"><br>Cuti</button><br> -->
                                <button class="btn btn-info ml-2" type="button" data-toggle="modal"
                                    data-target="#modalcuti"><img class="gbab" src="/img/cuti2.jpg"><br>Cuti</button><br>

                            </li>
                        </ul>
                    </form>
                    <div class="modal fade" id="modalcuti">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="box-title">Pilih Jenis Pengajuan</h6>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <form action="/absensi/cekAbsen" method="post">
                                        @csrf
                                        <button class="btn btn-success" type="submit" name="cuti" value="true"><i
                                                class="fa fa-upload"></i>Upload Cuti</button>
                                    </form>
                                    {{-- <form action="/absensi/cutibypass" method="post">
                                    @csrf                         
                                        <button class="btn btn-primary" type="submit" name="cuti" value="{{ session()->get(config('global.nama_lain')); }}"><i class="fa fa-edit"></i>Buat Permohonan Cuti</button>
                                    </form>        --}}
                                    <?php
                                    $e_nip = session()->get(config('global.nama_lain'));
                                    $e_tgl = Carbon::now()->toDateString();
                                    $data = [
                                        'nip' => $e_nip,
                                        'tgl' => $e_tgl,
                                    ];
                                    $encryptedData = Crypt::encrypt($data);
                                    ?>
                                    <br>
                                    <a class="btn btn-primary"
                                        href="https://cuti.lampungutarakab.go.id/bypass/{{ $encryptedData }}"><i
                                            class="fa fa-edit"></i> Buat Permohonan Cuti</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left"
                                        data-dismiss="modal">Batal</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item tmk">
                            <a href="/cekSkor/individu"><button class="btn btn-info ml-2" type="submit" name="individu"
                                    value=true><img class="gbab" src="/img/score.png"><br>Skor</button></a><br>
                        </li>
                    </ul>

            </div>
            </center>

        </div>
    </div>
    {{-- notifikasi --}}

    <script>
        Swal.fire({
            text: "Assalamualaikum Wr.Wb, Tabiik pun..",
            //imageUrl: "https://eabsensi-bkpsdm.lampungutarakab.go.id/img/kolase.jpg",//
            imageWidth: 300,
            imageHeight: 280,
            imageAlt: "Custom image",
            html: `<p><b>Ketentuan absen apel pagi :</b></p>
            <ol style="text-align:left">
             <small><li>Absen apel pagi hanya dapat diakses mulai pukul 07.15 s.d 07.45, dan wajib dilakukan dilokasi apel</li></small>
             
             <small><li>Waktu upload absen apel pagi (07.15 s.d 07.45) seluruhnya dianggap tepat waktu </li></small>
            <small><li>Jika telah mengupload absen apel pagi tidak perlu melakukan absen masuk </li></small>
            <small><li>Jika sebelumnya telah mengupload absen masuk, tetap diwajibkan melakukan absen apel pagi sepanjang masih dalam rentang waktu akses </li></small>
             </ol>
             `
        });
    </script>

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

    {{-- notifikasi --}}
    @if (session()->has('gagal'))
        <script>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: '{{ session('gagal') }}',
                showConfirmButton: true
            })
        </script>
    @endif
@endsection
@push('script')
    <script src="/js/filter.js"></script>
    <script>
        $('#menu').on('click', function() {
            $('#submenu').toggle('slow');
        })
        $('#wrap').on('click', function() {
            $('#submenu').hide();
        });
    </script>
@endpush
