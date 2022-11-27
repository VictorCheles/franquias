@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .remover-pergunta
        {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Editar Formulário
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::model($item, ['url' => route('avaliadoroculto.formularios.updatepeso', $item->id), 'method' => 'patch']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , null , ['class' => 'form-control', 'disabled' => true, 'readonly' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('lojas') ? 'has-error' : '' }}">
                        {!! Form::label('lojas', 'Lojas') !!}
                        {!! Form::select('lojas[]' , $lojas , $item->lojas->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => true, 'disabled' => true, 'readonly' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status' , \App\Models\AvaliadorOculto\Formulario::MAPA_STATUS , null, ['class' => 'form-control', 'disabled' => true, 'readonly' => true]) !!}
                    </div>
                    <div>
                        <div class="row" style="margin: 20px 0;">
                            <div class="col-xs-6 col-md-offset-8 col-md-4">
                                <div class="row">
                                    <div class="col-xs-6 peso-positivo">Soma: <span class="positivo">{{ $item->perguntas->sum('peso') }}</span></div>
                                    <div class="col-xs-6 peso-negativo">Soma: <span class="negativo">{{ $item->perguntas->sum('peso_negativo') }}</span></div>
                                </div>
                            </div>
                        </div>
                        @foreach($item->perguntas as $k => $pergunta)
                            <div class="form-group perguntas {{ $errors->has('pergunta.0') ? 'has-error' : '' }}">
                                <div class="row">
                                    <div class="col-sm-5">
                                        {!! Form::label('pergunta', 'Pergunta') !!}
                                        {!! Form::textarea('pergunta['. $pergunta->id .']' , $pergunta->pergunta , ['class' => 'form-control','rows' => 2 ,'disabled' => true, 'readonly' => true]) !!}
                                    </div>
                                    <div class="col-sm-3">
                                        {!! Form::label('pergunta_tipo', 'Tipo') !!}
                                        {!! Form::select('pergunta_tipo['. $pergunta->id .']' , \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , $pergunta->tipo, ['class' => 'form-control', 'disabled' => true, 'readonly' => true]) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::label('pergunta_peso', 'Peso') !!}
                                        {!! Form::number('pergunta_peso['. $pergunta->id .']' , $pergunta->peso, ['class' => 'form-control input-peso', 'required' => true]) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        {!! Form::label('pergunta_peso_negativo', 'Peso negativo') !!}
                                        {!! Form::number('pergunta_peso_negativo['. $pergunta->id .']' , $pergunta->peso_negativo, ['class' => 'form-control input-negativo', 'required' => true]) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row" style="margin: 20px 0;">
                            <div class="col-xs-6 col-md-offset-8 col-md-4">
                                <div class="row">
                                    <div class="col-xs-6 peso-positivo">Soma: <span class="positivo">{{ $item->perguntas->sum('peso') }}</span></div>
                                    <div class="col-xs-6 peso-negativo">Soma: <span class="negativo">{{ $item->perguntas->sum('peso_negativo') }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('avaliadoroculto.formularios.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.input-peso').change(function(){
                var soma = 0;
                $('.input-peso').each(function(i, input){
                    soma += parseInt($(input).val());
                });
                $('.peso-positivo .positivo').html(soma);
            });

            $('.input-negativo').change(function(){
                var soma = 0;
                $('.input-negativo').each(function(i, input){
                    soma += parseInt($(input).val());
                });
                $('.peso-negativo .negativo').html(soma);
            });
        });
    </script>
@endsection