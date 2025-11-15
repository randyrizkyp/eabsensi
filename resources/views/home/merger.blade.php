{{-- @php
$rapihkan = new App\Http\Controllers\MergerController;
@endphp
@foreach ($data as $d)
@php
$rapihkan->rapihkan($d->nip);
@endphp
@endforeach --}}

@foreach ($data as $d)
{{ $d->nip }}
@endforeach