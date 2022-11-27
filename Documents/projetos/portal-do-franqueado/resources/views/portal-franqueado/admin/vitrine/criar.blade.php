@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Nova Vitrine
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            @if($user->hasRoles([\App\ACL\Recurso::ADM_VITRINES_CRIAR]))
                <a href="{{ route('admin.vitrine.index') }}" class="btn btn-flat btn-app">
                    <i class="fa fa-arrow-left"></i> Voltar para lista
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
                {!! Form::open(['url' => route('admin.vitrine.store'), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('imagem') ? 'has-error' : '' }}">
                        <label for="imagem">Imagem
                            <small>(1110x206)</small>
                        </label>
                        {!! Form::file('imagem' , ['required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('link') ? 'has-error' : '' }}">
                        {!! Form::label('link', 'Link (url externa)') !!}
                        {!! Form::text('link' , '' , ['class' => 'form-control', 'placeholder' => 'https://google.com']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status' , \App\Models\Vitrine::$mapStatus , true , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('ordem') ? 'has-error' : '' }}">
                        {!! Form::label('ordem', 'Ordem') !!}
                        {!! Form::number('ordem', \App\Models\Vitrine::all()->max('ordem') + 1, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.vitrine.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection