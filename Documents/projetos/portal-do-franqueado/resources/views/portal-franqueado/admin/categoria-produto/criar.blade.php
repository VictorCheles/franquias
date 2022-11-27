@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Nova categoria
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
                {!! Form::open(['url' => route('admin.categoria.store')]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('disponivel') ? 'has-error' : '' }}">
                        {!! Form::label('disponivel', 'Categoria disponível?') !!}
                        {!! Form::select('disponivel' , \App\Models\CategoriaProduto::$mapDisponibilidade , '' , ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.categoria.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('select[name=disponivel]').change(function () {
                var diz = $(this);
                if ($(this).val() == 0)
                {
                    swal({
                        title: "Aviso!",
                        text: "Categoria não disponível também deixará seus produtos relacionados não listáveis para pedido",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Entendi!!",
                        cancelButtonText: "Cancelar",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm)
                        {

                        }
                        else
                        {
                            $(diz).val(1);
                        }
                    });
                }
            });
        });
    </script>
@endsection