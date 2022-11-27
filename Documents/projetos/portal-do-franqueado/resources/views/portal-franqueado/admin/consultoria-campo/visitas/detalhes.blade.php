@extends('layouts.portal-franqueado')

@section('extra_styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .ui-state-active.btn-success
        {
            color: #fff !important;
            background-color: #5cb85c !important;
            border-color: #4cae4c !important;
        }
        .ui-state-active.btn-danger
        {
            color: #fff !important;
            background-color: #d9534f !important;
            border-color: #d43f3a !important;
        }
        .circle {
            display: inline-block;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            font-size: 18px;
            color: #fff;
            line-height: 30px;
            text-align: center;
            background: #4082ba;
        }
    </style>
@endsection
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Relatório da Visita
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row" id="root">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Questionário aplicado</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Unidade:</th>
                            <td>{{ $item->loja->nome }}</td>
                            <th>Data:</th>
                            <td>{{ $item->data->format('d/m/Y') }}</td>
                            <th>Consultor:</th>
                            <td>{{ $item->user->nome }}</td>
                        </tr>
                    </table>
                    <h3 class="text-center">{{ $item->formulario->descricao }}</h3>
                    <div class="box-group" id="accordion">
                        @foreach($item->formulario->topicos as $i => $topico)
                            <div class="panel box box-default box-solid">
                                <div class="box-header with-border">
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <h4 class="box-title">
                                                <div class="circle">{{ $i + 1 }}</div>
                                                <span style="text-transform: uppercase;">{{ $topico->descricao }}</span>
                                            </h4>
                                        </div>
                                        <div class="col-xs-1 text-right" style="line-height: 30px;padding-right: 0;">Score: </div>
                                        <div class="col-xs-4">
                                            <?php
                                            $total = $topico->perguntas->count();
                                            $pontuacao = $item->pontuacaoPorTopico($topico->id);
                                            $porcentagem = (float) $item->porcentagemPorTopico($topico->id);
                                            $porcentagem = number_format(str_replace(',', '.', $porcentagem), 2);
                                            if ($porcentagem <= 30) {
                                                $class = 'progress-bar-danger';
                                            } elseif ($porcentagem > 30 && $porcentagem < 70) {
                                                $class = 'progress-bar-warning';
                                            } else {
                                                $class = 'progress-bar-success';
                                            }
                                            ?>
                                            <div id="progress-{{ $topico->id }}" class="progress" style="margin-bottom: 0;border-radius: 6px; height: 30px;" data-total="{{ $topico->perguntas->count() }}">
                                                <div class="progress-bar {{ $class }} progress-bar-striped" role="progressbar" style="width: {{ $porcentagem }}%;">
                                                    <span class="progress-bar-label" style="font-weight: bold;line-height: 30px;">{{ $porcentagem }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body">
                                    @foreach($topico->perguntas as $j => $pergunta)
                                        <?php
                                        $resposta = $item->respostaPergunta($pergunta);
                                        ?>
                                        <div class="form-group">
                                            <div class="form-group">
                                                <div style="text-indent: 50px;">{{ $i + 1 }}.{{ $j + 1 }}  {{ $pergunta->pergunta }}</div>
                                                @if($resposta)
                                                    <div class="row" style="
                                                        border-bottom: 1px dashed #ccc;
                                                        margin-right: 0;
                                                        margin-left: 0;
                                                        padding-bottom: 10px;
                                                        ">
                                                        <div class="col-xs-12" style="padding-left: 50px;">
                                                            {!! $resposta->resposta_formatted !!}
                                                        </div>
                                                        <div class="col-xs-12" style="padding-left: 50px; margin-top: 10px;" data-target="{{ $pergunta->id }}">
                                                            @foreach($resposta->fotos as $foto)
                                                                <a class="colorbox" href="{{ asset('uploads/visitas/' . $foto) }}">
                                                                    <img class="img-thumbnail" style="width: 150px; height: 150px;" src="{{ asset('uploads/visitas/' . $foto) }}">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group {{ $errors->has('relato_final') ? 'has-error' : '' }}">
                        {!! Form::label('relato_final', 'Relato final da visita') !!}
                        {!! Form::textarea('relato_final' , $item->relato_final, ['disabled','class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="box box-danger box-solid">
                        <div class="box-header with-border text-center">
                            <h2 class="box-title">Painel de Notificações</h2>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Notificação</th>
                                    <th>Valor unitário</th>
                                    <th>Quantidade</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody id="notificacoes-list">
                                    @foreach($item->notificacoes as $notificacao)
                                        <tr>
                                            <td>{{ $notificacao->descricao }}</td>
                                            <td>{{ maskMoney($notificacao->pivot->valor_un) }}</td>
                                            <td>{{ $notificacao->pivot->quantidade }}</td>
                                            <td>{{ maskMoney($notificacao->pivot->quantidade * $notificacao->pivot->valor_un) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('a.colorbox').colorbox({
                rel: 'colorbox',
                width: '90%'
            });
        });
    </script>
@endsection