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
            font-size: 15px;
        }
        .color-yellow
        {
            color: #FABA0A;
        }
    </style>
@endsection
@section('content')
    <p class="text-center">
        <img src="{{ asset('images/top-secret.png') }}" class="img-responsive" style="margin: 0 auto;">
    </p>
    <hr class="amarelo">
    <h2 class="color-red text-center" style="font-weight: bold;">Cadastro Finalizado</h2>
    <br>
    <p class="color-red text-center">
        Seu cadastro foi finalizado com sucesso!<br>
        Aguarde pelas próximas instruções!
    </p>
    <br>
@endsection