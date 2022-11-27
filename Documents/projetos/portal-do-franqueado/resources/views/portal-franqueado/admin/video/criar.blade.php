@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Novo Vídeo
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
                {!! Form::open(['url' => route('admin.video.store')]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                        {!! Form::label('titulo', 'Título') !!}
                        {!! Form::text('titulo' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::textarea('descricao' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('tag') ? 'has-error' : '' }}">
                        {!! Form::label('tag', 'Tag') !!}
                        {!! Form::select('tag' , \App\Models\Tag::select('id', DB::raw("titulo || ' ' || cor as titulo_t"))->orderBy('titulo', 'asc')->lists('titulo_t', 'id') , null, ['placeholder' => 'Selecione uma tag','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                        {!! Form::label('url', 'URL') !!}
                        {!! Form::text('url' , '' , ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.video.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary']) !!}
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
        CKEDITOR.replace('descricao', {
            language: 'pt-br',
            disableNativeSpellChecker : false
        });

        $('.select2').select2({
            language: 'pt-BR',
            templateResult: function(state){
                var splits = state.text.split(' ');
                if(splits.length == 1)
                {
                    return state.text;
                }
                return $('<span class="label" style="background: '+ splits[1] +';">'+ splits[0] +'</span>');
            }
        });
    </script>
@endsection