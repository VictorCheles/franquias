@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .nav-pills li.active a, .nav-pills li.active a:hover
        {
            border-left-color: #dd4b39;
        }
    </style>
@endsection

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Mensagens Diretas
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">

            @yield('message-action')

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Caixas</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li {!! $titulo == 'Recebidas' ? 'class="active"' : '' !!}>
                            <a href="{{ route('admin.mensagens') }}">
                                <i class="fa fa-inbox"></i>
                                Recebidas
                                <span class="label label-danger pull-right">
                                    {{ Auth()->user()->mensagensRecebidas()->count() }}
                                </span>
                            </a>
                        </li>
                        <li {!! $titulo == 'Enviadas' ? 'class="active"' : '' !!}>
                            <a href="{{ route('admin.mensagens.enviadas') }}">
                                <i class="fa fa-envelope-o"></i>Enviadas
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    @yield('mail-content')
    </div>
@endsection