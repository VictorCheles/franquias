<?php
$auth = Auth::user();
$franquia = $auth->lojas()->first();
if (Request::get('loja_id')) {
    $franquia = $auth->lojas()->where('id', Request::get('loja_id'))->first();
}
?>

@section('extra_styles')
    <style>
        .btn.filter
        {
            margin-bottom: 4px;
        }
        .lineup
        {
            text-decoration: line-through;
            opacity: 0.3;
        }
    </style>
@endsection

@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Pedido
                </h1>
            </div>
        </section>
    </div>
    @if(Request::get('loja_id'))
        <div class="row text-center" style="padding: 10px 0;">
            <a class="btn btn-app" href="{{ url()->current() . '?' . http_build_query(['loja_id' => Request::get('loja_id')]) }}">
                <i class="fa fa-check"></i> Fazer pedido do zero
            </a>
            @if($pedidos->count() > 0)
                <a class="btn btn-app" href="{{ url()->current() . '?' . http_build_query(['loja_id' => Request::get('loja_id'), 'media' => true]) }}">
                    <i class="fa fa-area-chart"></i> Pedido baseado na média
                </a>
            @endif
            @if($ultimoPedido)
                <a class="btn btn-app" href="{{ url()->current() . '?' . http_build_query(['loja_id' => Request::get('loja_id'), 'ultimo' => $ultimoPedido->id]) }}">
                    <i class="fa fa-fast-backward"></i> Repetir pedido anterior
                </a>
            @endif
        </div>
        <div class="box box-danger box-solid">
            <div class="box-header" style="padding: 0;">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <div style="padding: 10px 0 5px 0;">DATA LIMITE PARA REALIZAR PEDIDO</div>
                        <h3 style="padding: 5px; margin: 0;">{{ $franquia->data_limite->format('d/m/Y \a\s H:i') }}</h3>
                    </div>
                    <div class="col-sm-6 text-center">
                        <div style="padding: 10px 0 5px 0;">TEMPO RESTANTE</div>
                        <h3 style="padding: 5px; margin: 0;" id="getting-started"></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="padding: 10px 0;">
            <div class="col-sm-12">
                <button class="filter btn btn-flat btn-black" data-filter="*">Todos</button>
                @foreach(App\Models\CategoriaProduto::disponivel()->orderBy('nome')->get() as $cat)
                    <button class="filter btn btn-flat btn-black" data-filter=".category-{{ $cat->id }}">{{ $cat->nome }}</button>
                @endforeach
            </div>
        </div>
    @endif
    {!! Form::open(['url' => route('pedido.store'), 'id' => 'finalizar-pedido']) !!}
        <div class="form-group {{ $errors->has('loja_id') ? 'has-error' : '' }}">
            {!! Form::label('loja_id', 'Selecione uma Loja') !!}
            {!! Form::select('loja_id' , $auth->lojas()->pluck('nome', 'id') , Request::get('loja_id'), ['id' => 'loja_id', 'placeholder' => 'Selecione uma opção','class' => 'form-control', 'required']) !!}
        </div>
        @if(Request::get('loja_id'))
            <div id="mixitup-container" class="row">
                @if($lista->count() > 0)
                    @foreach($lista as $item)
                        <div class="col-xs-12 col-lg-3 mix category-{{ $item->categoria->id }}">
                            <div class="box box-black box-solid">
                                <div class="box-header text-center" style="height: 150px; overflow: hidden">
                                    <a class="colorbox" href="{{ $item->img }}">
                                        <img class="img-responsive" src="{{ $item->img }}">
                                    </a>
                                </div>
                                <div class="box-header text-center" style="height: 80px;">
                                    <h4>{{ $item->nome }}</h4>
                                </div>
                                <div class="box-body text-center" style="height: 60px;">
                                    {!! $item->descricao !!}
                                </div>
                                <div class="box-body text-right">
                                    <b style="font-size: 11px;">UND: {{ maskMoney($item->preco) }}</b>
                                </div>
                                <div class="box-body text-right">
                                    <div class="row">
                                        <div class="col-sm-6 border-right">
                                            <div class="description-block">
                                                <span class="description-text">Quantidade</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header">{!! Form::number("quantidade[{$item->id}]", isset($produtos[$item->id]) ? $produtos[$item->id]['media_quantidade'] : 0, ['min' => 0,'class' => 'input-preco form-control','data-target' => $item->id, 'data-preco' => $item->preco]) !!}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body text-center">
                                    <div class="col-sm-12">
                                        <div class="description-block">
                                            <h5 class="description-header text-center subtotal" valor="0" id="preco-target-{{ $item->id }}">R$ 0,00</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered">
                        <tr id="objetivo">
                            <td colspan="5" style="text-align: right">Valor mínimo <span>{{ maskMoney($franquia->valor_minimo_pedido) }}</span></td>
                        </tr>
                        <tr id="faltam">
                            <td colspan="5" style="text-align: right">Faltam <span>{{ maskMoney($franquia->valor_minimo_pedido) }}</span></td>
                        </tr>
                        @if($franquia->pedidoDentroDoPrazo())
                            <tr id="total">
                                <td colspan="5" style="text-align: right; font-size: 15px; "><b>Total</b> <span><b>R$ 0,00</b></span></td>
                            </tr>
                        @else
                            <tr id="multa" style="text-align: right" data-multa="{{ env('PEDIDO_ATRAZADO_MULTA', 300.00) }}">
                                <td>Multa <span>{{ maskMoney(env('PEDIDO_ATRAZADO_MULTA', 300.00)) }}</span></td>
                            </tr>
                            <tr id="total">
                                <td colspan="5" style="text-align: right; font-size: 15px; "><b>Total</b> <span><b>{{ maskMoney(env('PEDIDO_ATRAZADO_MULTA', 300.00)) }}</b></span></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        @endif
    @if(Request::get('loja_id'))
    <div class="">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="form-group {{ $errors->has('loja_id') ? 'has-error' : '' }}">
                    {!! Form::label('observacoes', 'Observações') !!}
                    {!! Form::textarea('observacoes' , '', ['class' => 'form-control']) !!}
                </div>
                {!! link_to('#', 'FINALIZAR PEDIDO', ['class' => 'btn btn-flat btn-success btn-lg hide fake-submit']) !!}
                {!! link_to('#', 'Seu pedido não atingiu o valor mínimo', ['class' => 'btn_valor_minimo btn btn-flat btn btn-flat btn-danger btn-lg disabled']) !!}
            </div>
        </div>
    </div>
    @endif
    {!! Form::close() !!}
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('form').bind("keypress", function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $('#loja_id').change(function(){
                window.location.href="{{ route('pedido.create') }}?loja_id=" + $(this).val();
            });

            $('.fake-submit').click(function(e)
            {
                e.preventDefault();
                swal({
                    title: "Finalizar pedido?",
                    text: "Ao finalizar, seu pedido será registrado no sistema.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Finalizar",
                    cancelButtonText: "Ainda não terminei"
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            $('#finalizar-pedido').submit();
                        }
                    }
                );
            });


            $('a.colorbox').colorbox({
                rel: 'colorbox',
                width: '50%'
            });
            $('#mixitup-container').mixItUp();
            var valor_minimo = parseFloat('{{ $franquia->valor_minimo_pedido }}');

            $('.input-preco').change(function(){
                var multa = 0;
                if($('#multa').length)
                {
                    multa = parseFloat($('#multa').data('multa'));
                }
                var total = multa;
                var multiplicador = parseInt($(this).val());
                var target = $('#preco-target-' + $(this).data('target'));
                var preco = parseFloat($(this).data('preco'));
                if(multiplicador > 0)
                {
                    valor = preco * multiplicador;
                    total += valor;
                    $(target).attr('valor', valor);
                    valor = valor.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                    $(target).html('R$ '+ valor);
                }
                else
                {
                    $(target).attr('valor', 0.0);
                    $(target).html('R$ 0,00');
                }

                var blau = 0.0;
                $('.subtotal').each(function(){
                    blau += parseFloat($(this).attr('valor'));
                });

                var faltam = valor_minimo - blau;
                $('#total td span').html('<b>R$ '+ (blau + multa).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') +'</b>');

                if(faltam <= 0)
                {
                    $('#faltam td span').html('R$ 0,00');
                }
                else
                {
                    $('#faltam td span').html('R$ '+ faltam.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
                }

                if(blau >= valor_minimo)
                {
                    $('.fake-submit').removeClass('hide');
                    $('.btn_valor_minimo').addClass('hide');
                }
                else
                {
                    $('.fake-submit').addClass('hide');
                    $('.btn_valor_minimo').removeClass('hide');
                }
            });

            var count_date = "{{ $franquia->data_limite->format('Y/m/d H:i') }}";
            var extra_time = "{{ $franquia->data_limite->addMinutes(15)->format('Y/m/d H:i') }}";
            var has_extra_time = true;
            $("#getting-started").countdown(count_date, function(event) {
                $(this).text(
                    event.strftime('%D dias, %Hh %Mmin %S')
                );
            }).on('update.countdown', function(diz){

            }).on('finish.countdown', function(diz){
                if(has_extra_time)
                {
                    has_extra_time = false;
                    $("#getting-started").countdown(extra_time);
                    swal('Você excedeu o prazo limite', 'Você tem uma tolerância de 15min após o termino do prazo final. seja rapido!');
                }
                else
                {
                    window.location.reload();
                }
            });

            $('.input-preco').trigger('change');
        });
    </script>
    @if(Request::get('ultimo') or Request::get('media'))
        @include('portal-franqueado.admin.pedido.modals.preview-pedido')
    @endif
@endsection