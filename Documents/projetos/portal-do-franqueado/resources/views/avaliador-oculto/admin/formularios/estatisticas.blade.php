@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Estatísticas do formulário
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <!-- display inline essas benga -->
                    <h3 class="box-title">{{ $formulario->nome }}</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Loja - Praça</th>
                                <th>Qtd avaliações</th>
                                <th>Pontuação</th>
                                <th>Performance</th>
                                <th style="width: 10%">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $ranking = 1;?>
                            @foreach($lojas as $loja)
                                <tr>
                                    <td class="text-center">{!! rankingTrophies($ranking++) !!}</td>
                                    <td>{{ $loja->nome . ' / ' . $loja->praca->nome }}</td>
                                    <td>{{ $formulario->users()->where('loja_id', $loja->id)->wherePivot('finalizou', true)->count() }}</td>
                                    <td>{{ $loja->pontuacao_total }}/{{ $loja->pontuacao_maxima }}</td>
                                    <td>{{ number_format($loja->aproveitamento, 2, ',', '.') }}%</td>
                                    <td class="options">
                                        <div class="btn-group-vertical">
                                            <a href="#" data-modal="{{ $loja->id }}" class="btn btn-default btn-flat open-modal-detalhes"><i class="fa fa-info"></i> Detalhar</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php $ranking = 1;?>
    @foreach($lojas as $loja)
        <div class="modal modal-{{ $loja->id }} fade">
            <div class="modal-dialog" style="width: 90%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">{!! rankingTrophies($ranking++) !!} {{ $loja->nome . ' / ' . $loja->praca->nome }}</h4>
                    </div>
                    <div class="modal-body no-padding">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Pergunta</th>
                                <th>Peso</th>
                                <th>Peso negativo</th>
                                <th width="20%">Respostas</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($loja->perguntas as $pergunta)
                                <tr>
                                    <td>{{ $pergunta->pergunta }}</td>
                                    <td>{{ $pergunta->peso }} - ({{ number_format($pergunta->porcentagem_pergunta, 2, ',', '.') }}%)</td>
                                    <td>{{ $pergunta->peso_negativo }}</td>
                                    <td>
                                        @if($pergunta->tipo == \App\Models\AvaliadorOculto\Pergunta::TIPO_SIM_NAO)
                                            <table class="table table-striped table-bordered text-center">
                                                <tr>
                                                    <th style="padding: 2px;" class="label-success">Sim ({{ number_format($pergunta->porcentagem_sim, 2, ',', '.') . '%' }})</th>
                                                    <th style="padding: 2px;" class="label-danger">Não ({{ number_format($pergunta->porcentagem_nao, 2, ',', '.') . '%' }})</th>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px;">{{ $pergunta->sim }}</td>
                                                    <td style="padding: 2px;">{{ $pergunta->nao }}</td>
                                                </tr>
                                            </table>
                                        @else
                                            <ol>
                                                @foreach($pergunta->respostas as $resposta)
                                                    <li>{{ $resposta->resposta }}</li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-default pull" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endforeach
    @include('avaliador-oculto.admin.formularios.modal-filter-estatisticas')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-modal-detalhes').click(function(e){
                e.preventDefault();
                var modal = $(this).data('modal');
                $('.modal-' + modal).modal();
            });
        });
    </script>
@endsection