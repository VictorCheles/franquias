@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}">Enquete: {{ $item->nome }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if($usuario_respostas->count() > 0)
                @foreach($item->perguntas as $pergunta)
                    <div class="box box-black box-solid">
                        <div class="box-header">
                            <h3 class="box-title">{{ $pergunta->pergunta }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                @foreach($pergunta->respostas as $resposta)
                                    <div class="radio">
                                        <label>
                                            <input disabled type="radio" {{ is_int($usuario_respostas->search($resposta->id)) ? 'checked' : '' }}>
                                            {{ $resposta->resposta }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {!! Form::open(['url' => route('enquetes.processar.resposta', $item->id), 'method' => 'put']) !!}
                @foreach($item->perguntas as $pergunta)
                    <div class="box box-black box-solid">
                        <div class="box-header">
                            <h3 class="box-title">{{ $pergunta->pergunta }}</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                @foreach($pergunta->respostas as $resposta)
                                    <div class="radio">
                                        <label>
                                            <input required name="resposta[{{ $pergunta->id }}]" id="esposta[{{ $pergunta->id }}]" value="{{ $resposta->id }}" type="radio">
                                            {{ $resposta->resposta }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="box box-black box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Finalizar</h3>
                    </div>
                    <div class="box-body text-center">
                        <b>Não esqueça de verificar se respondeu todas as perguntas</b>
                    </div>
                    <div class="box-footer">
                        {!! Form::submit('Enviar respostas', ['class' => 'btn btn-flat btn-primary']) !!}
                        {!! link_to(url('/'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            @endif
        </div>
    </div>
@endsection