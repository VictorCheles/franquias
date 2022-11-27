@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Dashboard
                </h1>
            </div>
        </section>
    </div>

    @section('extra_styles')
    @parent
    <style>
        .shablaw {
            margin-bottom: 10px;
        }
        .shablaw .btn {
            width: 100%;
        }

        .shablaw .col-xs-12:first-child
        {
            padding-left: 1;
        }

        .shablaw .col-xs-12:last-child
        {
            padding-right: 0;
        }
    </style>
@endsection

<div class="row">
        <div class="col-xs-12 btn-group">
            <a href="#" class="btn btn-default btn-flat pull-right open-modal-filter"><i class="fa fa-filter"></i> Abrir Filtro</a>
            <a href="{{ url()->current() }}" class="btn btn-default btn-flat pull-right"><i class="fa fa-times"></i> Limpar Filtro</a>
        </div>
    </div>
    <br>
    <div class="row">        
     <div class="col-lg-3 col-xs-6 card-action" style="cursor: pointer;" data-target="#lojas-pendentes">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-home"></i></span>

                <div class="info-box-content">
                <span class="info-box-number"><h4><b>{{ $lojas_avaliacoes_pendentes }}</b></h4></span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                    <span class="progress-description">
                        <p>Lojas com avaliações</p>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6 card-action" style="cursor: pointer;" data-target="#pendentes">
            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
                <span class="info-box-number"><h4><b>{{ $avaliacoes_pendentes->count() }}</b></h4></span>

                <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
            </div>
                <span class="progress-description">
                    <p>Pedentes</p>
                </span>
            </div>
        </div>
    </div>
    
        <div class="col-lg-3 col-xs-6 card-action" style="cursor: pointer;" data-target="#feitas">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
                <span class="info-box-number"><h4><b>{{ $avaliacoes_feitas->count() }}</b></h4></span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                    <span class="progress-description">
                        <p>Finalizadas</p>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-xs-6 card-action" style="cursor: pointer;" data-target="#sem-comprovante"> 
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
                <span class="info-box-number"><h4><b>{{ $avaliacoes_feitas_sem_comprovante->count() }}</b></h4></span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                    <span class="progress-description">
                        <p>Sem comprovante</p>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row to-hide" id="pendentes">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Avaliações Pendentes</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Finalizou o preenchimento?</th>
                                {{--<th>Enviou comprovante?</th>--}}
                                <th>Loja</th>
                                <th>Praça</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($avaliacoes_pendentes as $avp)
                                <?php $loja = \App\Models\Loja::find($avp->pivot->loja_id); ?>
                                <tr>
                                    <td>{{ $avp->nome }}</td>
                                    <td>{{ $avp->email }}</td>
                                    <td>
                                        @if($avp->pivot->respondido_em)
                                            {{ \Carbon\Carbon::parse($avp->pivot->respondido_em)->format('d/m/Y H:i:s') }}
                                        @else
                                            Não
                                        @endif
                                    </td>
                                    {{--<td>--}}
                                        {{--@if($avp->pivot->data_termino)--}}
                                            {{--{{ \Carbon\Carbon::parse($avp->pivot->data_termino)->format('d/m/Y H:i:s') }}--}}
                                        {{--@else--}}
                                            {{--Não--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    <td>{{ $loja->nome }}</td>
                                    <td>{{ $loja->praca->nome }}</td>
                                    <td style="width: 9%">
                                        <div class="row">
                                         <div class="col-md-5">
                                            <a href="#" rel="modal" data-modal="{{ $avp->id }}" class="btn btn-primary"><i title="Ver Perfil" class="fa fa-info"></i></a>
                                         </div>   
                                         <div class="col-md-5">
                                            <a href="{{ route('avaliadoroculto.users.formularios', $avp->id) }}" class="btn btn-success"><i title="Avaliações conlcuídas" class="fa fa-check-square"></i></a>
                                         </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="row to-hide" id="feitas" style="display: none;">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Avaliações Finalizadas</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Finalizou o preenchimento?</th>
                            {{--<th>Enviou comprovante?</th>--}}
                            <th>Loja</th>
                            <th>Praça</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($avaliacoes_feitas as $avf)
                            <?php $loja = \App\Models\Loja::find($avf->pivot->loja_id); ?>
                            <tr>
                                <td>{{ $avf->nome }}</td>
                                <td>{{ $avf->email }}</td>
                                <td>
                                    @if($avf->pivot->respondido_em)
                                        {{ \Carbon\Carbon::parse($avf->pivot->respondido_em)->format('d/m/Y H:i:s') }}
                                    @else
                                        Não
                                    @endif
                                </td>
                                {{--<td>--}}
                                    {{--@if($avf->pivot->finalizou)--}}
                                        {{--{{ \Carbon\Carbon::parse($avf->pivot->data_termino)->format('d/m/Y H:i:s') }}--}}
                                    {{--@else--}}
                                        {{--Não--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                <td>{{ $loja->nome }}</td>
                                <td>{{ $loja->praca->nome }}</td>
                                <td>
                                    <div class="col-md-5">
                                        <a href="#" rel="modal" data-modal="{{ $avf->id }}" class="btn btn-primary"><i title="Ver Perfil" class="fa fa-info"></i></a>
                                    </div>   
                                    <div class="col-md-5">
                                        <a href="{{ route('avaliadoroculto.users.formularios', $avf->id) }}" class="btn btn-success"><i title="Avaliações respondidas" class="fa fa-check-square"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="row to-hide" id="sem-comprovante" style="display: none;">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Finalizada sem comprovante</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Finalizou o preenchimento?</th>
                            {{--<th>Enviou comprovante?</th>--}}
                            <th>Loja</th>
                            <th>Praça</th>
                            <th>Opções</th>
                        </tr>
                        </thead>    
                        <tbody>
                        @foreach($avaliacoes_feitas_sem_comprovante as $avfsc)
                            <?php $loja = \App\Models\Loja::find($avfsc->pivot->loja_id); ?>
                            <tr>
                                <td>{{ $avfsc->nome }}</td>
                                <td>{{ $avfsc->email }}</td>
                                <td>
                                    @if($avfsc->pivot->respondido_em)
                                        {{ \Carbon\Carbon::parse($avfsc->pivot->respondido_em)->format('d/m/Y H:i:s') }}
                                    @else
                                        Não
                                    @endif
                                </td>
                                {{--<td>--}}
                                    {{--@if($avfsc->pivot->data_termino)--}}
                                        {{--{{ \Carbon\Carbon::parse($avfsc->pivot->data_termino)->format('d/m/Y H:i:s') }}--}}
                                    {{--@else--}}
                                        {{--Não--}}
                                    {{--@endif--}}
                                {{--</td>--}}
                                <td>{{ $loja->nome }}</td>
                                <td>{{ $loja->praca->nome }}</td>
                                <td>
                                    <div class="col-md-5">
                                        <a href="#" rel="modal" data-modal="{{ $avfsc->id }}" class="btn btn-primary"><i title="Ver Perfil" class="fa fa-info"></i></a>
                                    </div>   
                                    <div class="col-md-5">
                                        <a href="{{ route('avaliadoroculto.users.formularios', $avfsc->id) }}" class="btn btn-success"><i title="Avaliações respondidas" class="fa fa-check-square"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
    <div class="row to-hide" id="lojas-pendentes" style="display: none;">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Lojas com avaliações</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Loja</th>
                            <th>Pendentes</th>
                            <th>Concluídas</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lojas_objs as $loja)
                            @if($loja->pendentes > 0)
                                <tr>
                                    <td>{{ $loja->loja->nome }}</td>
                                    <td>{{ $loja->pendentes }}</td>
                                    <td>{{ $loja->concluidas }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>
    </div>
    <?php $lista = $avaliacoes_feitas->merge($avaliacoes_pendentes); ?>
    @include('avaliador-oculto.admin.users.modal-detalhes')
    @include('avaliador-oculto.admin.filtro')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.card-action').click(function(e){
                e.preventDefault();
                $('.to-hide').hide();
                $('.fa-arrow-circle-down').hide();
                $($(this).data('target')).show();
                $(this).children().children('.small-box-footer').children().children().show();
                return false;
            });
        })
    </script>
@endsection