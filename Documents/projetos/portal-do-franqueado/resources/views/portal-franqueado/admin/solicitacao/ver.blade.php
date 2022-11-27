<?php $auth = Auth()->user(); ?>
@extends('layouts.portal-franqueado')

@section('content')
    <?php $count = $historico->count(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Solicitação - Acompanhamento
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Acompanhamento</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Ticket de acompanhamento</th>
                            <td>{{ $item->tag }}</td>
                        </tr>
                        <tr>
                            <th>Setor</th>
                            <td>{{ $setor->nome }}</td>
                        </tr>
                        <tr>
                            <th>Solicitante</th>
                            <td>{{ $solicitante->nome . ' | ' . $solicitante->email }}</td>
                        </tr>
                        <tr>
                            <th>Solicitação</th>
                            <td>{{ $item->titulo }}</td>
                        </tr>
                        <tr>
                            <th>Descrição</th>
                            <td>{!! $item->descricao !!}</td>
                        </tr>
                        <tr>
                            <th>Solicitada em</th>
                            <td>
                                {{ $item->created_at->format('d/m/Y \a\s H:i:s') }}
                                ({{ $item->created_at->diffForHumans() }})
                            </td>
                        </tr>
                        <tr>
                            <th>Prazo de conclusão</th>
                            <td>
                                {{ $item->prazo ? $item->prazo->format('d/m/Y') : 'Não definido' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Ultima atualização</th>
                            <td>
                                @if($count > 0)
                                    {{ $historico->first()->created_at->format('d/m/Y \a\s H:i:s') }}
                                    ({{ $historico->first()->created_at->diffForHumans() }})
                                @else
                                    Solicitação ainda não analisada.
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $item->status_formatted !!}</td>
                        </tr>
                        <tr>
                            <th>Anexos</th>
                            <td>
                                @if($item->anexos)
                                    <ol>
                                        @foreach($item->anexos as $a)
                                            <li>
                                                <a target="_blank" href="{{ asset('uploads/solicitacao/' . $a) }}">{{ $a }}</a>
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    Sem anexos
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Deixar Feedback</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    @if($auth->isAdmin())
                        @if(in_array($item->status, [\App\Models\Solicitacao::STATUS_FINALIZADA, \App\Models\Solicitacao::STATUS_NEGADA]))
                            <div class="text-center">
                                <button class="btn btn-flat btn-default open-form">Esta solicitação encontra-se {!! $item->status_formatted !!}, deseja enviar outras observações?</button>
                            </div>
                            <div class="hide-form" style="display: none;">
                                @include('portal-franqueado.admin.solicitacao.editar')
                            </div>
                        @else
                            @include('portal-franqueado.admin.solicitacao.editar')
                        @endif
                        <br>
                    @else
                        @if(in_array($item->status, [\App\Models\Solicitacao::STATUS_FINALIZADA, \App\Models\Solicitacao::STATUS_NEGADA]))
                            <div class="text-center">
                                <button class="btn btn-flat btn-default open-form">Esta solicitação encontra-se {!! $item->status_formatted !!}, deseja enviar outras observações?</button>
                            </div>
                            <div class="hide-form" style="display: none;">
                                @include('portal-franqueado.admin.solicitacao.editar-autor')
                            </div>
                        @else
                            @include('portal-franqueado.admin.solicitacao.editar-autor')
                        @endif
                        <br>
                    @endif
                </div>
            </div>
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Histórico</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    @if($historico->count() > 0)
                        <ul class="timeline timeline-inverse">
                            @foreach($historicoGrouped as $date => $data)
                                <li class="time-label">
                                        <span class="bg-blue">
                                            {{ date('d/m/Y', strtotime($date)) }}
                                        </span>
                                </li>
                                @foreach($data as $d)
                                    <li>
                                        <i class="fa fa-comments bg-blue"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{ $d->created_at->format('H:i') }}</span>
                                            <h3 class="timeline-header"><a href="#">{{ $d->user()->first()->nome }}</a></h3>
                                            <div class="timeline-body">
                                                {!! $d->observacoes !!}
                                                @if($d->status_anterior_formatted != $d->status_atual_formatted)
                                                    <i>Sua solicitação foi movida de
                                                        {!! $d->status_anterior_formatted !!}
                                                        para
                                                        {!! $d->status_atual_formatted !!}
                                                    </i>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div><!-- /.col -->
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-form').click(function(){
                $('.hide-form').toggle('height');
            });
        });
    </script>
@endsection