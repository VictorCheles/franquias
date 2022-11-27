@extends('layouts.portal-franqueado')

@section('extra_styles')
    @parent
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .width48 .ui-checkboxradio-label
        {
            width: 48%;
            border-radius: 0;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            border-width: 1px;
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
                {!! Form::open(['id' => 'comunicado_form','url' => url()->current(), 'files' => true]) !!}
                    @include('portal-franqueado.admin.comunicados.criar._imagem')
                    @include('portal-franqueado.admin.comunicados.criar._destinatario')
                    @include('portal-franqueado.admin.comunicados.criar._escrever')
                    <div class="box-footer">
                        {!! link_to('/admin/comunicados/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                        {!! link_to('#', 'Publicar', ['class' => 'btn btn-flat btn-primary pull-right', 'id' => 'comunicado_fake_submit']) !!}
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

            $('#periodo_acao').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            }).val('');

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
                }).val('');
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