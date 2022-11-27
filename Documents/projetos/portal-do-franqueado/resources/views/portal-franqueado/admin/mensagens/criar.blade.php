@extends('portal-franqueado.admin.mensagens.layout')

@section('message-action')
    <a href="{{ route('admin.mensagens') }}" class="btn btn-danger btn-block margin-bottom">Voltar para a caixa de entrada</a>
@endsection
@section('mail-content')
        <div class="col-md-9">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Escrever nova mensagem</h3>
                </div>
                <div class="box-body">
                    {!! Form::open(['url' => route('admin.mensagens.store'), 'id' => 'criar_mensagem', 'files' => true]) !!}
                    <div class="form-group {{ $errors->has('to_id') ? 'has-error' : '' }}">
                        @if($response)
                            {!! Form::hidden('to_id', $response->from_id) !!}
                            {!! Form::text('shablim', $response->from->nome, ['class' => 'form-control', 'disabled' => 'disabled', 'readonly' => 'readonly']) !!}
                        @else
                            {!! Form::select('to_id', $users->pluck('nome', 'id')->toArray(), $response ? $response->to_id : null, ['placeholder' => 'Selecione um destinatário','class' => 'form-control select2', 'required']) !!}
                        @endif

                    </div>
                    <div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
                        {!! Form::text('subject' , $response ? 'RE: ' . $response->subject : '' , ['class' => 'form-control', 'required', 'maxlength' => 255, 'placeholder' => 'Assunto:']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::textarea('text' , $response ? '<br>...' . $response->text : null, ['class' => 'form-control','rows' => 5]) !!}
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            {!! Form::label('attachments', 'Anexos') !!}
                            {!! Form::file('attachments[]', ['multiple' => true]) !!}
                        </div>
                    </div>
                    {{ Form::hidden('response_id', $response ? $response->id : '') }}
                    {{ Form::hidden('response_folder', $response ? $response->folder : '') }}
                    {{ Form::hidden('folder', '') }}
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary form-button" data-folder="{{ \App\Models\Mensagens\Mensagem::FOLDER_SENT }}"><i class="fa fa-envelope-o"></i> Enviar</button>
                    </div>
                    <a href="{{ route('admin.mensagens') }}" class="btn btn-default" ><i class="fa fa-times"></i> Descartar</a>
                </div>
            </div>
        </div>
@endsection
@section('extra_scripts')
    @parent
    <script src="//cdn.ckeditor.com/4.7.0/standard/ckeditor.js"></script>
    <script>
        $('.select2').select2({
            language: 'pt-BR'
        });
        $(function () {
            //Add text editor
            CKEDITOR.replace('text');

            $('.form-button').click(function (e) {
                e.preventDefault();
                $("input[name='folder']").val(this.getAttribute('data-folder'));
                $("#criar_mensagem").submit();
            })
        });

    </script>
@endsection