<div class="form-group row">
    <label for="nama" class="col-sm-3 col-form-label">Nama</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="nama" name="nama" value="{{ $asn->nama }}" readonly>
    </div>
</div>
<div class="form-group row">
    <label for="nip" class="col-sm-3 col-form-label">NIP</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="nip" name="nip" value="{{ $asn->nip }}" readonly>
    </div>
</div>
<div class="form-group row">
    <label for="tmt_absen" class="col-sm-3 col-form-label">TMT_Absen</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="tmt_absen" name="tmt_absen" value="{{ $asn->tmt_absen }}" readonly>
    </div>
</div>

<div class="form-group row">
    <label for="tmt_mutasi" class="col-sm-3 col-form-label">TMT_Mutasi</label>
    <div class="col-sm-8">
        <input type="text" class="form-control tmt_mutasi" name="tmt_mutasi" autocomplete="off"
            placeholder="isi sesuai tgl SK Mutasi/Pensiun/Lainnya" required>

    </div>
</div>

<div class="form-group row">
    <label for="akhir_absen" class="col-sm-3 col-form-label">Terakhir_Absen</label>
    <div class="col-sm-8">
        <input type="text" class="form-control akhir_absen" name="akhir_absen" autocomplete="off"
            value="{{ now()->translatedFormat('d-m-Y') }}" readonly>
    </div>
</div>

<div class="form-group row">
    <label for="jenmut" class="col-sm-3 col-form-label">Jenis Mutasi</label>
    <div class="col-sm-8">
        <select class="custom-select seljen" name="jenmut" required>
            <option value="">== Pilih Jenis Mutasi ==</option>
            <option value="Pindah Luar Kabupaten">Pindah Luar Kabupaten</option>
            <option value="Pindah OPD">Pindah OPD</option>
            <option value="Pensiun">Pensiun</option>
            <option value="Berhenti">Berhenti</option>
            <option value="Tugas Belajar">Tugas Belajar</option>
        </select>
    </div>
</div>

<div class="form-group row pindahke" style="display: none;">
    <label for="pindahke" class="col-sm-3 col-form-label">OPD Tujuan</label>
    <div class="col-sm-8">
        <select class="custom-select" id="pindahkenya" name="pindahke">
            <option value="" readonly>== pilih OPD ==</option>
            @foreach ($dataOpd as $opd)
            <option value="{{ $opd->nama_lain }}|{{ $opd->kode_pd }}">{{ $opd->nama_pd }}</option>
            @endforeach
        </select>
    </div>
</div>
<script>
    $('.tmt_mutasi').datepicker({
                  format: 'dd-mm-yyyy',
                  autoclose: true,
                  todayHighlight: true,
    });

    $('.seljen').on('change', function(){
        var jen = $(this).val();
        if(jen == 'Pindah OPD'){
            $('.pindahke').show();
        }else{
            $('.pindahke').hide();
        }
    });
</script>