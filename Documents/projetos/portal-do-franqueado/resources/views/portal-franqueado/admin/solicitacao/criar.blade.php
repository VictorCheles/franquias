@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Nova Solicitação
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['url' => route('solicitacao.store'), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('setor') ? 'has-error' : '' }}">
                        {!! Form::label('setor', 'Setor') !!}
                        {!! Form::select('setor' , $setores , null, ['placeholder' => 'Selecione um setor','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                        {!! Form::label('titulo', 'Título') !!}
                        {!! Form::text('titulo' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::textarea('descricao' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('anexos', 'Anexos') !!}
                        {!! Form::file('anexos[]' , ['multiple']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('solicitacao.index'), 'Cancelar', ['class' => 'btn btn-danger']) !!}
                    {!! Form::submit('Solicitar', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script src="//cdn.ckeditor.com/4.7.0/standard/ckeditor.js"></script>
    <script>
        $(function(){
            CKEDITOR.replace('descricao', {
                language: 'pt-br',
                disableNativeSpellChecker : false
            });
            $('.select2').select2({
                language: 'pt-BR'
            });
        });
    </script>
@endsection