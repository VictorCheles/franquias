@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Editar Notificação - {{ $item->descricao }}
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
                {!! Form::open(['method' => 'put', 'url' => route('admin.consultoria-de-campo.setup.notificacoes.update', $item->id)]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::text('descricao' , $item->descricao , ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('valor_un') ? 'has-error' : '' }}">
                        {!! Form::label('valor_un', 'Valor unitário') !!}
                        {!! Form::text('valor_un' , maskMoney($item->valor_un), ['class' => 'form-control maskMoney', 'data-affixes-stay' => 'true', 'data-prefix' => 'R$ ', 'data-thousands' => '.', 'data-decimal' => ',']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary']) !!}
                    {!! link_to(route('admin.consultoria-de-campo.setup.formularios.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
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
            $('.maskMoney').maskMoney();
        });
    </script>
@endsection