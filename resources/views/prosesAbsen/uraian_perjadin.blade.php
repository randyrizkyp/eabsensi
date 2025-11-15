<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/jpg" href="/img/logolampura2.ico">
    <title>Perjalanan Dinas
        {{ config('global.nama_lain')}}
    </title>
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href=" https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">


    <style>
        html,
        body {
            width: 100%;
            height: 100%;

            margin: 0;
            padding: 0;
        }


        .tomb {
            width: 50vw;
            font-size: 5vw;
            vertical-align: middle;
            height: 50px;

        }

        #rowcek {
            display: flex;
            box-sizing: border-box;


        }

        .ketpos {
            margin-left: 12vw;
        }


        .pilket {
            margin-left: 11vw;
            margin-top: 5vw;
            width: 70vw;
            font-size: 5vw;
        }


        #isket {
            display: none;
        }

        #fotspt {

            position: relative;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;


        }

        #loading {
            position: absolute;
            left: 49%;
            bottom: 59%;
            z-index: 999;

        }

        #muatan_gb {}

        .agam {
            font-size: 1.2em;
            /*  margin-top: -13vw;*/
        }

        #tokir {
            display: none;
            justify-content: center;
            z-index: 999;
            margin-top: 10px;



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

</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <section>
                <h3 align="center" id="menu" class="alert alert-success mb-0" style="position: relative"><i
                        class="fas fa-bars float-left"></i>Uraian Perjalanan Dinas
                </h3>
                <div id="submenu" class="pt-4 pr-4 mt-0"
                    style="min-width: 300px; position: absolute; z-index:999 ; display: none">
                    <ul style=" list-style-type: none;">
                        <li class="mb-2 pr-4"><a href="/absensi/pilihKeterangan" class="text-white"><i
                                    class="fas fa-list-alt"></i>&emsp;Pilih Keterangan</a>
                        </li>
                        <li class="mb-2 pr-4"><a href="/logout" class="text-white"><i
                                    class="fas fa-sign-out-alt"></i>&emsp;Log
                                Out</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </div>
    <section id="muatan">
        <form action="/absensi/dinasLuar" method="post">
            @csrf

            <div class="row ml-2 mt-3 justify-content-center" id="wrap">
                <div class="col-11 form-group">
                    <label for="jenisdl">Jenis Dinas Luar :</label>
                    <select class="form-control" id="jenisdl" name="jenisdl" required>
                        <option value="">== pilih jenis DL ==</option>
                        <option value="Dalam Daerah">Dalam Daerah</option>
                        <option value="Luar Daerah">Luar Daerah</option>
                    </select>
                </div>
            </div>

            <div class="row justify-content-center ml-2">
                <div class="col-11 justify-content-center" form-group">
                    <label for="tujuan">Tujuan / lokasi :</label>
                    <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                </div>
            </div>

            <div class="row justify-content-center ml-2">

                <div class="col-11 form-group">
                    <label for="maksud">Dalam rangka :</label>
                    <textarea class="form-control" id="maksud" name="maksud" rows="3" required></textarea>
                </div>
            </div>

            <div class="row justify-content-center ml-2">
                <div class="col-11">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalturut">
                        Pilih Pengikut/Turut serta
                    </button>
                    <span class="jumkut ml-2"></span>
                </div>
            </div>
            <div class="row ml-2">
                <div class="col-11 mt-1 daftar_pengikut">

                </div>
                <input type="hidden" class="inputpengikut" name="inputpengikut" value="">
            </div>
            <!-- Modal -->
            <div class="modal fade" id="modalturut" data-backdrop="static" data-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header alert alert-info">
                            <h5 class="modal-title " id="staticBackdropLabel">Pilih Pengikut / Turut
                                Serta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <table width="100%" class="table table-striped table-borderd" id="tb_pegawai">

                                <thead class="table-dark">
                                    <tr>
                                        <td>#</td>
                                        <td>NAMA / PANGKAT</td>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daftarPegawai->where('nip', '!=', $nip)->where('pangkat','!=','Non-PNS')->sortBy('nama') as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" name="pengikut"
                                                    value="{{ $data->nip }}={{ $data->nama }}"
                                                    id="peng_{{ $data->id  }}">
                                                <label class="form-check-label nmpengikut" for="peng_{{ $data->id }}">
                                                    {{ $data->nama }} / <span style="font-size: 9px;">
                                                        {{ $data->pangkat }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-primary text-white">
                                        <td>#</td>
                                        <td>NAMA / PANGKAT</td>
                                    </tr>

                                    @foreach ($daftarPegawai->where('nip', '!=', $nip)->where('pangkat','Non-PNS')->sortBy('nama') as $danon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="form-group form-check">
                                                <input type="checkbox" class="form-check-input" name="pengikut"
                                                    value="{{ $danon->nip }}={{ $danon->nama }}"
                                                    id="peng_{{ $danon->id  }}">
                                                <label class="form-check-label nmpengikut" for="peng_{{ $danon->id }}">
                                                    {{ $danon->nama }} / <span style="font-size: 9px;">
                                                        {{ $danon->pangkat }}
                                                    </span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>








                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary pilihpengikut"
                                data-dismiss="modal">Pilih</button>
                        </div>

                    </div>
                </div>
            </div>

            <center>
                <button class="btn btn-info mt-4" type="submit" name="uraian_dl">SUBMIT</button>
            </center>

        </form>
    </section>













    <br><br>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src=" https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#menu').on('click', function(){
            $('#submenu').toggle('slow');
        })
        $('#wrap').on('click', function(){
            $('#submenu').hide();
        });
        
    </script>
    <script type="text/javascript">
        $('.pilihpengikut').on('click', function(){

      var checkbox = $("input[name='pengikut']");
      var nmpengikut = '';
      var pengikut = '';
      var jum = 0;
      for (var i = 0; i < checkbox.length; i++) {
        
        
        if (checkbox[i].checked) {
          var data = checkbox[i].value;
              data = data.split('=');
           
          pengikut = pengikut + data[0] + '|' ;
          nmpengikut = nmpengikut + data[1] + ' | ';
          jum = jum + 1;

                

        }



      }


       $('.daftar_pengikut').html(nmpengikut);
       $('.inputpengikut').val(pengikut);
       $('.jumkut').text(jum + ' orang');
 
   });

   $('#tb_pegawai').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "responsive": true,
    });

 


    </script>

</body>

</html>