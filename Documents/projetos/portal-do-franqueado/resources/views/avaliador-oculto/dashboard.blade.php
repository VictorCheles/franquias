@extends('layouts.app-avaliador-oculto')
@section('extra_styles')
    <style>

        /*  HEADER  */
        .header {
            display: flex;
            flex-direction: column;
        }

        h1, h2 {
            color: #7E2224;
            text-align: center;
        }

        h1 {
            display: inline-block;
            margin: 10px auto;
            padding-bottom: 16px;
            border-bottom: 3px solid #F7D373;
        }

        h2 {
            margin: 0 auto;
            padding: 10px 20px;
            border: 1px solid #F5CD5F;

            font-size: 18px;
            font-weight: normal;
        }

        .bold {
            font-weight: bold;
        }

        /* TABELA */
        .questions {
            width: 95%;
            margin: 15px auto;
            display: flex;
            flex-direction: column;
        }

        .question {
            width: 100%;
            padding: 10px;

            display: flex;
            flex-direction: row;
            align-items: center;

            border-bottom: 1px solid #E5EBF0;
        }

        .question:nth-child(even) {
            background-color: #F9F9F9;
        }

        span.text {
            margin: 20px;
            font-size: 12px;
            color: rgba(0,0,0, .67);
        }

        /* COUNTER */
        .counter {
            display: block;
            width: 28px; height: 28px;
            padding: 2px;

            background-color: #F6CD5F;
            border: 2px solid #E3B949;

            text-align: center;
            font-weight: bold;
        }


        /* Squares */
        .square.group {
            display: flex;
            flex: 1;
            justify-content: flex-end;
        }

        .square.icon {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 55px; height: 55px;
            margin-left: 3px;

            background-color: #A3A3A3;
            box-shadow: 1px 2px 1px rgba(0,0,0, .2);

            color: white;
            font-size: 30px;
        }

        .square.icon.green {
            background-color: #59B38D;
        }

        .square.icon.red {
            background-color: #DB6266;
        }
    </style>
@endsection
@section('content')
    @if(!$current)
        <div class="header group">
            <h1>Nenhuma missão para hoje!</h1>
        </div>
    @else
        <div class="header group">
            <h1>Sua missão hoje é:</h1>
            <h2>Avaliar a loja <span class="bold">{{ $loja->nome }}</span></h2>
        </div>
        {!! Form::open(['id' => 'submit-me', 'url' => route('responder.formulario'), 'files' => true]) !!}
            <input type="hidden" name="formulario_id" value="{{ $current->id }}">
            <input type="hidden" name="loja_id" value="{{ $loja->id }}">
            <div class="questions">
                @foreach($current->perguntas as $k => $pergunta)
                    @if($pergunta->tipo == \App\Models\AvaliadorOculto\Pergunta::TIPO_SIM_NAO)
                        <div class="question">
                            <div>
                                <span class="counter">{{ $k+1 }}</span>
                            </div>
                            <span class="text">
                                {{ $pergunta->pergunta }}
                            </span>
                            <div class="square group" data-ref="{{ $pergunta->id }}">
                                <div class="square icon {{ old('perguntas.' . $pergunta->id) == \App\Models\AvaliadorOculto\Resposta::SIM ? 'green' : '' }}" data-color="green">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    <input style="display: none;" {{ old('perguntas.' . $pergunta->id) == \App\Models\AvaliadorOculto\Resposta::SIM ? 'checked="checked"' : '' }} type="radio" name="perguntas[{{ $pergunta->id }}]" value="{{ \App\Models\AvaliadorOculto\Resposta::SIM }}" required>
                                </div>
                                <div class="square icon {{ old('perguntas.' . $pergunta->id) == \App\Models\AvaliadorOculto\Resposta::NAO ? 'red' : '' }}" data-color="red">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    <input style="display: none;" {{ old('perguntas.' . $pergunta->id) == \App\Models\AvaliadorOculto\Resposta::NAO ? 'checked="checked"' : '' }} type="radio" name="perguntas[{{ $pergunta->id }}]" value="{{ \App\Models\AvaliadorOculto\Resposta::NAO }}" required>
                                </div>
                            </div>
                        </div>
                    @elseif($pergunta->tipo == \App\Models\AvaliadorOculto\Pergunta::TIPO_SUBJETIVA)
                        <div class="question" style="flex-wrap: wrap;">
                            <div>
                                <span class="counter">{{ $k+1 }}</span>
                            </div>
                            <span class="text">
                                {{ $pergunta->pergunta }}
                            </span>
                            <div class="cont-textarea" style="width: 100%; flex-basis: 100%;">
                                {!! Form::textarea('perguntas[' . $pergunta->id . ']' , old('perguntas.' . $pergunta->id) , ['required' => true, 'class' => 'form-control', 'rows' => 5]) !!}
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="question" style="flex-wrap: wrap;">
                    <div>
                        <span class="counter">{{ $k+2 }}</span>
                    </div>
                    <span class="text">
                            Relato final
                        </span>
                    <div class="cont-textarea" style="width: 100%; flex-basis: 100%;">
                        {!! Form::textarea('observacoes' , old('observacoes') , ['required' => true, 'placeholder' => 'Sugestões / críticas', 'class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="question" style="flex-wrap: wrap; overflow: hidden; background: #f2f2f2; border-bottom: 3px solid #fff;">
                    <div style="width: 100%; margin-bottom: 5px;" class="text-center">
                        <img src="{{ asset('images/icon_falg.png') }}">
                        <br>
                        <b class="text-center" style="width: 100%;">Fachada Loja</b>
                    </div>
                    <div style="width: 100%;">
                        <p>
                            Tire uma foto da fachada externa da loja e faça o upload aqui!
                        </p>
                    </div>
                    <div class="cont-textarea" style="width: 100%; flex-basis: 100%;">
                        {!! Form::file('foto_loja' , ['id' => 'comprovante', 'required']) !!}
                    </div>
                </div>
                <div class="question" style="flex-wrap: wrap; overflow: hidden; background: #f2f2f2; border-bottom: 3px solid #fff;">
                    <div style="width: 100%; margin-bottom: 5px;" class="text-center">
                        <img src="{{ asset('images/icon_batata.png') }}">
                        <br>
                        <b class="text-center" style="width: 100%;">Foto do consumo</b>
                    </div>
                    <div style="width: 100%;">
                        <p>
                            Antes de devorar seu delicioso {{ env('APP_NAME') }} tire uma foto e faça o upload aqui
                        </p>
                    </div>
                    <div class="cont-textarea" style="width: 100%; flex-basis: 100%;">
                        {!! Form::file('foto_consumo' , ['id' => 'comprovante', 'required']) !!}
                    </div>
                </div>
                {{--<div class="question" style="flex-wrap: wrap; overflow: hidden; background: #f2f2f2; border-bottom: 3px solid #fff;">--}}
                    {{--<div style="width: 100%; margin-bottom: 5px;" class="text-center">--}}
                        {{--<img src="{{ asset('images/icon_calendar.png') }}">--}}
                        {{--<br>--}}
                        {{--<b class="text-center" style="width: 100%;">Upload do cupom fiscal</b>--}}
                    {{--</div>--}}
                    {{--<div style="width: 100%;">--}}
                        {{--<p>--}}
                            {{--Para concluir a sua visita é necessário que nos envie o cupom fiscal que comprova o consumo em nossa loja--}}
                            {{--esta etapa é muito importante para que seu ressarcimento aconteça sem maiores problemas--}}
                        {{--</p>--}}
                    {{--</div>--}}
                    {{--<div class="cont-textarea" style="width: 100%; flex-basis: 100%;">--}}
                        {{--{!! Form::file('comprovante' , ['id' => 'comprovante', 'required']) !!}--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-flat btn-primary" style="width: 100%;">Enviar</button>
            </div>
        {!! Form::close() !!}
        <div class="modal modal-filter fade">
            <div class="modal-dialog" style="width: 95% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title text-center">ATENÇÃO!</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <b>Vamos começar!</b>
                        </p>
                        <p>
                        
                            Preencha o formulário de avaliação com muita atenção, através de suas respostas tomaremos decisões importantes para uma evolução constante de nossos produtos
                            e serviço.
                        </p>
                        <p>
                            Ao final da avaliação, descreva um breve relato sobre a sua experiência.
                        </p>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('COMEÇAR', ['class' => 'btn btn-flat btn-success', 'data-dismiss' => 'modal', 'style' => 'width: 100%']) !!}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.modal-filter').modal();
            //$(':checkbox').checkboxpicker();

            $('.square.icon').click(function(){
                $(this).parent().children('.square.icon').each(function(i, chi){
                    $(chi).removeClass('green').removeClass('red');
                });

                var $square = $(this);
                var $input = $(this).children('input');
                var color = $square.data('color');

                $input.prop('checked', true);
                $square.addClass(color);
            });

//            $('.fake-submit').click(function(e){
//                e.preventDefault();
//
//                if(!$('#observacoes').val())
//                {
//                    swal('', 'Preencha o campo "Relato final"', 'warning');
//                }
//                else
//                {
//                    swal({
//                        title: "Obrigado por participar do nosso programa de Cliente Oculto",
//                        text: "",
//                        html:true,
//                        type: "success",
//                        showCancelButton: false,
//                        confirmButtonText: "Continuar",
//                        closeOnConfirm: false
//                    },
//                    function(isConfirm){
//                        if (isConfirm) {
//                            $('#submit-me').submit();
//                        } else {
//                            //swal("Cancelled", "Your imaginary file is safe :)", "error");
//                        }
//                    });
//                }
//            });
        });
    </script>
@endsection