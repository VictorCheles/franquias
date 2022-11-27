@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar Vitrine
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_LISTAR]))
                <a href="{{ route('admin.vitrine.index') }}" class="btn btn-flat btn-app">
                    <i class="fa fa-arrow-left"></i> Voltar para lista
                </a>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_CRIAR]))
                <a href="{{ route('admin.vitrine.create') }}" class="btn btn-flat btn-app">
                    <i class="fa fa-plus"></i> Nova vitrine
                </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::model($item ,['url' => route('admin.vitrine.update', $item->id), 'files' => true, 'method' => 'put']) !!}
                <div class="box-body">
                    <div class="form-group">
                        <a href="{{ $item->img }}">
                            <img class="img-responsive" style="width: 50%" src="{{ $item->img }}">
                        </a>
                    </div>
                    <div class="form-group">
                        <label for="imagem">Imagem
                            <small>(1110x206)</small>
                        </label>
                        {!! Form::file('imagem') !!}
                    </div>
                    <div class="form-group {{ $errors->has('link') ? 'has-error' : '' }}">
                        {!! Form::label('link', 'Link (url externa)') !!}
                        {!! Form::text('link' , null, ['class' => 'form-control', 'placeholder' => 'https://google.com']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status' , \App\Models\Vitrine::$mapStatus , null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('ordem') ? 'has-error' : '' }}">
                        {!! Form::label('ordem', 'Ordem') !!}
                        {!! Form::number('ordem', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.vitrine.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection