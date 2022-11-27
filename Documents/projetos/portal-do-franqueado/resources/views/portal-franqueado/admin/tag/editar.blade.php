@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar Tag - {{ $item->titulo }}
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
                {!! Form::model($item ,['url' => route('admin.tag.update', $item->id), 'method' => 'put']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                        {!! Form::label('titulo', 'Título') !!}
                        {!! Form::text('titulo' , null , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cor') ? 'has-error' : '' }}">
                        {!! Form::label('cor', 'Cor') !!}
                        {!! Form::text('cor' , null , ['class' => 'form-control cpicker']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.tag.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger ']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.cpicker').colorpicker();
        });
    </script>
@endsection