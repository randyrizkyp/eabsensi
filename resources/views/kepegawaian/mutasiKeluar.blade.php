<?php 
use App\Http\Controllers\PegawaiController;
$cek = new PegawaiController;
?>
@extends('templatesLTE.main')
@section('content')
{{-- notifikasi --}}
@if(session()->has('success'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'success',
   title: '{{ session("success") }}',
   showConfirmButton: false,
   timer : 1000
})
</script>

@endif

{{-- notifikasi --}}
@if(session()->has('fail'))
<script>
    Swal.fire({
   position: 'center',
   icon: 'error',
   title: '{{ session("fail") }}',
   showConfirmButton: true
})
</script>

@endif
{{-- End Notifikasi --}}

<div class="row">
    <div class="col-md-12 p-0 mx-0">
        <!-- Card Body -->
        <div class="card-body bg-white">
            <div class="alert alert-success mb-2 text-center">
                <h3>DAFTAR PEGAWAI MUTASI KELUAR </h3>
            </div>

            <table class="table table-bordered" style="font-size: .8rem;" id="mutasiKeluar">
                <thead style="background-color: darkseagreen">
                    <tr>
                        <th>No</th>
                        <th>Nama/NIP</th>
                        <th>Pangkat/Gol</th>
                        <th>Jabatan</th>
                        <th>Unit Organisasi</th>
                        <th>
                            <center>Foto</center>
                        </th>
                        <th>Jenis Mutasi</th>
                        <th>TMT_Mutasi</th>
                        <th>Akhir_Absen</th>

                        <th>Mutasi_ke</th>
                        <th>Respon</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $dt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dt->nama }} <br>NIP.{{ $dt->nip }}</td>
                        <td>{{ $dt->pangkat }}</td>
                        <td>{{ $dt->jabatan }}</td>
                        <td>{{ $dt->unit_organisasi }}</td>
                        <td>
                            <img src="/storage/foto_pegawai/{{ $dt->foto }}" width="50px">
                        </td>
                        <td>{{ $dt->jenis_mutasi }}</td>
                        <td>{{ $dt->tmt_mutasi }}</td>
                        <td>{{ $dt->akhir_absen }}</td>
                        <td>{{ $dt->pindah_ke }}</td>
                        <td>
                            {{ $cek->cekMutasi($dt->nip, $dt->tmt_mutasi) }}
                        </td>

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
    function showFoto(foto){
        var path = '/storage/foto_pegawai/'+foto;
                               
        Swal.fire({
          imageUrl: path,
          imageWidth: 480,
          imageHeight: 540,
          imageAlt: 'Custom image',
        })
    }

    $('#mutasiKeluar').DataTable();
</script>
@endpush