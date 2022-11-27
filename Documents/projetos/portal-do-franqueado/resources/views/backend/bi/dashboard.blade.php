@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header">
                    <h3 class="box-title">Filtro</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn btn-flat btn-box-tool p-2">
                            <button class="btn btn-flat btn-default btn-sm"><i class="fa fa-filter"></i> Abrir</button>
                        </a>
                        <a href="{{ url()->current() }}" class="btn btn-flat btn-box-tool p-2">
                            <button class="btn btn-flat btn-default btn-sm"><i class="fa fa-close"></i> Limpar</button>
                        </a>
                    </div>
                </div><!-- /.box-header -->
            </div><!-- /.box -->
        </div>
    </div>
    @include('backend.bi.partials.dashboard.cards')
    @include('backend.bi.partials.dashboard.grafico-cupons-geral')
    @include('backend.bi.partials.dashboard.grafico-cupons-por-loja')
    @include('backend.bi.partials.dashboard.filtro')
@endsection
@section('extra_scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
@endsection