@extends('layouts.portal-franqueado-full-width')

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
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Realizar Visita
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row" id="waiting">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div class="box-header with-border text-center">
                    <h3 class="box-title">
                        Carregando módulo...
                    </h3>
                </div>
                <div class="box-body">
                    <h2>Carregnado módulo...</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="root" style="display: none;">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div class="box-header with-border text-center">
                    <h3 class="box-title">Aplicação de Questionário</h3>
                </div>
                {!! Form::open(['id' => 'form-visita','url' => route('admin.consultoria-de-campo.visitas.update', $item->id), 'method' => 'put', 'files' => true]) !!}
                {!! Form::hidden('form_id_to_preview', $item->id) !!}
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
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $topico->id }}" style="text-transform: uppercase">
                                                    {{ $topico->descricao }}
                                                </a>
                                            </h4>
                                        </div>
                                        <div class="col-xs-1 text-right" style="line-height: 30px;padding-right: 0;">Score: </div>
                                        <div class="col-xs-4">
                                            <div id="progress-{{ $topico->id }}" class="progress" style="margin-bottom: 0;border-radius: 6px; height: 30px;" data-total="{{ $topico->perguntas->count() }}">
                                                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0;">
                                                    <span class="progress-bar-label" style="font-weight: bold;line-height: 30px;">0%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapse-{{ $topico->id }}" class="panel-collapse collapse {{ $i == 0 ? 'in' : '' }}">
                                    <div class="box-body">
                                        @foreach($topico->perguntas as $j => $pergunta)
                                            <div class="form-group {{ $errors->has('resposta.' . $pergunta->id . '.') ? 'has-error' : '' }}">
                                                <div class="form-group">
                                                    <div style="text-indent: 50px;">{{ $i + 1 }}.{{ $j + 1 }}  {{ $pergunta->pergunta }}</div>
                                                    <div class="row" style="
                                                    border-bottom: 1px dashed #ccc;
                                                    margin-right: 0;
                                                    margin-left: 0;
                                                    padding-bottom: 10px;
                                                    ">
                                                        <div class="col-xs-6" style="padding-left: 50px;">
                                                            <label for="resposta-{{ $pergunta->id }}-nao-avaliado">Não Avaliado</label>
                                                            <input data-modal="{{ $pergunta->id }}" class="checkbox remove-required" type="radio" name="resposta[{{ $pergunta->id }}]" id="resposta-{{ $pergunta->id }}-nao-avaliado" value="null" data-target="{{ $topico->id }}" required checked>
                                                            <label for="resposta-{{ $pergunta->id }}-sim">Sim</label>
                                                            <input data-modal="{{ $pergunta->id }}" class="checkbox remove-required" type="radio" name="resposta[{{ $pergunta->id }}]" id="resposta-{{ $pergunta->id }}-sim" value="1" data-target="{{ $topico->id }}" required>
                                                            <label for="resposta-{{ $pergunta->id }}-nao">Não</label>
                                                            <input data-modal="{{ $pergunta->id }}" class="checkbox open-modal-acao-corretiva" type="radio" name="resposta[{{ $pergunta->id }}]" id="resposta-{{ $pergunta->id }}-nao" value="0" data-target="{{ $topico->id }}" required>
                                                            <i class="fa fa-camera fa-2x" style="position: relative; color: #888d96;cursor: pointer; top: 8px; font-size: 30px;">
                                                                <div class="" style="opacity: 0;position: absolute; top: 0;width: 30px;overflow: hidden;">
                                                                    {!! Form::file('upload_pergunta['. $pergunta->id .']', ['onchange' => 'update_preview(this,'. $pergunta->id .')', 'style' => 'cursor:pointer;']) !!}
                                                                </div>
                                                            </i>
                                                        </div>
                                                        <div class="col-xs-6" data-target="{{ $pergunta->id }}">

                                                        </div>
                                                        @include($views_path . 'modals.acao-carretiva', ['pergunta' => $pergunta])
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group {{ $errors->has('relato_final') ? 'has-error' : '' }}">
                        {!! Form::label('relato_final', 'Relato final da visita') !!}
                        {!! Form::textarea('relato_final' , null, ['class' => 'form-control', 'required']) !!}
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
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody id="notificacoes-list">

                                </tbody>
                            </table>
                            <div class="text-center" style="padding: 10px;">
                                <a href="#" class="btn btn-default btn-flat nova-notificacao" style="border-radius: 6px;"><i class="fa fa-plus"></i> Adicionar notificação</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    {!! link_to(route('admin.consultoria-de-campo'), 'Voltar', ['class' => 'btn btn-flat btn-danger']) !!}
                    <a href="#" class="swal-step1 btn btn-flat btn-primary pull-right">Finalizar Visita</a>
                    {!! Form::submit('Finalizar visita', ['id' => 'btn-submit','class' => 'hide']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @include($views_path . 'modals.nova-notificacao')
    <div class="modal modal-preview fade">
        <div class="modal-dialog" style="width: 650px; height: auto;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Preview da visita</h4>
                </div>
                <div class="modal-body modal-preview-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left btn-flat" style="width: 100%" data-dismiss="modal">Editar formulário</button>
                    <br>
                    <button type="button" class="btn btn-primary btn-flat swal-step2" style="width: 100%">Finalizar e enviar visita</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function update_preview(input, target)
        {
            if(input.files)
            {
                var reader = new FileReader();
                $(input).attr('style', 'color: green');
                reader.onload = function(e) {
                    $('[data-target='+ target + ']').html('<img class="img-responsive img-thumbnail" src="' + e.target.result +'" style="width: 50px; height: 50px;">');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function atualizarProgressBar(target, pontuacao)
        {
            var $target = $('#progress-' + target);
            var total_perguntas = $target.data('total');
            var total_pontos = 0;
            $('[data-target=' + target + ']:checked').each(function(i, input){
                if($(input).val() == 1)
                {
                    total_pontos++;
                }
            });
            var porcentagem = (total_pontos / total_perguntas) * 100;
            $target.children().children().html(porcentagem.toFixed(2) + '%');
            $bar = $target.children();
            $bar.attr('style', 'width:' + porcentagem + '%');
            $bar.removeClass('progress-bar-danger').removeClass('progress-bar-warning').removeClass('progress-bar-success');

            if(porcentagem <= 30)
            {
                $bar.addClass('progress-bar-danger');
            }
            else if (porcentagem > 30 && porcentagem < 70)
            {
                $bar.addClass('progress-bar-warning');
            }
            else
            {
                $bar.addClass('progress-bar-success');
            }
        }

        $(function(){

            $('.overlay').hide();
            $('#root').slideDown();
            $('#waiting').slideUp();

            $('.checkbox').checkboxradio({
                icon: false
            }).change(function(input){
                var $target = $(input.target);
                var id = $target.attr('id');
                var target = $target.data('target');
                if(this.value == 1)
                {
                    atualizarProgressBar(target, 1);
                    $('label[for=' + id + ']').addClass('btn-success');
                }
                else
                {
                    atualizarProgressBar(target, 0);
                    $('label[for=' + id + ']').addClass('btn-danger');
                }
            });

            $('.ui-button').addClass('btn btn-default btn-flat');

            $('.nova-notificacao').click(function(){

            });

            $('.swal-step1').click(function(e){
                e.preventDefault();
                swal({
                        title: "Atenção",
                        text: "Antes de finalizar o formulário preenchido, verifique com atenção novamente todas as respostas, leia novamente toda a parte escrita para ter certeza que as informações contidas no seu preenchimento estão corretas",
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            url = "{{ route('admin.consultoria-de-campo.visitas.preview') }}?"+$('#form-visita').serialize();
                            $.get(url, function(data){
                                $('.modal-preview-body').html('<iframe style="margin:0; padding:0; border:0; width: 100%; height: 100%; display: block;" src="'+ url +'">');
                                $('.modal-preview').modal();
                                $('.modal-preview-body').css('height',$( window ).height()*0.8);
                            });
                        } else {
                            return false;
                        }
                    });
                //swal('Atenção!', 'Antes de finalizar o formulário preenchido, verifique com atenção novamente todas as respostas, leia novamente toda a parte escrita para ter certeza que as informações contidas no seu preenchimento estão corretas', 'warning');
            });

            $('.swal-step2').click(function(e){
                e.preventDefault();
                swal({
                    title: "IMPORTANTE!",
                    text: "após o envio ao franqueado não poderá ser modificada nenhuma informação contida nesse formulário",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ciente, enviar",
                    cancelButtonText: "Revisar novamente",
                    closeOnConfirm: true
                }, function(isConfirm){
                    if(isConfirm){
                        $('#btn-submit').trigger('click');
                    } else {
                        $('.modal-preview').modal('hide');
                    }

                });
            });
        });
    </script>
@endsection