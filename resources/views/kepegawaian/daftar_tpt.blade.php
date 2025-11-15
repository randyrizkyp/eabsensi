<option value="|">Tidak Ada</option>
@foreach($subunit as $sub)
<option value="{{ $sub->kode_tpt_lain }}|{{ $sub->tpt_lain }}">{{
    $sub->tpt_lain }}
</option>
@endforeach