@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .group-topico {
            border-bottom: 1px dashed #ddd;
            padding: 10px 0;
        }
    </style>
@endsection

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Cadastrar novo Formulário
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
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['url' => route('admin.consultoria-de-campo.setup.formularios.store')]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::text('descricao' , '' , ['class' => 'form-control', 'required', 'maxlength' => 255]) !!}
                    </div>
                    <div class="group-topico form-group" data-ref="0">
                        {!! Form::label('topico[0][descricao]', 'Tópico 1') !!}
                        {!! Form::text('topico[0][descricao]' , Request::get('topico.0.descricao'), ['class' => 'form-control', 'required', 'maxlength' => 255]) !!}
                        <div style="margin-top:10px;">
                            <div class="row">
                                <div class="col-sm-1 hidden-xs">

                                </div>
                                <div class="col-sm-10 col-xs-12">
                                    <div class="group-pergunta form-group">
                                        {!! Form::label('topico[0][pergunta][0][descricao]', 'Pergunta') !!}
                                        {!! Form::text('topico[0][pergunta][0][descricao]' , Request::get('topico.0.pergunta.0.descricao'), ['class' => 'form-control', 'required', 'maxlength' => 255]) !!}
                                    </div>
                                    <a href="#" class="btn btn-info btn-flat btn-sm add-pergunta" data-id="0"><i class="fa fa-plus"></i> Nova pergunta</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-info btn-flat btn-sm add-topico"><i class="fa fa-plus"></i> Novo tópico</a>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.consultoria-de-campo.setup.formularios.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        function templatePergunta(topico_id, pergunta_id)
        {
            var buttom = '<a href="#" class="btn btn-flat btn-xs btn-danger remover-pergunta">Remover Pergunta</a>';
            if(pergunta_id == 0)
            {
                buttom = '';
            }
            var template = `
            <div class="group-pergunta form-group">
                <label for="topico[`+topico_id+`][pergunta][`+pergunta_id+`]">Pergunta `+ buttom +`</label>
                <input type="text" name="topico[`+topico_id+`][pergunta][`+pergunta_id+`][descricao]" class="form-control" required maxlength="255">
            </div>
            `;

            return template;
        }


        function templateTopico(id)
        {
            var pergunta = templatePergunta(id, 0);
            var template = `
                <div class="group-topico form-group" data-ref="`+ id +`">
                    <label for="topico[`+id+`]">Tópico `+ (id+1) +` <a href="#" class="btn btn-flat btn-xs btn-danger remover-topico">Remover tópico</a></label>
                    <input type="text" name="topico[`+id+`][descricao]" class="form-control" required maxlength="255">
                    <div style="margin-top:10px;">
                        <div class="row">
                            <div class="col-sm-1 hidden-xs">

                            </div>
                            <div class="col-sm-10 col-xs-12">
                                `+ pergunta +`
                                <a href="#" class="btn btn-info btn-flat btn-sm add-pergunta" data-id="`+ id +`"><i class="fa fa-plus"></i> Nova pergunta</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            return template;
        }

        var current_topico_id = 0;


        $(function(){
            $('#root').on('click', '.add-pergunta', function(e){
                e.preventDefault();
                var topico_id = $(this).data('id');
                var $groupTopico = $('.group-topico[data-ref='+ topico_id +']');
                var numero_pergunta = $groupTopico.children().eq(2).children().children().eq(1).children('.group-pergunta').length;
                var pergunta = templatePergunta(topico_id, numero_pergunta);
                $groupTopico.children().eq(2).children().children().eq(1).children('.group-pergunta').last().after(pergunta)
                startMaxlength();
            }).on('click', '.remover-pergunta', function(e){
                e.preventDefault();
                $(this).parent().parent().remove();
                swal('Pergunta removida', '', 'success');
            }).on('click', '.remover-topico', function(e){
                e.preventDefault();
                $(this).parent().parent().remove();
                swal('Tópico removido', '', 'success');
            });

            $('.add-topico').click(function(e){
                e.preventDefault();
                current_topico_id++;
                var topico_id = current_topico_id;
                $('.group-topico').last().after(templateTopico(topico_id));
                startMaxlength();
            });
        });
    </script>
@endsection