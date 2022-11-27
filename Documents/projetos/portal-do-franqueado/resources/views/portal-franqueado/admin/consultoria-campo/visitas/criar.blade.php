@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Cadastrar nova Visita
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
                    <h3 class="box-title">Visitas</h3>
                </div>
                {!! Form::open(['url' => route('admin.consultoria-de-campo.visitas.store'), 'class' => 'form-horizontal']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('loja_id') ? 'has-error' : '' }}">
                        <div class="col-sm-1 col-xs-3">
                            <img src="{{ asset('images/icon-bag.png') }}" class="img-responsive">
                        </div>
                        <div class="col-sm-11 col-xs-9" style="padding-top: 20px;">
                            {!! Form::select('loja_id' , $lojas->pluck('nome', 'id')->toArray(), null, ['placeholder' => 'Selecione uma Loja', 'class' => 'form-control select2', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                        <div class="col-sm-1 col-xs-3">
                            <img src="{{ asset('images/icon-people.png') }}" class="img-responsive">
                        </div>
                        <div class="col-sm-11 col-xs-9" style="padding-top: 20px;">
                            {!! Form::select('user_id' , $users->pluck('nome', 'id')->toArray() , null, ['placeholder' => 'Selecione um consultor', 'class' => 'form-control select2', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group {{ $errors->has('formulario_id') ? 'has-error' : '' }}">
                        <div class="col-sm-1 col-xs-3">
                            <img src="{{ asset('images/icon-form.png') }}" class="img-responsive">
                        </div>
                        <div class="col-sm-11 col-xs-9" style="padding-top: 20px;">
                            {!! Form::select('formulario_id' , $formularios->pluck('descricao', 'id')->toArray() , null, ['placeholder' => 'Selecione um Formulário', 'class' => 'form-control select2', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group {{ $errors->has('data') ? 'has-error' : '' }}">
                        <div class="col-sm-1 col-xs-3">
                            <img src="{{ asset('images/calendar.svg') }}" class="img-responsive">
                        </div>
                        <div class="col-sm-11 col-xs-9" style="padding-top: 20px;">
                            {!! Form::text('data' , null, ['placeholder' => 'Selecione uma data', 'class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group {{ $errors->has('iniciar') ? 'has-error' : '' }}">
                        <div class="col-sm-1 col-xs-3">
                            <i class="fa fa-check" style="font-size: 72px;"></i>
                        </div>
                        <div class="col-sm-11 col-xs-9" style="padding-top: 20px;">
                            {!! Form::select('iniciar' , [true => 'Iniciar visita agora', false => 'Apenas cadastrar visita'] , null, ['placeholder' => 'Selecione uma opção', 'class' => 'form-control select2', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.consultoria-de-campo'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Finalizar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
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
            $('.select2').select2();
            $('.maskMoney').maskMoney({
                allowZero: true
            });
        });
    </script>
@endsection