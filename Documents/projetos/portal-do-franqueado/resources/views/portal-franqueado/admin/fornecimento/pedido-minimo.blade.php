@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Atualizar valor do pedido mínimo
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
                {!! Form::open(['url' => route('admin.fornecimento.pedidominimo.update')]) !!}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Loja</th>
                                <th>Valor mínimo do pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>
                                        <div class="form-group">
                                            {!! Form::text('valor_minimo_pedido[' . $item->id . ']' , maskMoney($item->valor_minimo_pedido) , ['class' => 'form-control maskMoney', 'data-affixes-stay' => 'true', 'data-prefix' => 'R$ ', 'data-thousands' => '.', 'data-decimal' => ',']) !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.fornecimento.pedidominimo.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Salvar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script type="text/javascript">
        $(function(){
            $('.maskMoney').maskMoney({
                allowZero: true
            });
        });
    </script>
@endsection