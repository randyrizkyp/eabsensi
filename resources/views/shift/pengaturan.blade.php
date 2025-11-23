@extends('templatesLTE.main')

@section('content')


{{-- CSS --}}
<style>
    table {
        border-collapse: collapse;
        width: max-content;
        min-width: 100%;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 6px;
        text-align: center;
        white-space: nowrap;
    }

    .freeze {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 10;
        font-weight: bold;
    }

    thead th {
        position: sticky;
        top: 0;
        background: #f1f1f1;
        z-index: 20;
    }

    .scroll-container {
        width: 100%;
        overflow: auto;
        max-height: 500px;
        border: 1px solid #ccc;
    }

    .edit-input {
        width: 50px;
        text-align: center;
    }
</style>


    {{-- Notifikasi Success --}}
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

    {{-- Notifikasi Error --}}
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

    <div class="row">
        <div class="col-md-12 p-0 mx-0">
            <div class="card-body bg-white">

                <div class="alert alert-warning mb-2 text-center">
                    <h3>DAFTAR ASN, TAHUN {{ $tahun }}</h3>
                </div>

                <div class="d-flex">
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
                        <button type="submit" class="btn btn-sm btn-danger mt-2 mb-4 ml-2">
                            <img src="/img/pdf.png" width="18px"> &ensp; Export PDF
                        </button>
                    </form>
                </div>

                <form action="" method="GET" class="mb-3">

                <div class="d-flex">

                    {{-- PILIH BULAN --}}
                    <select name="bulan" class="form-control mr-2" style="width:150px;">
                        @php
                            $namaBulan = [
                                1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                                5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                                9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                            ];
                        @endphp

                        @foreach($namaBulan as $key => $val)
                            <option value="{{ $key }}" {{ $key == $bulan ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>

                    {{-- PILIH TAHUN --}}
                    <select name="tahun" class="form-control mr-2" style="width:120px;">
                        @for($t = date('Y') - 3; $t <= date('Y') + 1; $t++)
                            <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>

                    <button class="btn btn-primary">Tampilkan</button>

                </div>

            </form>
            
            @foreach($ruangan as $ruang)

<div class="alert alert-warning mb-1 text-center">
    <h5>{{ $ruang->tpt_lain }}</h5>
</div>

<div class="scroll-container">
    <table class="shiftTable">
        <thead>
            <tr>
                <th class="freeze">Nama</th>
                @for($i = 1; $i <= $jumlahHari; $i++)
                    <th>{{ $i }}</th>
                @endfor
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($nama as $row)
                @php
                    $dataShift = $shiftData[$row->nip]->tanggal ?? "";
                    $arrShift = str_split($dataShift);
                @endphp

                @if($row->kode_tpt_lain == $ruang->kode_tpt_lain)
                    <tr>
                        <td class="freeze">{{ $row->nama }} / {{ $row->nip }}</td>

                        @for($i = 0; $i < $jumlahHari; $i++)
                            @php $isi = $arrShift[$i] ?? ""; @endphp
                            <td>
                                <span class="text">{{ $isi }}</span>
                                <input class="edit-input" style="display:none" value="{{ $isi }}">
                            </td>
                        @endfor

                        <td>
                            <button class="btn-edit btn btn-sm btn-warning">Edit</button>
                            <button class="btn-save btn btn-sm btn-success" style="display:none">Save</button>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>

        {{-- ===================== --}}
        {{--       TFOOT TOTAL     --}}
        {{-- ===================== --}}
        <tfoot>
            {{-- TOTAL SHIFT 1 --}}
            <tr>
                <th cclass="freeze">Total Shift 1</th>
                @for($i = 0; $i < $jumlahHari; $i++)
                    @php
                        $total = 0;
                        foreach($nama as $row){
                            if($row->kode_tpt_lain == $ruang->kode_tpt_lain){
                                $val = $shiftData[$row->nip]->tanggal[$i] ?? "";
                                if($val == "1") $total++;
                            }
                        }
                    @endphp
                    <th>{{ $total }}</th>
                @endfor
                <th></th>
            </tr>

            {{-- TOTAL SHIFT 2 --}}
            <tr>
                <th cclass="freeze">Total Shift 2</th>
                @for($i = 0; $i < $jumlahHari; $i++)
                    @php
                        $total = 0;
                        foreach($nama as $row){
                            if($row->kode_tpt_lain == $ruang->kode_tpt_lain){
                                $val = $shiftData[$row->nip]->tanggal[$i] ?? "";
                                if($val == "2") $total++;
                            }
                        }
                    @endphp
                    <th>{{ $total }}</th>
                @endfor
                <th></th>
            </tr>

            {{-- TOTAL SHIFT 3 --}}
            <tr>
                <th class="freeze" >Total Shift 3</th>
                @for($i = 0; $i < $jumlahHari; $i++)
                    @php
                        $total = 0;
                        foreach($nama as $row){
                            if($row->kode_tpt_lain == $ruang->kode_tpt_lain){
                                $val = $shiftData[$row->nip]->tanggal[$i] ?? "";
                                if($val == "3") $total++;
                            }
                        }
                    @endphp
                    <th>{{ $total }}</th>
                @endfor
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

@endforeach
        </div>
    </div>

@endsection


@push('scripts')
{{-- jQuery (kalau template sudah punya, ini bisa dihapus) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Hanya boleh 1,2,3,L,C dan otomatis uppercase
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('edit-input')) {

        let val = e.target.value.toUpperCase();      // otomatis BESAR
        let allowed = ['1','2','3','L','C'];         // daftar yg boleh

        // Jika input lebih dari 1 karakter → ambil 1 karakter pertama
        if (val.length > 1) {
            val = val.charAt(0);
        }

        // Jika karakter tidak valid → kosongkan
        if (!allowed.includes(val)) {
            val = "";
        }

        e.target.value = val;
    }
});
</script>
<script>
$(document).ready(function() {

    // Tombol EDIT
    $('.btn-edit').click(function() {
        let row = $(this).closest('tr');

        row.find('.text').hide();
        row.find('.edit-input').show();

        $(this).hide();
        row.find('.btn-save').show();
    });

    // Tombol SAVE
    // Tombol SAVE
    // Tombol SAVE
    $('.btn-save').click(function () {

        let row = $(this).closest('tr');

        // --- AMBIL NAMA & NIP ---
        let namaNip = row.find('.freeze').text().trim();
        let parts   = namaNip.split(" / ");

        let nama = parts[0] ?? "";
        let nip  = parts[1] ?? "";

        // --- VALIDASI REQUIRED ---
        let valid = true;
        let hasilTanggal = "";

        row.find('td').each(function () {
            let input = $(this).find('.edit-input');

            if (input.length) {

                // 🎯 Jika kosong → tidak valid
                if (input.val().trim() === "") {
                    valid = false;
                    input.addClass('border border-danger'); // Tandai merah
                } else {
                    input.removeClass('border border-danger');
                }

                hasilTanggal += input.val();
            }
        });

        // ❌ Jika ada yang kosong → tampilkan alert dan stop
        if (!valid) {
            Swal.fire({
                icon: "error",
                title: "Semua kolom wajib diisi!",
                text: "Tidak boleh ada hari yang kosong.",
            });
            return; // STOP proses save
        }

        console.log("NAMA:", nama);
        console.log("NIP:", nip);
        console.log("TANGGAL:", hasilTanggal);

        // --- KEMBALIKAN TAMPILAN TEKS ---
        row.find('.text').each(function (i) {
            $(this).text( row.find('td').eq(i+1).find('.edit-input').val() );
        });

        row.find('.text').show();
        row.find('.edit-input').hide();

        row.find('.btn-save').hide();
        row.find('.btn-edit').show();

        // --- KIRIM KE SERVER ---
        $.ajax({
            url: "/shift/update",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                nama: nama,
                nip: nip,
                tanggal: hasilTanggal
            },
            success: function (res) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil disimpan",
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        });

    });

});
</script>
@endpush