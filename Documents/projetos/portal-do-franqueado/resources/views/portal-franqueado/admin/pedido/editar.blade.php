@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar Pedido -  #{{ $item->id . ' - ' . $item->loja()->first()->nome }}
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
                {!! Form::model($item ,['url' => route('admin.pedido.update', $item->id), 'method' => 'put', 'files' => true]) !!}
                <div class="box-body no-padding">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th>#</th>
                            <td>{{ $item->id }}</td>
                        </tr>
                        <tr>
                            <th>Loja</th>
                            <td>{{ $item->loja->nome }}</td>
                        </tr>
                        <tr>
                            <th>Produtos</th>
                            <td style="margin:0; padding: 0">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Valor unitário</th>
                                            <th>Quantidade</th>
                                            <th>Peso</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="target-new-products">
                                        @foreach($item->produtos as $produto)
                                            <tr>
                                                <td>{{ $produto->nome }}</td>
                                                <td>{{ maskMoney($produto['pivot']->preco) }}</td>
                                                <td width="15%">
                                                    <input min="0" type="number" class="form-control" data-id="{{ $produto->id }}" name="produto[{{ $produto->id }}]" value="{{ $produto['pivot']->quantidade }}">
                                                </td>
                                                <td>{{ $produto['pivot']->quantidade * $produto->peso }}kg</td>
                                                <td>{{ maskMoney($produto['pivot']->quantidade * $produto['pivot']->preco) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <a href="#" class="btn btn-flat btn-info pull-right open-modal-new-product" style="margin: 5px;">Adicionar produto</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Observações</th>
                            <td>{{ $item->observacoes }}</td>
                        </tr>
                        <tr>
                            <th>Multa</th>
                            <td>
                                @if($user->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_MULTA_EDITAR]))
                                    <div class="form-group {{ $errors->has('preco') ? 'has-error' : '' }}">
                                        {!! Form::text('multa' , maskMoney($item->multa) , ['class' => 'form-control maskMoney', 'data-affixes-stay' => 'true', 'data-prefix' => 'R$ ', 'data-thousands' => '.', 'data-decimal' => ',']) !!}
                                    </div>
                                @else
                                    {{ maskMoney($item->multa) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Valor total</th>
                            <td>{{ maskMoney($item->valorTotal() + $item->multa) }}</td>
                        </tr>
                    </table>    
                </div>
                <div class="box-body">
                    <div class="form-group {{ $errors->has('data_entrega') ? 'has-error' : '' }}">
                        {!! Form::label('data_entrega', 'Data prevista de entrega') !!}
                        {!! Form::text('data_entrega' , $item->data_entrega ? $item->data_entrega->format('Y-m-d') : '' , ['class' => 'form-control datepicker', ]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status' , \App\Models\Pedido::$mapStatus , null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('pedido.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @include('portal-franqueado.admin.pedido.modals.novo-produto')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $(function(){
                $('.maskMoney').maskMoney({
                    allowZero: true
                });
            });

            $('html').on('click', '.open-modal-new-product',function(e){
                e.preventDefault();
                var ids = {id:[]};
                $('input[name^=produto]').each(function(i, item){
                    ids.id.push($(item).data('id'));
                });

                $('select#novo_produto').html('<option value>Selecione uma opção</option>');
                var url = '{{ route('admin.ajax.produtos') }}?' + $.param(ids);
                $.get(url, function(data){
                    $(data).each(function(i, item){
                        $('select#novo_produto').append('<option data-original-name="' + item.nome + '" value="' + item.id + '">' + item.nome + ' | ' + item.preco + '</option>');
                    });
                });
            });

            $('.btn-add-product').click(function(){
                var id;
                if(id = $('#novo_produto').val())
                {
                    var nome = $('#novo_produto option:selected').data('original-name');
                    var quantidade = $('#novo_produto_quantidade').val();
                    $('.target-new-products tr').last().after('' +
                        '<tr>' +
                            '<td>' + nome + '</td>'+
                            '<td>0</td>' +
                            '<td>' +
                                '<input min="0" type="number" class="form-control" data-id="'+ id +'" name="novo_produto['+ id +']" value="' + quantidade + '">' +
                            '</td>' +
                            '<td>0</td>' +
                            '<td>0</td>' +
                        '</tr>');
                }
                $('#novo_produto_quantidade').val(1);
            });

        });
    </script>
@endsection