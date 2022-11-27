<div class="row">
    <div class="col-xs-12">
        @if($usuario_respostas->count() > 0)
            <div class="box box-black box-solid">
                <div class="box-header text-center">
                    <h2>Enquete: {{ $item->enquete->nome }}</h2>
                </div>
                <div class="box-body">
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <strong>Aviso!</strong> Você já respondeu esta enquete
                    </div>
                    @foreach($item->enquete->perguntas as $pergunta)
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
                </div>
            </div>
        @else
            <div class="box box-black box-solid">
                <div class="box-header text-center">
                    <h2>Enquete: {{ $item->enquete->nome }}</h2>
                </div>
                <div class="box-body">
                    {!! Form::open(['url' => route('enquetes.processar.resposta', $item->enquete->id), 'method' => 'put']) !!}
                    {!! Form::hidden('url_back', url()->current()) !!}
                    @foreach($item->enquete->perguntas as $pergunta)
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
                        <div class="box-footer">
                            {!! Form::submit('Enviar respostas', ['class' => 'btn btn-flat btn-primary']) !!}
                            {!! link_to(url('/'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        @endif
    </div>
</div>