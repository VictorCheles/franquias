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
        input#email
        {
            text-transform: lowercase;
        }
    </style>
@endsection
@section('content')
    <p class="text-center">
        <img src="{{ asset('images/top-secret.png') }}" class="img-responsive" style="margin: 0 auto;">
    </p>
    <hr class="amarelo">
    <h2 class="color-red text-center" style="font-weight: bold;">Ficha de Cadastro</h2>
    <p class="color-red text-center">
        Seja bem vindo ao <b>Programa Cliente Oculto {{ env('APP_NAME') }}</b>
    </p>
    <br>
    <p class="color-red text-center">
        A primeira etapa para que você possa se tornar um avaliador oculto {{ env('APP_NAME') }}<br>
        é preencher o formulário abaixo com todas as suas informaçoẽs.<br>
        Após essa etapa você receberá em seu email, quando chegar a sua vez, as informações para acessar<br>
        o painel de avaliação com o dia e loja de sua visita<br>
    </p>
    <br>
    {!! Form::open(['url' => route('auto.cadastro.post', $token)]) !!}
        <h3 class="text-center color-red">Dados de perfil</h3>
        <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
            {!! Form::label('nome', 'Nome completo') !!}
            {!! Form::text('nome' , '' , ['class' => 'form-control', 'required' => 'required']) !!}
        </div>
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            {!! Form::label('email', 'E-mail') !!}
            {!! Form::text('email' , '' , ['class' => 'form-control', 'required' => 'required', 'id' => 'email']) !!}
        </div>
        <div class="form-group {{ $errors->has('telefone') ? 'has-error' : '' }}">
            {!! Form::label('telefone', 'Telefone') !!}
            {!! Form::text('telefone' , '' , ['class' => 'form-control', 'data-mask' => '(99) 99999-9999', 'required']) !!}
        </div>
        <div class="form-group {{ $errors->has('uf') ? 'has-error' : '' }}">
            {!! Form::label('uf', 'Estado') !!}
            {!! Form::select('uf' , \App\Models\AvaliadorOculto\User::MAPA_UFS ,'' , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2', 'required']) !!}
        </div>
        <div class="form-group {{ $errors->has('cidade') ? 'has-error' : '' }}">
            {!! Form::label('cidade', 'Cidade') !!}
            {!! Form::text('cidade' , '' , ['class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group {{ $errors->has('cpf') ? 'has-error' : '' }}">
            {!! Form::label('cpf', 'CPF') !!}
            {!! Form::text('cpf' , '' , ['class' => 'form-control', 'required' => 'required', 'data-mask' => '999.999.999-99']) !!}
        </div>
        <div class="form-group {{ $errors->has('rg') ? 'has-error' : '' }}">
            {!! Form::label('rg', 'RG') !!}
            {!! Form::text('rg' , '' , ['class' => 'form-control', 'required' => 'required']) !!}
        </div>
        <div class="form-group {{ $errors->has('data_nascimento') ? 'has-error' : '' }}">
            {!! Form::label('data_nascimento', 'Data de nascimento') !!}
            {!! Form::text('data_nascimento' , '' , ['class' => 'form-control', 'required' => 'required', 'data-mask' => '99/99/9999']) !!}
        </div>
        <div class="form-group {{ $errors->has('escolaridade') ? 'has-error' : '' }}">
            {!! Form::label('escolaridade', 'Escolaridade') !!}
            {!! Form::select('escolaridade' , \App\Models\AvaliadorOculto\User::MAPA_ESCOLARIDADE ,'' , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2', 'required' => 'required']) !!}
        </div>
        <button type="submit" class="btn btn-flat btn-success" style="width: 100%;">Finalizar cadastro</button>
    {!! Form::close() !!}
@endsection