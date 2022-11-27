@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Setup
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12 card">
            <a href="{{ route('admin.consultoria-de-campo.setup.formularios.index') }}">
                <div class="overlay laranja"></div>
                <div class="card-image">
                    <img src="{{ asset('images/novo-documento.png') }}" class="img-responsive">
                </div>
                <div class="card-title text-center">
                    Cadastrar formulário
                </div>
                <div class="card-text text-center">
                    <p>
                        Configure as opções<br>
                        de formulário de visita
                    </p>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xs-12 card">
            <a href="{{ route('admin.consultoria-de-campo.setup.notificacoes.index') }}">
                <div class="overlay roxo"></div>
                <div class="card-image">
                    <img src="{{ asset('images/comment.png') }}" class="img-responsive">
                </div>
                <div class="card-title text-center">
                    Cadastrar notificação
                </div>
                <div class="card-text text-center">
                    <p>
                        Configure as opções<br>
                        de notificação
                    </p>
                </div>
            </a>
        </div>
    </div>
@endsection