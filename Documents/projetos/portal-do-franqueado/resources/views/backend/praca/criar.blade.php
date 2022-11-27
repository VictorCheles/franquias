@if(str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', Route::current()->domain()))
    <?php $layout = 'layouts.portal-franqueado'; $box = 'box-black'; ?>
@else
    <?php $layout = 'layouts.app'; $box = 'box-danger';?>
@endif

@extends($layout)

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Nova Praça
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
            <div class="box {{ $box }} box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => route('backend.praca.store'), 'method' => 'post']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('data_limite_pedido') ? 'has-error' : '' }}">
                        {!! Form::label('data_limite_pedido', 'Data limite para pedidos') !!}
                        {!! Form::text('data_limite_pedido' , '', ['class' => 'form-control default-datetimepicker']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('backend.praca.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection