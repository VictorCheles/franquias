@extends('layouts.portal-franqueado')

@section('extra_styles')
    @parent
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .width48 .ui-checkboxradio-label
        {
            width: 48%;
        }
        .width100
        {
            height: 150px;
        }
        .width100 .ui-checkboxradio-label
        {
            width: 100%;
            display: block;
        }
        .slider-for
        {
            overflow: hidden;
            max-height: 265px;
        }
        .slider-nav .slick-track
        {
            max-height: 80px;
            overflow: hidden;
        }
        .slider-thumbnail
        {
            width: 70px;
            height: 70px;
        }
        .border-selected
        {
            border: 1px solid #000;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar Comunicado - {{ $item->titulo }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::model($item ,['id' => 'comunicado_form','url' => url()->current(), 'files' => true]) !!}
                <div class="box-body">
                    @if(!$item->imagem_id)
                        <div class="row">
                            <div class="col-xs-12">
                                    <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                                        <label>Imagem enviada através de upload</label>
                                        <br>
                                        <img width="30%" src="{{ $item->img }}">
                                    </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            @if($imagens_padrao->count() > 0)
                                <div class="slider-for">
                                    <img src="https://placehold.it/529x238?text=Selecione uma imagem" class="img-responsive img-thumbnail" style="width: 529px; height: 238px;">
                                    @foreach($imagens_padrao as $imagem)
                                        <img src="{{ $imagem->thumbnail }}" class="img-responsive img-thumbnail" style="width: 529px; height: 238px;">
                                    @endforeach
                                </div>
                                <div class="slider-nav">
                                    <label>
                                        <img src="https://placehold.it/70x70?text=Selecione uma imagem" class="img-responsive img-thumbnail slider-thumbnail" data-id="{{ $imagem->id }}">
                                        <input style="display: none;" type="radio" name="imagem_id" value>
                                    </label>
                                    @foreach($imagens_padrao as $imagem)
                                        <label>
                                            <img src="{{ $imagem->thumbnail }}" class="img-thumbnail slider-thumbnail" data-id="{{ $imagem->id }}">
                                            @if($imagem->id == $item->imagem_id)
                                                <input style="display: none;" type="radio" name="imagem_id" value="{{ $imagem->id }}" checked>
                                            @else
                                                <input style="display: none;" type="radio" name="imagem_id" value="{{ $imagem->id }}">
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <img src="https://placehold.it/529x238?text=Nenhuma Imagem padrão cadastrada" class="img-responsive img-thumbnail">
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                                {!! Form::label('titulo', 'Título') !!}
                                {!! Form::text('titulo' , null , ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <label>Ambiente</label>
                            <div class="width48">
                                <label for="radio-3">Interno</label>
                                <input class="ambiente" type="radio" name="tipo_id" id="radio-3" value="{{ \App\Models\Comunicado::TIPO_INTERNO }}" data-color="#00c0ef" required {{ $item->tipo_id ==  \App\Models\Comunicado::TIPO_INTERNO ? 'checked' : ''}}>
                                <label for="radio-4">Externo</label>
                                <input class="ambiente" type="radio" name="tipo_id" id="radio-4" value="{{ \App\Models\Comunicado::TIPO_FRANQUEADO }}" data-color="#f39c12" required {{ $item->tipo_id ==  \App\Models\Comunicado::TIPO_FRANQUEADO ? 'checked' : ''}}>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                {!! Form::label('periodo_importancia', 'Período de importância') !!}
                                <div class="input-group">
                                    @if($hasImportante)
                                        <small>
                                            <i>
                                                Existe um comunicado importante em vigência<br>
                                                de {{ $hasImportante->inicio_importancia->format('d/m/Y') }} até {{ $hasImportante->fim_importancia->format('d/m/Y') }}<br>
                                                <a href="{{ url('/admin/comunicados/listar') . '?' . http_build_query(['filter' => ['titulo' => $hasImportante->titulo]]) }}">Ver comunicado</a>
                                            </i>
                                        </small>
                                    @else
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        {!! Form::text('periodo_importancia', $item->periodo_importancia , ['class' => 'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <label>Abrir para discussão</label>
                            <div class="width48">
                                <label for="radio-5">Sim</label>
                                <input class="check-discussao" type="radio" name="aberto_para_questionamento" id="radio-5" value="1" data-color="#007fff" required {{ $item->aberto_para_questionamento ? 'checked' : ''}}>
                                <label for="radio-6">Não</label>
                                <input class="check-discussao" type="radio" name="aberto_para_questionamento" id="radio-6" value="0" data-color="red" required {{ !$item->aberto_para_questionamento ? 'checked' : ''}}>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                {!! Form::label('periodo_acao', 'Marcar no calendário?') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('periodo_acao', $item->evento_calendario ? $item->evento_calendario->periodo : '' , ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('imagem', 'Upload de nova imagem') !!}
                                {!! Form::file('imagem') !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('galeria', 'Galeria') !!}
                                {!! Form::file('galeria[]', ['multiple' => true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3" style="margin-top: 23px;">
                            <div class="form-group">
                                <a href="#" class="btn btn-flat btn-default open-modal-destinatarios" style="width: 100%">Destinatários <span id="dest-count">({{ $item->destinatarios->count() }})</span></a>
                                @include('portal-franqueado.admin.comunicados.modals.form-destinatarios')
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3" style="margin-top: 23px;">
                            <div class="form-group {{ $errors->has('setor_id') ? 'has-error' : '' }}">
                                {!! Form::select('setor_id' , Auth::user()->setores->lists('nome', 'id') , null, ['placeholder' => 'Setor','class' => 'form-control select2', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('anexos', 'Anexos') !!}
                                {!! Form::file('anexos[]', ['multiple' => true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                                {!! Form::label('descricao', 'Comunicado') !!}
                                {!! Form::textarea('descricao' , null , ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('video') ? 'has-error' : '' }}">
                                {!! Form::label('videos', 'Vídeos (um por linha)') !!}
                                {!! Form::textarea('videos' , null, ['class' => 'form-control','rows' => 5]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <h3>Imagens</h3>
                    @if($item->galeria)
                        @foreach($item->galeria as $img)
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td>
                                            <img height="70" width="70" src="{{ asset('uploads/comunicados/'. $img) }}">
                                        </td>
                                        <td>
                                            {{ $img }}
                                        </td>
                                        <td>
                                            <a class="btn btn-flat btn-danger remover-imagem pull-right" data-id="{{ $item->id }}" data-file="{{ $img }}" data-stack="galeria">Remover imagem</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="box-body">
                    <h3>Anexos</h3>
                    @if($item->anexos)
                        @foreach($item->anexos as $anexo)
                            <div class="col-lg-12">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <td>
                                            <a target="_blank" href="{{ asset('uploads/comunicados/' . $anexo) }}">{{ $anexo }}</a>
                                        </td>
                                        <td>
                                            <a class="btn btn-flat btn-danger remover-imagem pull-right" data-id="{{ $item->id }}" data-file="{{ $anexo }}" data-stack="anexos">Remover anexo</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="box-footer">
                    {!! link_to('/admin/comunicados/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! link_to('#', 'Editar', ['class' => 'btn btn-flat btn-primary pull-right', 'id' => 'comunicado_fake_submit']) !!}
                </div>
                {!! Form::close() !!}
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script src="//cdn.ckeditor.com/4.7.0/standard/ckeditor.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function(){
            $('.overlay').hide();

            var por_loja = {!! $destinatarios_por_loja !!};
            var por_praca = {!! $destinatarios_por_praca !!};

            $('#comunicado_fake_submit').click(function(e)
            {
                e.preventDefault();
                var total_dest = 0;
                $('.checkboxdestinatarios').each(function(){
                    if($(this).is(':checked'))
                    {
                        total_dest++;
                    }
                });

                if(total_dest === 0)
                {
                    swal("0 destinatários", "Você deve selecionar ao menos um destinatário", "warning");
                }

                swal({
                        title: "Deseja finalizar?",
                        text: "Após confirmação, seu comunicado será publicado",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Publicar!",
                        cancelButtonText: "Continuar",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm)
                        {
                            $('#comunicado_form').submit();
                        }
                        else
                        {
                            swal("Continuando...", "Não esqueça de verificar se está tudo OK!", "warning");
                        }
                    });
            });

            $('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '.slider-nav'
            });

            $('.slider-nav').slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                asNavFor: '.slider-for',
                arrows: false,
                dots: true,
                centerMode: true,
                focusOnSelect: true
            });

            $('input[name=imagem_id]').change(function(){
                $('.slider-nav .slick-slide img').removeClass('border-selected');
                $(this).parent().children('img').addClass('border-selected');
            });


            $('.checkboxradio, .check-discussao, .ambiente').checkboxradio({
                icon: false
            }).change(function(input){
                var target = this;
                var color = $(target).data('color');
                var $buttom = $('label[for='+ $(target).attr('id') +']');
                $(this).parent().children('label').removeAttr('style');
                $buttom.attr('style', 'background:' + color + '; border-color:' + color);
            });

            $('.checkboxdestinatarios').checkboxradio({
                icon: false
            }).change(function(){
                var total = 0;
                $('.checkboxdestinatarios').each(function(){
                    if($(this).is(':checked'))
                    {
                        total++;
                    }
                });
                $('#dest-count').html('(' + total + ')');
            });

            $('#periodo_acao').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            @if(!$hasImportante)
                $('#periodo_importancia').daterangepicker({
                    locale: {
                        applyLabel: 'Selecionar',
                        cancelLabel: 'Cancelar',
                        format: 'DD/MM/YYYY',
                        language: 'pt-BR'
                    }
                }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            @endif

            $('.select2').select2();

            function generateList(list)
            {
                $('#dest-count').html('(0)');
                $('.checkboxdestinatarios').checkboxradio('destroy');
                var counter = 1;
                var check = 1;
                var $container = $('.row.row-destinatarios');
                $container.empty();
                $.each(list, function(loja, lista){
                    $container.append('<div class="col-xs-12 text-center"><h4>' + loja + '</h4><div class="checkbox"><label><input class="check-all" data-check="' + check + '" type="checkbox">Marcar/Desmarcar todos</label></div></div>');
                    $.each(lista, function(i, user){
                        $container.append('' +
                            '<div data-find="' + user.fake_name.replace('<br>', ' ') + '" class="col-xs-6 col-sm-2 width100">\
                                <label for="checkbox-' + counter + '">\
                                    <img src="' + user.thumbnail + '" class="img-responsive" style="max-height: 75px; margin: 0 auto;">\
                                    '+ user.nome +'\
                                </label>\
                                <input class="checkboxdestinatarios" data-parent="' + check + '" type="checkbox" name="destinatario[]" id="checkbox-' + counter + '" value="' + user.id + '">\
                            </div>');
                        counter++;
                    });
                    check++;
                });

                $('.checkboxdestinatarios').checkboxradio({
                    icon: false
                }).change(function(){
                    var total = 0;
                    $('.checkboxdestinatarios').each(function(){
                        if($(this).is(':checked'))
                        {
                            total++;
                        }
                    });
                    $('#dest-count').html('(' + total + ')');
                });;
            }

            $('.por-loja').click(function(e){
                e.preventDefault();
                generateList(por_loja);
            });

            $('.por-praca').click(function(e){
                e.preventDefault();
                generateList(por_praca);
            });

            $('#q').keyup(function () {
                var q = $(this).val();
                $('div[data-find]').each(function (i, e) {
                    var i = $(e),
                        f = i.data('find'),
                        r = new RegExp('.*' + q + '.*', 'gim');
                    if (r.test(f)) {
                        i.prop('hidden', false);
                    } else {
                        i.prop('hidden', true);
                    }
                });
            });

            $('form').on('change', '.check-all', function(){
                var checked = $(this).is(':checked');
                var $targets = $('[data-parent=' + $(this).data('check') + ']');
                $targets.each(function(i, elem){
                    if(checked && !$(elem).is(':checked'))
                    {
                        $(elem).trigger('click');
                    }

                    if(!checked && $(elem).is(':checked'))
                    {
                        $(elem).trigger('click');
                    }
                });
            });

            CKEDITOR.replace('descricao', {
                language: 'pt-br',
                disableNativeSpellChecker : false
            });

            var select2 = $('select#destinatario').select2();

            var imagem_id = parseInt('{{ $item->imagem_id }}');
            if(imagem_id)
            {
                $('.slider-thumbnail[data-id='+imagem_id+']').parent().trigger('click');
            }


            $('.remover-imagem').click(function(){
                var diz = $(this).parent().parent().parent().parent().remove();
                var data = {
                    id : $(this).data('id'),
                    file: $(this).data('file'),
                    stack: $(this).data('stack'),
                    '_token' : '{{ csrf_token() }}',
                    '_method': 'delete'
                };
                var mensagem = 'Imagem removida com sucesso';

                if(data.stack == 'anexos')
                {
                    mensagem = 'Anexo removido com sucesso';
                }
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.comunicado.excluir.imagem') }}',
                    data: data,
                    success: function(response){
                        if(response.success)
                        {
                            swal('Tudo certo!!', mensagem,'success');
                            diz.delete();
                        }
                        else
                        {
                            swal('Erro', response.message, 'error');
                        }

                    },
                    error: function(response){
                        swal('error');
                    }
                });
            });
        });
    </script>
@endsection