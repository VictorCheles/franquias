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
                {!! Form::model($item, ['url' => route('avaliadoroculto.formularios.update', $item->id), 'method' => 'patch']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , null , ['class' => 'form-control', 'required' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('lojas') ? 'has-error' : '' }}">
                        {!! Form::label('lojas', 'Lojas') !!}
                        {!! Form::select('lojas[]' , $lojas , $item->lojas->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => true, 'required' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status' , \App\Models\AvaliadorOculto\Formulario::MAPA_STATUS , null, ['class' => 'form-control', 'required' => true]) !!}
                    </div>
                    <div class="callout callout-warning">
                        <h4>Cuidado aqui!</h4>
                        <p>Ao clicar no botão "Remover pergunta" ela será removida permanentemente</p>
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
                                        @if($k > 0)
                                            <label for="pergunta">Pergunta</label>
                                            <div class="input-group">
                                                <span class="input-group-addon remover-pergunta" data-id="{{ $pergunta->id }}"><i class="fa fa-minus"></i></span>
                                                <textarea class="form-control" rows="2" name="pergunta[{{ $pergunta->id }}]">{{ $pergunta->pergunta }}</textarea>
                                            </div>
                                        @else
                                            {!! Form::label('pergunta', 'Pergunta') !!}
                                            {!! Form::textarea('pergunta['. $pergunta->id .']' , $pergunta->pergunta , ['class' => 'form-control','rows' => 2,'required' => true, 'data-id' => $pergunta->id]) !!}
                                        @endif
                                    </div>
                                    <div class="col-sm-3">
                                        {!! Form::label('pergunta_tipo', 'Tipo') !!}
                                        {!! Form::select('pergunta_tipo['. $pergunta->id .']' , \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , $pergunta->tipo, ['class' => 'form-control', 'required' => true]) !!}
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
                    <div class="form-group">
                        <a href="#" class="btn btn-flat btn-info pull-right add-pergunta" style="width: auto;"><i class="fa fa-plus"></i> Adicionar pergunta</a>
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
    <div class="modal fade modal-add-pergunta">
        <div class="modal-dialog" style="width: 80%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Adicionar pergunta</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-5">
                                {!! Form::label('pergunta', 'Pergunta') !!}
                                {!! Form::textarea('nova_pergunta' , null , ['class' => 'form-control', 'rows' => 2,'required' => true, 'data-id' => $pergunta->id]) !!}
                            </div>
                            <div class="col-sm-3">
                                {!! Form::label('pergunta_tipo', 'Tipo') !!}
                                {!! Form::select('nova_pergunta_tipo', \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , null, ['class' => 'form-control', 'required' => true]) !!}
                            </div>
                            <div class="col-sm-2">
                                {!! Form::label('pergunta_peso', 'Peso') !!}
                                {!! Form::number('nova_pergunta_peso', 1, ['class' => 'form-control', 'required' => true]) !!}
                            </div>
                            <div class="col-sm-2">
                                {!! Form::label('pergunta_peso_negativo', 'Peso negativo') !!}
                                {!! Form::number('nova_pergunta_peso_negativo', 1, ['class' => 'form-control', 'required' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default pull-left cancelar-pergunta" data-dismiss="modal">Cancelar</button>
                    <a href="#" class="btn btn-flat btn-primary salvar-pergunta">Salvar pergunta</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        function pergunta_template(id, pergunta, tipo, peso, peso_negativo)
        {
            var template = '' +
                '<div class="form-group perguntas">' +
                    '<div class="row">' +
                        '<div class="col-sm-5">'+
                            '<label for="pergunta">Pergunta</label>' +
                            '<div class="input-group">' +
                                '<span class="input-group-addon remover-pergunta" data-id="'+id+'"><i class="fa fa-minus"></i></span>' +
                                '<textarea class="form-control" rows="2" name="pergunta[]">' +pergunta+ '</textarea>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-sm-3">'+
                            '<label for="pergunta_tipo">Tipo</label>'+
                            '{!! Form::select('pergunta_tipo[]' , \App\Models\AvaliadorOculto\Pergunta::MAPA_TIPOS , '', ['class' => 'form-control', 'required' => true]) !!}'+
                        '</div>'+
                        '<div class="col-sm-2">'+
                            '<label for="pergunta_peso">Peso</label>'+
                            '<input class="form-control input-peso" required="true" name="pergunta_peso[]" value="' +peso+ '" type="number">' +
                        '</div>'+
                        '<div class="col-sm-2">'+
                            '<label for="pergunta_peso_negativo">Peso negativo</label>'+
                            '<input class="form-control input-peso" required="true" name="pergunta_peso_negativo[]" value="' +peso_negativo+ '" type="number">' +
                        '</div>'+
                    '</div>' +
                '</div>';
            return template;
        }


        $('.add-pergunta').click(function(e){
            e.preventDefault();
            $('.modal-add-pergunta').modal();
        });

        $('.salvar-pergunta').click(function(e){
            e.preventDefault();
            var pergunta = $('.modal-add-pergunta textarea[name=nova_pergunta]').val();
            var tipo = $('.modal-add-pergunta select[name=nova_pergunta_tipo]').val();
            var peso = $('.modal-add-pergunta input[name=nova_pergunta_peso]').val();
            var peso_negativo = $('.modal-add-pergunta input[name=nova_pergunta_peso_negativo]').val();
            if(!pergunta || !tipo || !peso)
            {
                swal('Erro!', 'Preencha todos os campos', 'error');
            }

            $.ajax({
                url: '{{ route('ajax.avaliadoroculto.formularios.nova.pergunta') }}',
                type: 'post',
                data: {
                    "_token": '{{ csrf_token() }}',
                    pergunta: pergunta,
                    tipo: tipo,
                    peso: peso,
                    peso_negativo: peso_negativo,
                    formulario: '{{ $item->id }}'
                }
            }).done(function(data){
                $('.modal-add-pergunta input[name=nova_pergunta]').val('');
                $('.modal-add-pergunta select[name=nova_pergunta_tipo]').val('');
                $('.modal-add-pergunta input[name=nova_pergunta_peso]').val('');
                $('.modal-add-pergunta input[name=nova_pergunta_peso_negativo]').val('');
                $('.modal-add-pergunta').modal('hide');
                $('.form-group.perguntas').last().after(pergunta_template(data.id, pergunta, tipo, peso, peso_negativo));
                $('.form-group.perguntas').last().find('select').val(tipo);
                $(".sortable").sortable();

            }).error(function(data){

            });
        });

        $('form').on('click', '.remover-pergunta', function(){
            var diz = $(this);
            var id = diz.data('id');
            $(".sortable").disableSelection();
            swal({
                title: "Deseja remover esta pergunta?",
                text: "Esta operação não pode ser desfeita, e ela é definitiva",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Remover",
                closeOnConfirm: true
            },
            function(){
                $.ajax({
                    url: '{{ route('ajax.avaliadoroculto.formularios.remover.pergunta') }}',
                    type: 'post',
                    data: {
                        "_token": '{{ csrf_token() }}',
                        pergunta: id
                    }
                }).done(function(data){
                    diz.parent().parent().parent().parent().remove();
                }).error(function(data){
                    swal('Erro', 'Não foi possível remover esta pergunta', 'error');
                });
            });
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