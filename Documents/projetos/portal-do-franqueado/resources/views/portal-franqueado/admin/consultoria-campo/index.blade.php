@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo
                </h1>
            </div>
        </section>
    </div>
    <div class="row">

        <div class="col-sm-6 col-xs-12 card">
            <a href="{{ route('admin.consultoria-de-campo.visitas.create') }}">
                <div class="overlay laranja"></div>
                <div class="card-image">
                    <img src="{{ asset('images/note-pen.png') }}" class="img-responsive">
                </div>
                <div class="card-title text-center">
                    Cadastro de visitas
                </div>
                <div class="card-text text-center">
                    <p>
                        Cadastre sue formulário aqui<br>
                    </p>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xs-12 card">
            <a href="{{ route('admin.consultoria-de-campo.visitas.index') }}">
                <div class="overlay azul"></div>
                <div class="card-image">
                    <img src="{{ asset('images/line-chart.png') }}" class="img-responsive">
                </div>
                <div class="card-title text-center">
                    Visitas e Relatório
                </div>
                <div class="card-text text-center">
                    <p>
                        Configure as opções<br>
                        de formulário de visita, notificações e multas.
                    </p>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xs-12 card">
            <a href="{{ route('admin.consultoria-de-campo.acoes-corretivas.index') }}">
                <div class="overlay verde"></div>
                <div class="card-image">
                    <img src="{{ asset('images/dashboard.png') }}" class="img-responsive">
                </div>
                <div class="card-title text-center">
                    Painel de ações
                    corretivas
                </div>
                <div class="card-text text-center">
                    <p>
                        Configure as opções<br>
                        de formulário de visita, notificações e multas.
                    </p>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xs-12 card">
            <a href="{{ route('admin.consultoria-de-campo.setup') }}">
                <div class="overlay roxo"></div>
                <div class="card-image">
                    <img src="{{ asset('images/image-cog.png') }}" class="img-responsive">
                </div>
                <div class="card-title text-center">
                    Setup
                </div>
                <div class="card-text text-center">
                    <p>
                        Configure as opções<br>
                        de formulário de visita, notificações e multas.
                    </p>
                </div>
            </a>
        </div>
    </div>
@endsection