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
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Novo Formulário
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
                {!! Form::open(['url' => route('avaliadoroculto.formularios.store')]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control', 'required' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('lojas') ? 'has-error' : '' }}">
                        {!! Form::label('lojas', 'Lojas') !!}
                        {!! Form::select('lojas[]' , $lojas , '', ['class' => 'form-control select2', 'multiple' => true, 'required' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status' , \App\Models\AvaliadorOculto\Formulario::MAPA_STATUS , '', ['class' => 'form-control', 'required' => true]) !!}
                    </div>
                    <div>
                        <div class="row" style="margin: 20px 0;">
                            <div class="col-xs-6 col-md-offset-8 col-md-4">
                                <div class="row">
                                    <div class="col-xs-6 peso-positivo">Soma: <span class="positivo">0</span></div>
                                    <div class="col-xs-6 peso-negativo">Soma: <span class="negativo">0</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group perguntas {{ $errors->has('pergunta.0') ? 'has-error' : '' }}">
                            <div class="row">
                                <div class="col-sm-5">
                                    {!! Form::label('pergunta', 'Pergunta') !!}
                                    {!! Form::textarea('pergunta[0]' , '' , ['class' => 'form-control', 'rows' => 2, 'required' => true]) !!}
                                </div>
                                <div class="col-sm-3">
                                    {!! Form::label('pergunta_tipo', 'Tipo') !!}
                                    {!! Form::select('pergunta_tipo[0]' , \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , '', ['class' => 'form-control', 'required' => true]) !!}
                                </div>
                                <div class="col-sm-2">
                                    {!! Form::label('pergunta_peso', 'Peso') !!}
                                    {!! Form::number('pergunta_peso[0]' , 0, ['class' => 'form-control input-peso', 'required' => true]) !!}
                                </div>
                                <div class="col-sm-2">
                                    {!! Form::label('pergunta_peso_negativo', 'Peso negativo') !!}
                                    {!! Form::number('pergunta_peso_negativo[0]' , 0, ['class' => 'form-control input-negativo', 'required' => true]) !!}
                                </div>
                            </div>
                        </div>
                        @if(old('pergunta') > 1)
                            @foreach(old('pergunta') as $k => $v)
                                @if($k == 0)
                                    @continue
                                @endif
                                <div class="form-group perguntas">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label for="pergunta">Pergunta</label>
                                            <div class="input-group">
                                                <span class="input-group-addon remover-pergunta"><i class="fa fa-minus"></i></span>
                                                <textarea class="form-control" name="pergunta[]" required>{{ $v }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="pergunta_tipo">Tipo</label>
                                            {!! Form::select('pergunta_tipo[]' , \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , old('pergunta_tipo')[$k], ['class' => 'form-control', 'required' => true]) !!}
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="pergunta_peso">Peso</label>
                                            {!! Form::number('pergunta_peso[]' , old('pergunta_peso')[$k], ['class' => 'form-control input-peso', 'required' => true]) !!}
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="pergunta_peso_negativo">Peso negativo</label>
                                            {!! Form::number('pergunta_peso_negativo[]' , old('pergunta_peso_negativo')[$k], ['class' => 'form-control input-negativo', 'required' => true]) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="row" style="margin: 20px 0;">
                            <div class="col-xs-6 col-md-offset-8 col-md-4">
                                <div class="row">
                                    <div class="col-xs-6 peso-positivo">Soma: <span class="positivo">0</span></div>
                                    <div class="col-xs-6 peso-negativo">Soma: <span class="negativo">0</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="#" class="btn btn-flat btn-info pull-right add-pergunta" style="width: auto;"><i class="fa fa-plus"></i> Adicionar pergunta</a>
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('avaliadoroculto.formularios.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Criar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        function pergunta_template()
        {
            var template = '' +
                '<div class="form-group perguntas">' +
                    '<div class="row">' +
                        '<div class="col-sm-5">'+
                            '<label for="pergunta">Pergunta</label>' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon remover-pergunta"><i class="fa fa-minus"></i></span>' +
                                '<input class="form-control" name="pergunta[]" type="text">' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-3">'+
                            '<label for="pergunta_tipo">Tipo</label>'+
                            '{!! Form::select('pergunta_tipo[]' , \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , '', ['class' => 'form-control', 'required' => true]) !!}'+
                        '</div>'+
                        '<div class="col-sm-2">'+
                            '<label for="pergunta_peso">Peso</label>'+
                            '{!! Form::number('pergunta_peso[]' , 0, ['class' => 'form-control input-peso', 'required' => true]) !!}'+
                        '</div>'+
                        '<div class="col-sm-2">'+
                            '<label for="pergunta_peso_negativo">Peso negativo</label>'+
                            '{!! Form::number('pergunta_peso_negativo[]' , 0, ['class' => 'form-control input-negativo', 'required' => true]) !!}'+
                        '</div>'+
                    '</div>' +
                '</div>';
            return template;
        }

        $('.add-pergunta').click(function(e){
            e.preventDefault();
            $('.form-group.perguntas').last().after(pergunta_template());
        });

        $('form').on('click', '.remover-pergunta', function(){
            $(this).parent().parent().parent().parent().remove();
        });


        $('.select2').select2({
            language: 'pt-BR'
        });

        $(function(){
            $('form').on('change', '.input-peso', function(){
                var soma = 0;
                $('.input-peso').each(function(i, input){
                    soma += parseInt($(input).val());
                });
                $('.peso-positivo .positivo').html(soma);
            });

            $('form').on('change', '.input-negativo', function(){
                var soma = 0;
                $('.input-negativo').each(function(i, input){
                    soma += parseInt($(input).val());
                });
                $('.peso-negativo .negativo').html(soma);
            });
        });
    </script>
@endsection