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
            height:150px;
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

        .stepwizard-step p {
            margin-top: 10px;
        }

        .stepwizard-row {
            display: table-row;
        }

        .stepwizard {
            display: table;
            width: 95%;
            position: relative;
        }

        .stepwizard-step button[disabled] {
            opacity: 1 !important;
            filter: alpha(opacity=100) !important;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-order: 0;

        }

        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }

        .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
        }
        .btn-confirm{
            margin-right: -46%;

        }
    </style>
@endsection

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> {{ $item ? 'Cópia do comunicado - ' . $item->titulo : 'Novo comunicado' }}
                </h1>
            </div>
        </section>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid" style="margin-left: -2%">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                <form>
                <b><h4 style="margin-left: 4%; margin-top: 2%;">Adicionar a imagem</h4></b>
                <hr/>

                {!! Form::open(['id' => 'comunicado_form','url' => url()->current(), 'files' => true]) !!}
                <div class="box-body">
                    <div class="row">
                    <div class="row setup-content">
                        <div class="col-md-6 col-md-11" style="margin-left: 60px; margin-top: 17px">
                            @if($imagens_padrao->count() > 0)
                                <div class="slider-for">
                                    <img src="https://placehold.it/529x238?text=Selecione uma imagem" class="img-responsive img-thumbnail">
                                    @foreach($imagens_padrao as $imagem)
                                        <img src="{{ $imagem->thumbnail }}" class="img-responsive img-thumbnail">
                                    @endforeach
                                </div>
                                <br/>
                                <div class="slider-nav">
                                    <label>
                                        <img src="https://placehold.it/70x70?text=Selecione uma imagem" class="img-responsive img-thumbnail slider-thumbnail" data-id="{{ $imagem->id }}">
                                        <input style="display: none;" type="radio" name="imagem_id" value>
                                    </label>
                                    @foreach($imagens_padrao as $imagem)
                                        <label>
                                            <img src="{{ $imagem->thumbnail }}" class="img-thumbnail slider-thumbnail" data-id="{{ $imagem->id }}">
                                            <input style="display: none;" type="radio" name="imagem_id" value="{{ $imagem->id }}">
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <img src="https://placehold.it/529x238?text=Nenhuma Imagem padrão cadastrada" class="img-responsive img-thumbnail">
                            @endif
                        </div>
                        <br/>

                    <div style="margin-left: 2%">
                        <div class="col-md-4" style="margin-top: -3px; margin-left: 5%" class="col-md-3">
                            <div class="form-group">
                            <i class="fa fa-file-image-o"></i>  {!! Form::label('imagem', 'Carregar nova imagem') !!}
                                {!! Form::file('imagem') !!}
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top: 6%; margin-left: 5%" class="col-md-3">
                            <div class="form-group">
                                <i class="fa fa-picture-o"></i> {!! Form::label('galeria', 'Carregar nova imagem para galeria') !!}
                                {!! Form::file('galeria[]', ['multiple' => true]) !!}
                            </div>
                        </div>
                         <div class="col-md-2" style="margin-top: 6%; margin-left: 5%" class="col-md-3">
                            <div class="form-group">
                                <i class="fa fa-paperclip"></i> {!! Form::label('anexos', 'Anexos(Ex: pdf, txt)') !!}
                                {!! Form::file('anexos[]', ['multiple' => true]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <b><h4 style="margin-left: 4%; margin-top: 4%;">Selecionar Destinatários</h4></b>
                <hr/>

                <div class="row setup-content" style="margin-left: 2%; width: 95%">
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
                                {!! Form::text('periodo_importancia', null , ['class' => 'form-control']) !!}
                        @endif
                        </form>
                        </div>
                    </div>
                </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                    {!! Form::label('data_inicio', 'Data de Inicio') !!}
                    <div class="input-group">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar">
                        </i>
                        </div>
                        <input class="form-control date-picker" id="date" name="date" type="text"/>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class='col-sm-3'>
                        <div class="form-group">
                        {!! Form::label('data_fim', 'Data de Termino') !!}
                            <div class="input-group">
                                <div class="input-group-addon">
                                <i class="fa fa-calendar">
                                </i>
                                </div>
                                <input class="form-control date-picker" id="date2" name="date" type="text"/>
                        </div>
                    </div>
                    </div>
                <div class="col-md-2 col-sm-2">
                    <label>Ambiente</label>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="#" class="btn btn-default" style="width: 100%" require>Interno</a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn btn-default" style="width: 100%" require>Externo</a>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row" style="margin-left: 22%;">
                        <div class="col-sm-4" style="margin-top: -3%;">
                        <label>Setor de onde está sendo enviado</label>
                            <div class="form-group {{ $errors->has('setor_id') ? 'has-error' : '' }}">
                                {!! Form::select('setor_id' , Auth::user()->setores->lists('nome', 'id') , $item ? $item->setor_id : null, ['placeholder' => 'Setor','class' => 'form-control select2', 'required']) !!}
                            </div>
                        </div>
                    <div class="col-sm-4" style="margin-top: -3%;">
                    <label>Destinatários do comunicado</label>
                        <div class="form-group">
                            <a href="#" class="btn btn-flat btn-default open-modal-destinatarios" style="width: 100%">Destinatários <span id="dest-count">(0)</span></a>
                                @include('portal-franqueado.admin.comunicados.modals.form-destinatarios')
                        </div>
                    </div>
                        <div class="row col-md-3" style="margin-top: -3%;">
                            <label>Abrir para discussão</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <a href="#" class="btn btn-default" style="width: 100%" require>Sim</a>
                                </div>
                                <div class="col-md-5">
                                    <a href="#" class="btn btn-default" style="width: 100%" require>Não</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <b><h4 style="margin-left: 2%; margin-top: 4%;">Escrever Comunicado</h4></b>
                    <hr style="margin-left: -2%;"/>
                        <div class="row setup-content" style="margin-left: 1%; width: 90%">
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                                {!! Form::label('titulo', 'Título') !!}
                                {!! Form::text('titulo' , $item ? $item->titulo : null , ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                                    {!! Form::label('descricao', 'Comunicado') !!}
                                    {!! Form::textarea('descricao' , $item ? $item->descricao : null , ['class' => 'form-control', 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('video') ? 'has-error' : '' }}">
                                    {!! Form::label('videos', 'Vídeos (um por linha)') !!}
                                    {!! Form::textarea('videos' , $item ? $item->videos : null, ['class' => 'form-control','rows' => 5]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-md-10" style="margin-left: -28%; margin-top: 5%">
                        {!! link_to('/admin/comunicados/listar', 'Cancelar', ['class' => 'btn btn-danger']) !!}
                        {!! link_to('#', 'Publicar', ['class' => 'btn btn-success pull-right btn-confirm', 'id' => 'comunicado_fake_submit']) !!}
                    </div>

                <!--{!! Form::close() !!}
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>-->

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

                if(total_dest == 0)
                {
                    swal("0 destinatários", "Você deve selecionar ao menos um destinatário", "warning");
                    return false;
                }

                swal({
                    title: "Deseja publicar?",
                    text: "Após confirmação, seu comunicado será publicado",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Publicar!",
                    cancelButtonText: "Ainda não terminei",
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
                $buttom.attr('style', 'background:' + color + ';border-color:' + color);
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

            //coisa velha

            var select2 = $('select#destinatario').select2();
        });
    </script>
<script>
	$(document).ready(function(){
		$('.date-picker').datepicker({
			dateFormat: 'dd/mm/yy',
            language: 'pt-BR',
			todayHighlight: true,
			autoclose: true
		});
	})
</script>
@endsection