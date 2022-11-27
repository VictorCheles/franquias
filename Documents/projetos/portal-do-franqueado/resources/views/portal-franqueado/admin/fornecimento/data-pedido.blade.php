@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Atualizar data para Pedido
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['url' => route('admin.fornecimento.datapedido.update')]) !!}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Praça</th>
                                <th>Data limite para pedidos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::text('data_limite_pedido[' . $item->id . ']' , $item->data_limite_pedido ? \Carbon\Carbon::parse($item->data_limite_pedido)->format('Y-m-d H:i') : '' , ['class' => 'form-control default-datetimepicker', 'required']) !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.fornecimento.datapedido.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Salvar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection