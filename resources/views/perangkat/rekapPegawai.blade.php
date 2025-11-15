@extends('templates.main')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<!-- start accordion -->
<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel">
        <a class="panel-heading" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion"
            href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <h4 class="panel-title">Sekretariat/Dinas/Badan</h4>
        </a>
        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
                <div class="row ml-2">
                    <div class="col-md-12">
                        @foreach($pds as $pd)
                        <a href="/rekapPegawai?opd={{ $pd->kode_pd }}" class="btn btn-secondary btn-sm text-white">{{
                            $loop->iteration .". ".
                            ucwords(strtolower($pd->nama_lain))
                            }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel">
        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion"
            href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            <h4 class="panel-title">Collapsible Group Items #2</h4>
        </a>
        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                <p><strong>Collapsible Item 2 data</strong>
                </p>
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf
                moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                Brunch 3 wolf moon tempor,
            </div>
        </div>
    </div>
    <div class="panel">
        <a class="panel-heading collapsed" role="tab" id="headingThree" data-toggle="collapse" data-parent="#accordion"
            href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            <h4 class="panel-title">Collapsible Group Items #3</h4>
        </a>
        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
            <div class="panel-body">
                <p><strong>Collapsible Item 3 data</strong>
                </p>
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf
                moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                Brunch 3 wolf moon tempor
            </div>
        </div>
    </div>
</div>
<!-- end of accordion -->
@endsection
@push('script')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

@endpush