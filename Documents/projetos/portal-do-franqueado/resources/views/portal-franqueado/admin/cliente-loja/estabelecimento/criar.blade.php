@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Novo Estabelecimento
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário de Estabelecimentos</h3>
                </div>
                {!! Form::open(['url' => route('clientes_loja_estabelecimento.store')]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome do estabelecimento') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary']) !!}
                    {!! link_to(route('clientes_loja_estabelecimento.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection