<div class="modal modal-enquete-dashboard fade">
    <div class="modal-dialog">
        {!! Form::open(['url' => route('enquetes.processar.resposta', $enquete->id), 'method' => 'put']) !!}
        {!! Form::hidden('url_back', url()->current()) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Enquete: {{ $enquete->nome }}</h4>
                </div>
                <div class="modal-body">
                    @foreach($enquete->perguntas as $pergunta)
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
                </div>
                <div class="modal-footer">
                    {!! Form::submit('Enviar respostas', ['class' => 'btn btn-flat btn-info pull-left']) !!}
                    <button type="button" class="btn btn-flat btn-danger pull" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.modal-enquete-dashboard').modal();
        });
    </script>
@endsection