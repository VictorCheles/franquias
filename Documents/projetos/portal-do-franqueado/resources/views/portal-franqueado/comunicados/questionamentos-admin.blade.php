{!! Form::open(['method' => 'put', 'url' => route('admin.comunicado.encerrar.assunto', $item->id)]) !!}
<div class="row">
    <div class="col-xs-12">
        <div class="form-group">
            @if($item->aberto_para_questionamento)
                {!! Form::submit('Encerrar assunto', ['class' => 'btn btn-flat btn-theme-padrao pull-right']) !!}
            @else
                {!! Form::submit('Abrir para discussão', ['class' => 'btn btn-flat btn-success pull-right']) !!}
            @endif
            <br style="clear: both;">
        </div>
    </div>
</div>
{!! Form::close() !!}
@if($questionamentos->count() > 0)
    @foreach($questionamentos as $user_id => $quests)
        <div class="box box-black box-solid">
            <div class="box-header">
                <i class="fa fa-comments-o"></i>
                <h3 class="box-title">
                    Questionamentos - {{ $quests->first()->user->nome }}
                </h3>
                @if($item->aberto_para_questionamento)
                    <h3 class="box-title pull-right" style="color: green;">
                        <i class="fa fa-check"></i>
                        Aberto para questionamentos/respostas
                    </h3>
                @else
                    <h3 class="box-title pull-right" style="color: red;">
                        <i class="fa fa-times"></i>
                        Assunto encerrado
                    </h3>
                @endif
            </div>
            <div class="box-body chat" id="chat-box">
                @foreach($quests as $questionamento)
                    @if($questionamento->respostas->count() > 0)
                        @foreach($questionamento->respostas as $resposta)
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="{{ $resposta->user->thumbnail }}">
                                    <span class="username">
                                    <a>{{ $resposta->user->nome }}</a>
                                </span>
                                    <span class="description">{{ $resposta->created_at->format('d/m/Y \- H:s') }}</span>
                                </div>
                                <!-- /.user-block -->
                                {!! $resposta->texto !!}
                                @if($resposta->anexos and count($resposta->anexos) > 0)
                                    <div class="item">
                                        <div class="attachment">
                                            <h4>Anexos:</h4>
                                            @foreach($resposta->anexos as $anexo)
                                                <p class="filename">
                                                    <a target="_blank" href="{{ asset('uploads/comunicados/respostas/' . $anexo) }}">{{ $anexo }}</a>
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ $questionamento->user->thumbnail }}">
                                <span class="username">
                                    <a>{{ $questionamento->user->nome }}</a>
                                </span>
                                <span class="description">{{ $questionamento->created_at->format('d/m/Y \- H:s') }}</span>
                            </div>
                            <!-- /.user-block -->
                            {!! $questionamento->texto !!}
                            @if($questionamento->anexos and count($questionamento->anexos) > 0)
                                <div class="item">
                                    <div class="attachment">
                                        <h4>Anexos:</h4>
                                        @foreach($questionamento->anexos as $anexo)
                                            <p class="filename">
                                                <a target="_blank" href="{{ asset('uploads/comunicados/respostas/' . $anexo) }}">{{ $anexo }}</a>
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                @endforeach
            </div>
            @if($item->aberto_para_questionamento)
                {!! Form::open(['id' => 'quest-form', 'url' => route('questionamento.admin.reply'), 'files' => true]) !!}
                {!! Form::hidden('questionamento_id', $quests->first()->id) !!}
                {!! Form::hidden('comunicado_id', $item->id) !!}
                <div class="box-footer">
                    <div class="form-group">
                        <div class="input-group {{ $errors->has('questionamento') ? 'has-error' : '' }}" style="width: 100%;">
                            {!! Form::textarea('questionamento', null, ['class' => 'form-control ck', 'placeholder' => 'Escreva aqui seu questionamento/resposta', 'rows' => 4]) !!}
                        </div>
                    </div>
                    <div class="form-group form-anexos">
                        <label>Anexos <a href="#" class="add-file-anexos"><i class="fa fa-plus"></i></a></label>
                    </div>
                    <div class="input-group form-anexos"></div>
                </div>
                <div class="box-footer">
                    {!! Form::submit('Enviar', ['class' => 'btn btn-flat btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            @endif
        </div>
    @endforeach
@else
    <div class="box box-black box-solid">
        <div class="box-header">
            <i class="fa fa-comments-o"></i>
            <h3 class="box-title">
                Não há respostas/questionamentos
            </h3>
        </div>
    </div>
@endif
@section('extra_scripts')
    @parent
    <script src="//cdn.ckeditor.com/4.7.0/standard/ckeditor.js"></script>
    <script>
        $(function(){
            /**
             * ANEXOS
             */
            var input_anexos = '<div class="input-group form-anexos">' +
                '<span class="input-group-addon"><a href="#" class="remove-me"><i class="fa fa-minus" title="remover" style="color: darkred;"></i></a></span>' +
                '<input type="file" name="anexos[]">' +
                '</div>';

            $('.add-file-anexos').click(function(e){
                e.preventDefault();
                $(this).parent().parent().last().after(input_anexos);
            });
            $('form').on('click', '.remove-me',function(e){
                e.preventDefault();
                $(this).parent().parent().remove();
            });

            $('.ck').each(function(){
                CKEDITOR.replace(this, {
                    language: 'pt-br',
                    disableNativeSpellChecker : false
                });
            });
        });
    </script>
@endsection