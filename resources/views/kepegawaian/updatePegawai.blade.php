<input type="hidden" name="id" value="{{ $asn->id }}">
<div class="form-row">
    <div class="col ">
        <div class="form-group row">
            <label for="nama" class="col-sm-3 col-form-label text-right">Nama</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $asn->nama }}"
                    required>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="nip" class="col-sm-3 col-form-label text-right">NIP</label>
            <div class="col-sm-9">
                <input type="text" minlength="18" maxlength="18" onkeypress="return hanyaAngka (event)"
                    class="form-control" name="nip" value="{{ $asn->nip }}" readonly>
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
                    <option {{ $asn->jenis_asn == 'PNS' ? 'selected' : '' }}>PNS</option>
                    <option {{ $asn->jenis_asn == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="form-row">

    <div class="col">
        <div class="form-group row">
            <label for="pangkat" class="col-sm-3 col-form-label text-right">Pangkat/Gol</label>
            <div class="col-sm-9">
                <select class="custom-select" id="pangkat" name="pangkat" required>
                    <option value="" disabled>--pilih--</option>
                    <option selected>{{ $asn->pangkat }}</option>
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
                <input type="hidden" name="kode_pd" value="{{ config('global.kode_pd') }}">
                <input type="text" class="form-control" name="uker" value="{{ config('global.nama_pd') }}"
                    readonly>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="uorg" class="col-sm-3 col-form-label text-right">Unit
                Org</label>
            <div class="col-sm-9">
                <select class="custom-select uorg" name="uorg">
                    @foreach ($unors as $unor)
                        <option
                            value="{{ $unor->kode_unit }}|{{ $unor->unit_organisasi }}|{{ $unor->lat }}|{{ $unor->lot }}"
                            {{ $asn->kode_unit == $unor->kode_unit ? 'selected' : '' }}>
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
            <label for="jabatan" class="col-sm-3 col-form-label text-right ">Jabatan</label>
            <div class="col-sm-9">
                <select class="selectpicker"
                        id="jabatan"
                        name="jabatan"
                        data-live-search="true"
                        data-style="custom-select"
                        required>
                    <option value="" disabled {{ $asn->jabatan ? '' : 'selected' }}>-- pilih --</option>
                    @foreach ($pokjab as $jabt)
                        <option value="{{ $jabt->nama_jabatan }}"
                            {{ $asn->jabatan == $jabt->nama_jabatan ? 'selected' : '' }}>
                            {{ $jabt->nama_jabatan }}
                        </option>
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
                    <option {{ $asn->jenis_jbt == 'Struktural' ? 'selected' : '' }}>Struktural
                    </option>
                    <option {{ $asn->jenis_jbt == 'Fungsional Khusus' ? 'selected' : '' }}>Fungsional Khusus
                    </option>
                    <option {{ $asn->jenis_jbt == 'Fungsional Umum' ? 'selected' : '' }}>
                        Fungsional Umum</option>

                </select>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="status_jbt" class="col-sm-3 col-form-label text-right">Status
                Jbt</label>
            <div class="col-sm-9">
                <select class="custom-select" id="status_jbt" name="status_jbt" required>
                    <option value="Def" {{ $asn->status_jbt == 'Def' ? 'selected' : '' }}>Definitif</option>
                    <option value="Plt" {{ $asn->status_jbt == 'Plt' ? 'selected' : '' }}>Pelaksana Tugas (Plt)
                    </option>
                    <option value="Plh" {{ $asn->status_jbt == 'Plh' ? 'selected' : '' }}>Pelaksana Harian (Plh)
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-row rowSub">
    <div class="col">
        <div class="form-group row">
            <label for="tpt_lain" class="col-sm-3 col-form-label text-right">Sub
                Unit</label>
            <div class="col-sm-9">
                <select class="custom-select tpt_lain" name="tpt_lain">
                    <option value="|">Tidak Ada</option>

                    @foreach ($subunit->where('kode_unit', $asn->kode_unit) as $sub)
                        <option value="{{ $sub->kode_tpt_lain }}|{{ $sub->tpt_lain }}"
                            {{ $asn->kode_tpt_lain == $sub->kode_tpt_lain ? 'selected' : '' }}>
                            {{ $sub->tpt_lain }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="opd_lain"
                class="col-sm-3 col-form-label text-right"><small><b>Plt.OPD_Lain</b></small></label>
            <div class="col-sm-9">
                <select class="custom-select" id="opd_lain" name="opd_lain">
                    <option value="|">Tidak Ada</option>
                    @foreach ($opdlain as $opd)
                        <option value="{{ $opd->kode_pd }}|{{ $opd->nama_lain }}"
                            {{ $asn->opd_lain == $opd->nama_lain ? 'selected' : '' }}>
                            {{ $opd->nama_lain }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="tmt_absen" class="col-sm-3 col-form-label text-right">TMT_Absen</label>
            <div class="col-sm-9">
                <input type="text" class="form-control edtmt_absen" name="tmt_absen" autocomplete="off"
                    value="{{ $asn->tmt_absen }}" required>
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
                    <option {{ $asn->jenkel == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option {{ $asn->jenkel == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="form-group row">
            <label for="tpp" class="col-sm-3 col-form-label text-right">TPP</label>
            <div class="col-sm-9">
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input type="text" class="form-control tpp" name="tpp" value="{{ $asn->tpp }}"
                        required>
                </div>

            </div>
        </div>

    </div>

    <div class="col">
        <div class="form-group row">
            <label for="norut" class="col-sm-3 col-form-label text-right">No
                Urut</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="norut" name="norut"
                    value="{{ $asn->norut }}" required>

            </div>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col">
        <div class="form-group row">
            <label for="reset" class="col-sm-6 col-form-label text-right">Reset Password</label>
            <div class="col-sm-4 d-flex justify-content-left">
                <input class="ml-1" type="checkbox" name="resetpass" value="1"
                    {{ $resetpass == 'true' ? 'checked' : '' }}>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="foto" class="col-sm-3 col-form-label text-right">foto</label>
            <div class="col-sm-9">
                <input type="file" class="form-control edfoto_pegawai" name="foto_pegawai">
                <input type="hidden" name="old_foto" value="{{ $asn->foto }}">
                @if ($asn->foto)
                    <img class="img-fluid mt-2 edgb" src="{{ asset('storage/foto_pegawai/' . $asn->foto) }}"
                        width="150px">
                @else
                    <img class="mt-2 float-right edgb" src="../img/foto_pegawai/no_image.png" width="150">
                @endif
            </div>

        </div>
    </div>
    <div class="col">
        <div class="form-group row">
            <label for="shift"
                class="col-sm-3 col-form-label text-right">Shift</label>
            <div class="col-sm-9">               
                <select class="custom-select" id="shift" name="shift" required>
                    <option value="" selected disabled>--pilih--</option>
                    <option {{ $asn->status == 'aktif' ? 'selected' : '' }}>Regular</option>
                    <option {{ $asn->status == 'Shift' ? 'selected' : '' }}>Shift</option>
                </select>
            </div>
        </div>
    </div>

</div>
<script>
    $('.tpp').mask('000.000.000', {
        reverse: true
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


        }




    }

    $('.edtmt_absen').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
    });

</script>

<script>
$(function() {
    $('.selectpicker').selectpicker();
});

</script>
