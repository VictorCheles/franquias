@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Módulo de Metas - Editar Meta - {{ $item->titulo }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row" id="root">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Meta</h3>
                </div>
                {!! Form::open(['method' => 'put', 'url' => route('admin.modulo-de-metas.metas.update', $item->id)]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
                        {!! Form::label('titulo', 'Título') !!}
                        {!! Form::text('titulo' , $item->titulo, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('inicio', 'Período da meta') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            {!! Form::text('periodo_meta' , $item->periodo, ['class' => 'form-control range', 'required']) !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('metrica') ? 'has-error' : '' }}">
                        {!! Form::label('metrica', 'Métrica') !!}
                        {!! Form::select('metrica' , $metricas , $item->metrica , ['placeholder' => 'Selecione uma métrica','class' => 'form-control select2', 'required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('valor') ? 'has-error' : '' }}">
                        {!! Form::label('valor', 'Valor') !!}
                        {!! Form::text('valor' , $item->valor, ['class' => 'form-control', 'required', 'maxlength' => 100]) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.modulo-de-metas.metas.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
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
            $('.range').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endsection