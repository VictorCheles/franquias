@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .group-topico {
            border-bottom: 1px dashed #ddd;
            padding: 10px 0;
        }
    </style>
@endsection

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Editar Ação corretiva - {{ $item->descricao }}
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
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['method' => 'put', 'url' => route('admin.consultoria-de-campo.acoes-corretivas.update', $item->id)]) !!}
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('loja', 'Loja') !!}
                        {!! Form::text('loja' , $item->visita->loja->nome , ['class' => 'form-control', 'disabled', 'readonly']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::text('descricao' , $item->descricao , ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('data_correcao') ? 'has-error' : '' }}">
                        {!! Form::label('data_correcao', 'Data de correção') !!}
                        {!! Form::text('data_correcao' , $item->data_correcao->format('Y-m-d') , ['class' => 'form-control date', 'required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', \App\Models\ConsultoriaCampo\AcaoCorretiva::$mapStatus, $item->status, ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.consultoria-de-campo.acoes-corretivas.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
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
            $('.date').datepicker({
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection