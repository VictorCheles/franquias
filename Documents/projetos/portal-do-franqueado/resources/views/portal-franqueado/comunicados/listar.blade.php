@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .list-group {border-radius: 0;}
        .list-group .list-group-item {background-color: transparent;overflow: hidden;border: 0;border-radius: 0;padding: 0 8px;}
        .list-group .list-group-item .row-picture,
        .list-group .list-group-item .row-action-primary {float: left;display: inline-block;padding-right: 16px;padding-top: 8px;}
        .list-group .list-group-item .row-picture label,
        .list-group .list-group-item .row-action-primary label {display: block;width: 56px;height: 56px;}
        .list-group .list-group-item .row-picture img,
        .list-group .list-group-item .row-action-primary img {padding: 1px;}
        .list-group .list-group-item .row-action-primary i {background: rgba(0, 0, 0, 0.25);border-radius: 100%;text-align: center;line-height: 56px;font-size: 20px;color: white;}
        .list-group .list-group-item .row-picture label,
        .list-group .list-group-item .row-action-primary label {margin-left: 7px;margin-right: -7px;margin-top: 5px;margin-bottom: -5px;}
        .list-group .list-group-item .row-content {display: inline-block;width: calc(100% - 92px);min-height: 66px; margin-top: 13px}
        .list-group .list-group-item .row-content .action-secondary {position: absolute;right: 16px;top: 16px;}
        .list-group .list-group-item .row-content .action-secondary i {font-size: 20px;color: rgba(0, 0, 0, 0.25);cursor: pointer;}
        .list-group .list-group-item .row-content .least-content {position: absolute;right: 16px;top: 0px;color: rgba(0, 0, 0, 0.54);font-size: 14px;}
        .list-group .list-group-item .list-group-item-heading {color: rgba(0, 0, 0, 0.77);font-size: 18px;line-height: 25px;}
        .list-group .list-group-separator {clear: both;overflow: hidden;margin-top: 10px;margin-bottom: 10px;}
        .list-group .list-group-separator:before {content: "";width: calc(100% - 90px);border-bottom: 1px solid rgba(0, 0, 0, 0.1);float: right;}

        .bg-bottom{height: 100px;margin-left: 30px;}
        .row-float{margin-top: -40px;}
        .img-box{box-shadow: 0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);   }
    </style>

@endsection

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Comunicados
                </h1>
                <h1 class="section-title caticon sbx pull-right">
                    {!! Form::open(['method' => 'get']) !!}
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="#" class="open-modal-filter btn btn-theme-padrao btn-min-block">Buscar</a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ url()->current() }}" class="btn btn-theme2 btn-min-block">Limpar filtro</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </h1>
            </div>
        </section>
    </div>
    @foreach($lista as $item)
        <?php $comunicado = $item; ?>
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <a href="{{ url('/comunicados/ler', $comunicado->id) }}">
                    <img src="{{ $item->img }}" class="img-responsive img-box img-thumbnail">
                </a>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <h4><a href="{{ url('/comunicados/ler', $comunicado->id) }}">{{ $item->titulo }}</a></h4>
                <p>
                    {{ $item->descricao_resumida }}
                </p>
            </div>
            <div class="col-xs-12 col-sm-8 col-sm-offset-4">
                <div class="list-group row">
                    <div class="list-group-item col-xs-12 col-sm-9">
                        <div class="row-picture">
                            <a href="{{ url()->current() . '?' . http_build_query(['filter' => ['setor_id' => $item->setor_id]]) }}">
                                <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/brand.png') }}" style="width: 55px; height: 55px; border:none; ">
                            </a>
                        </div>
                        <div class="row-content">
                            <div class="list-group-item-heading">
                                <a href="{{ url()->current() . '?' . http_build_query(['filter' => ['setor_id' => $item->setor_id]]) }}">
                                    <small>{{ $item->setor->nome }}</small>
                                </a>
                            </div>
                            <small>
                                <i class="glyphicon glyphicon-time"></i> Data - {{ $item->created_at->format('d/m \- H:i') }}
                                <br>
                            </small>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3" style="padding-top: 25px;">
                        <a class="btn btn-theme btn-sm btn-min-block btn-primary" href="{{ url('/comunicados/ler', $comunicado->id) }}">Ler comunicado <span class="glyphicon glyphicon-chevron-right"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    @endforeach
    <div class="center pagination-black">
        {{ $lista->appends(Request::all())->links() }}
    </div>
    @include('portal-franqueado.admin.setor.modals.filtro')
@endsection