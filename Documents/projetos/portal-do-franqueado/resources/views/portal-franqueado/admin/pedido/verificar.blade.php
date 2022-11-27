<?php $user = Auth()->user(); ?>
@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Pedido - Verificação / Acompanhamento
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-black box-solid">
                {!! Form::open(['url' => route('pedido.verificacao', $item->id)]) !!}
                    <div class="box-header">
                        <h3 class="box-title">Histórico</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                    @if($mensagens->count() > 0)
                            <ul class="timeline timeline-inverse">
                                @foreach($mensagens as $date => $lista)
                                    <li class="time-label">
                                        <span class="bg-blue">
                                            {{ $date }}
                                        </span>
                                    </li>
                                    @foreach($lista as $mensagem)
                                        <li>
                                            <i class="fa fa-comments bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> {{ $mensagem->created_at->format('H:i') }}</span>
                                                <h3 class="timeline-header"><a href="#">{{ $mensagem->user->nome }}</a>
                                                </h3>
                                                <div class="timeline-body">
                                                    {{ $mensagem->mensagem }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        @endif
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('mensagem') ? 'has-error' : '' }}">
                                {!! Form::label('mensagem', 'Mensagem') !!}
                                {!! Form::textarea('mensagem' , null, ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        {!! link_to(route('pedido.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                        {!! Form::submit('Enviar mensagem', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Pedido</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th>#</th>
                            <td>{{ $item->id }}</td>
                        </tr>
                        <tr>
                            <th>Loja</th>
                            <td>{{ $item->loja->nome }}</td>
                        </tr>
                        <tr>
                            <th>Produtos</th>
                            <td style="margin:0; padding: 0">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Valor unitário</th>
                                        <th>Quantidade</th>
                                        <th>Peso</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    </thead>
                                    <tbody class="target-new-products">
                                    @foreach($item->produtos as $produto)
                                        <tr>
                                            <td>{{ $produto->nome }}</td>
                                            <td>{{ maskMoney($produto['pivot']->preco) }}</td>
                                            <td width="15%">
                                                {{ $produto['pivot']->quantidade }}
                                            </td>
                                            <td>{{ $produto['pivot']->quantidade * $produto->peso }}kg</td>
                                            <td>{{ maskMoney($produto['pivot']->quantidade * $produto['pivot']->preco) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th>Observações</th>
                            <td>{{ $item->observacoes }}</td>
                        </tr>
                        <tr>
                            <th>Multa</th>
                            <td>
                                {{ maskMoney($item->multa) }}
                            </td>
                        </tr>
                        <tr>
                            <th>Valor total</th>
                            <td>{{ maskMoney($item->valorTotal() + $item->multa) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div><!-- /.col -->
    </div>
@endsection