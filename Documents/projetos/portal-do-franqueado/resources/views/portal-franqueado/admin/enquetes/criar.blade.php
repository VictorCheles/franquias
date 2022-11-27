@extends('layouts.portal-franqueado')
@section('extra_styles')
    @parent
    <style>
        .add-resposta, .remover-resposta, .remover-pergunta, .group-result
        {
            cursor: pointer !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        {!! Form::open(['url' => route('admin.enquetes.store'), 'files' => true]) !!}
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Enquete</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group {{ $errors->has('enquete.nome') ? 'has-error' : '' }}">
                            {!! Form::label('enquete[nome]', 'Nome') !!}
                            {!! Form::text('enquete[nome]' , '' , ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group {{ $errors->has('destinatario') ? 'has-error' : '' }}">
                            {!! Form::label('destinatario', 'Destinatários (Lista por Loja)') !!}
                            {!! Form::select('destinatario[]' , $optionsUsuarios , null, ['multiple', 'class' => 'form-control chosen']) !!}
                            <div style="margin-top: 5px;">
                                <a href="#" class="btn btn-flat btn-danger remover-todos">Remover todos</a>
                                <a href="#" class="btn btn-flat btn-info pull-right todos">Adicionar todos</a>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('enquete.inicio') ? 'has-error' : '' }}">
                            {!! Form::label('enquete[inicio]', 'Início') !!}
                            {!! Form::date('enquete[inicio]' , date('Y-m-d'), ['class' => 'form-control datepicker', 'data-startdate' => 'd']) !!}
                        </div>
                        <div class="form-group {{ $errors->has('enquete.fim') ? 'has-error' : '' }}">
                            {!! Form::label('enquete[fim]', 'Fim') !!}
                            {!! Form::date('enquete[fim]' , date('Y-m-d', strtotime('+1 day')), ['class' => 'form-control datepicker', 'data-startdate' => '+1d']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 pergunta">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pergunta</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group {{ $errors->has('pergunta.0') ? 'has-error' : '' }}">
                            <label for="pergunta[0]">Pergunta</label>
                            <input class="form-control" name="pergunta[0]" id="pergunta[0]" type="text" value="{{ old('pergunta.0') }}">
                        </div>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title">Respostas <i data-rel="0" class="fa fa-plus add-resposta"></i></h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group resposta {{ $errors->has('resposta.0.0') ? 'has-error' : '' }}">
                            <label for="resposta[0][0]">Resposta</label>
                            <input class="form-control" name="resposta[0][0]" id="resposta[0][0]" type="text" value="{{ old('resposta.0.0') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="box box-black box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Adicionar mais uma pergunta</h3>
                    </div>
                    <div class="box-body text-center">
                        <a href="#" class="btn btn-flat btn-info add-pergunta">Adicionar uma pergunta</a>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title">Finalizar</h3>
                    </div>
                    <div class="box-body text-center">
                        <b>Antes de terminar, certifique-se de que as perguntas não são vazias, e que cada pergunta possui ao menos uma resposta</b>
                    </div>
                    <div class="box-footer">
                        {!! link_to('/admin/comunicados/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                        {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            function pergunta_template(pergunta)
            {
                var template = '' +
                    '<div class="col-sm-12 pergunta">' +
                        '<div class="box box-black box-solid">' +
                            '<div class="box-header with-border">' +
                                '<h3 class="box-title">Pergunta <i class="fa fa-minus remover-pergunta"></i></h3>' +
                            '</div>' +
                            '<div class="box-body">' +
                                '<div class="form-group">' +
                                    '<label for="pergunta['+ pergunta +']">Pergunta</label>' +
                                    '<input class="form-control" name="pergunta['+ pergunta +']" id="pergunta['+ pergunta +']" type="text">' +
                                '</div>' +
                            '</div>' +
                            '<div class="box-header with-border">' +
                                '<h3 class="box-title">Respostas <i data-rel="'+ pergunta +'" class="fa fa-plus add-resposta"></i></h3>' +
                            '</div>' +
                            '<div class="box-body">' +
                                '<div class="form-group resposta">' +
                                    '<label for="resposta['+ pergunta +'][0]">Resposta</label>' +
                                    '<input class="form-control" name="resposta['+ pergunta +'][0]" id="resposta['+ pergunta +'][0]" type="text">' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                return template;
            }

            function resposta_template(pergunta, resposta)
            {
                var template = '' +
                    '<div class="form-group resposta">' +
                        '<label for="resposta['+ pergunta +']['+ resposta +']">Resposta</label>' +
                        '<div class="input-group">' +
                            '<span class="input-group-addon remover-resposta"><i class="fa fa-minus"></i></span>' +
                            '<input class="form-control" name="resposta['+ pergunta +']['+ resposta +']" id="resposta['+ pergunta +']['+ resposta +']" type="text">' +
                        '</div>' +
                    '</div>';
                return template;
            }
            $('form').on('click', '.add-resposta',function () {
                pergunta = $(this).data('rel');
                resposta = $(this).parent().parent().parent().children('.box-body').eq(1).children('.form-group.resposta').length;
                local = $(this).parent().parent().parent().children('.box-body').eq(1).children('.form-group.resposta').last();
                local.after(resposta_template(pergunta, resposta));
            });
            $('form').on('click', '.remover-resposta', function(){
                $(this).parent().parent().remove();
            });


            $('.add-pergunta').click(function(e){
                e.preventDefault();
                pergunta = $('.pergunta').length;
                $('.pergunta').last().after(pergunta_template(pergunta));
            });

            $('form').on('click', '.remover-pergunta', function(){
                $(this).parent().parent().parent().parent().remove();
            });


            $('.chosen').chosen({
                no_results_text: "Sem resultados para",
                placeholder_text_single: "Selecione uma opção",
                placeholder_text_multiple: "Selecione os destinatários"
            });

            $('.todos').click(function(){
                $(this).parent().parent().children().find('option').prop('selected', true);
                $(this).parent().parent().children().trigger('chosen:updated');
                return false;
            });

            $('.remover-todos').click(function(){
                $(this).parent().parent().children().find('option').prop('selected', false);
                $(this).parent().parent().children().trigger('chosen:updated');
                return false;
            });

            $('form').on('click', '.group-result', function () {
                var unselected = $(this).nextUntil('.group-result').not('.result-selected');
                if (unselected.length) {
                    unselected.trigger('mouseup');
                } else {
                    $(this).nextUntil('.group-result').each(function () {
                        $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
                    });
                }
            });
        });
    </script>
@endsection