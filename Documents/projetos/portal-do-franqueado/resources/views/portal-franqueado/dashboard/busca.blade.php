@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Busca geral
                </h1>
            </div>
        </section>
    </div>
    <div class="row" style="margin-bottom: 10px;">
        <form method="get" action="{{ url()->current() }}">
            <div class="col-sm-10 col-xs-12">
                <input class="form-control typehead-field" autocomplete="false" placeholder="Faça uma busca primeiro" name="q" value="{{ Request::get('q') }}">
            </div>
            <div class="col-sm-2 col-xs-12">
                <input type="submit" class="btn btn-flat btn-info" value="Buscar">
            </div>
        </form>
    </div>
    @if($lista)
        <div class="row" style="padding-bottom: 10px;">
            <div class="col-xs-12">
                A busca retornou {{ $lista->count() }} registro{{ $lista->count() == 1 ? '' : 's' }}
            </div>
        </div>
        @foreach($lista as $item)
            <div class="row" style="padding-bottom: 10px;">
                <div class="col-xs-2">
                    {!! \App\Http\Controllers\Franquias\DashboardController::buscaThumb($item) !!}
                </div>
                <div class="col-xs-10">
                    <h4><a href="{{ \App\Http\Controllers\Franquias\DashboardController::linkBusca($item) }}">{{ \App\Http\Controllers\Franquias\DashboardController::tipoFormatado($item) }} - {{ $item->titulo }}</a></h4>
                    <div><a style="color: #006621;" href="{{ \App\Http\Controllers\Franquias\DashboardController::linkBusca($item) }}">{{ \App\Http\Controllers\Franquias\DashboardController::linkBusca($item) }}</a></div>
                    <div style="font-size: 12px;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</div>
                    <div>{!! str_limit(strip_tags($item->descricao), 200) !!}</div>
                </div>
            </div>
        @endforeach
    @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            var data = ['Arquivos', 'Comunicados', 'Vídeos', 'Solicitações'];
            $(".typehead-field").typeahead({
                source: data,
                showHintOnFocus: true
            });
        });
    </script>
@endsection