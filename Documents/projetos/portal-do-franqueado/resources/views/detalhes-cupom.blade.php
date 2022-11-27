@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <div class="box box-danger box-solid box-promocao no-border">
                <div class="box-body no-padding">
                    <div class="box-saia-sup"></div>
                    <img src="/uploads/{{ $promocao->imagem }}" class="img-responsive">
                </div>
                <div class="box-header text-center">
                    <h2>{{ $promocao->nome }}</h2>
                </div>
                <div class="box-footer box-saia"></div>
                <div class="box-body">
                    @if($cupom->status() == \App\Models\Cupom::STATUS_VALIDO)
                        <p class="text-muted well well-sm no-shadow text-center hidden-xs cupom-code label-danger">
                            {{ $cupom->code }}
                        </p>
                    @else
                        <span class="label label-danger hidden-xs">Este cupom já foi utilizado</span>
                        <p class="text-muted well well-sm no-shadow text-center hidden-xs" style="margin-top: 10px; font-family: monospace; font-size: 55px;">
                            <i style="text-decoration: line-through;">{{ $cupom->code }}</i>
                        </p>
                    @endif

                    @if($cupom->status() == \App\Models\Cupom::STATUS_VALIDO)
                        <p class="text-muted well well-sm no-shadow text-center visible-xs label-danger" style="margin-top: 10px; font-family: monospace; font-size: 30px;">
                            {{ $cupom->code }}
                        </p>
                    @else
                        <span class="label label-danger visible-xs">Este cupom já foi utilizado</span>
                        <p class="text-muted well well-sm no-shadow text-center visible-xs" style="margin-top: 10px; font-family: monospace; font-size: 30px;">
                            <i style="text-decoration: line-through;">{{ $cupom->code }}</i>
                        </p>
                    @endif
                </div>
                <div class="box-body">
                    <b>Descrição:</b>
                    {!! $promocao->descricao !!}
                </div>
                <div class="box-footer bg-redi">
                    <b>Regulamento:</b>
                    <br>
                    <b>{{ $promocao->texto_validade }}</b>
                    {!! $promocao->regulamento !!}
                </div>
            </div><!-- /.widget-user -->
        </div>
    </div>
@endsection