@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar Cliente
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário de Estabelecimentos</h3>
                </div>
                {!! Form::model($item, ['url' => route('clientes_loja.update', $item->id), 'method' => 'patch']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome completo') !!}
                        {!! Form::text('nome' , null , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::email('email' , null , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('telefone') ? 'has-error' : '' }}">
                        {!! Form::label('telefone', 'Telefone') !!}
                        {!! Form::text('telefone' , null , ['class' => 'form-control', 'data-mask' => '(99)99999-9999']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('estabelecimento_id') ? 'has-error' : '' }}">
                        {!! Form::label('estabelecimento_id', 'Estabelecimento') !!}
                        @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_CRIAR]))
                            <small><a href="#" class="open-model-estabelecimento"><i class="fa fa-plus"></i> Adicionar estabelecimento</a></small>
                        @endif
                        {!! Form::select('estabelecimento_id' , $estabelecimentos , $item->estabelecimento_id , ['placeholder' => 'Selecione um estabelecimento','class' => 'form-control select2', 'required']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('clientes_loja_estabelecimento.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_CRIAR]))
        @include('portal-franqueado.admin.cliente-loja.cliente.modals.form-estabelecimento')
    @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.select2').select2();
        });
    </script>
@endsection