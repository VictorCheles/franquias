@extends('layouts.app-avaliador-oculto')
@section('extra_styles')
    <style>
        .color-red
        {
            color: #7D1A1D;
        }
        .amarelo
        {
            border: 1px solid #FABA0A;
        }
        p.color-red
        {
            font-size: 20px;
        }
        .color-yellow
        {
            color: #FABA0A;
        }
        .modal-dialog {
            margin: 0;
        }
    </style>

@endsection
@section('content')
    <h2 class="color-red text-center" style="font-weight: bold;">PARABÉNS!</h2>
    <p class="color-red text-center">{{ Auth::guard('avaliador_oculto')->user()->nome }}</p>
    <hr class="amarelo">
    <p class="color-red text-center">
        Você participou do programa <b>cliente oculto</b> da rede {{ env('APP_NAME') }}!
    </p>
    <br>
    <p class="color-red text-center">
        A Avaliação foi realizada na <b class="color-yellow">Loja {{ $loja->nome }}</b> na cidade <b class="color-yellow">{{ $loja->cidade }}/{{ $loja->uf }}</b>,<br>
        Na data <b><i>{{ \Carbon\Carbon::parse($ultimoRespondido->pivot->data_termino)->format('d') }}</i></b> de
        <b><i>{{ ucfirst(\Carbon\Carbon::parse($ultimoRespondido->pivot->data_termino)->formatLocalized('%B')) }}</i></b> de
        <b><i>{{ \Carbon\Carbon::parse($ultimoRespondido->pivot->data_termino)->format('Y') }}</i></b>.
    </p>
    <br>
    <p class="text-center">
        <img src="{{ asset('images/top-secret.png') }}" class="img-responsive" style="margin: 0 auto;">
    </p>
@endsection