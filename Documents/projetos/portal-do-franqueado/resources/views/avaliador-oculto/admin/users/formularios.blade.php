@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Avaliador formulários
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Adicionar mais formulários</h3>
                </div>
                {!! Form::open(['url' => route('avaliadoroculto.users.formularios.post', $user->id), 'files' => true]) !!}
                <div class="box-body">
                    <div class="usuarios_formularios">
                        <div class="form-to">
                            <div class="form-group {{ $errors->has('data_visita') ? 'has-error' : '' }}">
                                {!! Form::label('data_visita', 'Data e hora da visita') !!}
                                {!! Form::text('data_visita' , '' , ['class' => 'form-control default-datetimepicker']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('voucher', 'Arquivo do Voucher') !!}
                                {!! Form::file('voucher', ['required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('loja', 'Loja') !!}
                                {!! Form::select('loja' , $lojas , null, ['required' => true, 'placeholder' => 'Selecione uma loja','class' => 'form-control']) !!}
                            </div>
                            <div class="target"></div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('avaliadoroculto.users.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Confirmar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Formularios aplicados para: {{ $user->nome }}</h3>
                </div>
            </div>
        </div>
    </div>
    @if($user->formularios->count() > 0)
        @foreach($user->formularios as $formulario)
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-black box-solid">
                        <div class="box-header">
                            <h3 class="box-title">{{ $formulario->nome }}
                                : {{ \App\Models\Loja::find($formulario->pivot->loja_id)->nome }}</h3>
                        </div>
                        <div class="box-body no-padding">
                            <table class="table table-bordered table-responsive">
                                <tr>
                                    @if(!$formulario->pivot->respondido_em)
                                        <th>Cancelar Visita</th>
                                        <td>
                                            {!! Form::open(['url' => route('avaliadoroculto.formularios.remover.formulario'), 'method' => 'delete']) !!}
                                            {!! Form::hidden('user_id', $user->id)  !!}
                                            {!! Form::hidden('formulario_id', $formulario->id)  !!}
                                            {!! Form::submit('Cancelar visita', ['class' => 'btn btn-danger btn-block']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Data da visita</th>
                                    <td>
                                        @if($formulario->pivot->data_visita)
                                            {{ \Carbon\Carbon::parse($formulario->pivot->data_visita)->format('d/m/Y H:i') }}
                                        @else
                                            --/--/----
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Finalizou o preenchimento?</th>
                                    <td>
                                        @if($formulario->pivot->respondido_em)
                                            {{ \Carbon\Carbon::parse($formulario->pivot->respondido_em)->format('d/m/Y H:i:s') }}
                                        @else
                                            Não
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Perguntas:</th>
                                    <th>Resposta:</th>
                                </tr>
                                @foreach($formulario->perguntas as $pergunta)
                                    <?php
                                    $resposta = $pergunta->resposta($formulario->pivot->loja_id, $user->id)->first();
                                    ?>
                                    <tr>
                                        <td>{{ $pergunta->pergunta }}</td>
                                        <td>{!! $resposta ?  $resposta->resposta_formatted : 'Não respondeu' !!}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="2">Relato final:</th>
                                </tr>
                                <tr>
                                    <td colspan="2">{{ $formulario->pivot->observacoes }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Cupom fiscal:</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a target="_blank" href="{{ asset('uploads/cliente_oculto_comprovantes/' . $formulario->pivot->foto_comprovante) }}">{{ $formulario->pivot->foto_comprovante }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Foto da fachada:</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a target="_blank" href="{{ asset('uploads/cliente_oculto_comprovantes/' . $formulario->pivot->foto_loja) }}">{{ $formulario->pivot->foto_loja }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">Foto do consumo:</th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a target="_blank" href="{{ asset('uploads/cliente_oculto_comprovantes/' . $formulario->pivot->foto_consumo) }}">{{ $formulario->pivot->foto_consumo }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <strong>Reaplicar / Resetar este formulário?</strong> <br>Esta opção faz com
                                            que o formulário deva ser respondido novamente, sendo assim, apagando também
                                            o comprovante que foi enviado posteriormente.
                                        </div>
                                    </td>
                                    <td>
                                        {!! Form::open(['url' => route('avaliadoroculto.formularios.resetar.formulario'), 'method' => 'delete']) !!}
                                        {!! Form::hidden('user_id', $user->id) !!}
                                        {!! Form::hidden('formulario_id', $formulario->id) !!}
                                        {!! Form::hidden('loja_id', $formulario->pivot->loja_id) !!}
                                        {!! Form::submit('Reaplicar / Resetar', ['class' => 'btn btn-flat btn-danger']) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif


@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.usuarios_formularios').on('change', 'select', function () {
                var diz = $(this);
                if (id = $(this).val())
                    $.ajax({
                        url: '{{ url('ajax_avaliadoroculto/formularios_da_loja') }}/' + id
                    }).done(function (data) {
                        diz.parent().parent().children('.target').html(data);
                    });
            });
        });
    </script>
@endsection