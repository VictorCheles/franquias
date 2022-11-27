<?php $auth = Auth()->user(); ?>
@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Fornecimento - Pedidos em aberto
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Pedidos</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Loja</th>
                                <th>Solicitado em</th>
                                <th>Data prevista de entrega</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>
                                        {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->loja->nome }}
                                    </td>
                                    <td>
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ $item->data_entrega ? $item->data_entrega->format('d/m/Y') : 'data ainda não foi definida' }}
                                    </td>
                                    <td>
                                        {!! $item->status_formatted !!}
                                    </td>
                                    <td class="options" style="width: 25%">
                                            <div class="row">
                                                @if($auth->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_EDITAR]))
                                                    <div class="col-md-3">
                                                        <a href="{{ route('admin.pedido.edit', $item->id) }}" class="btn btn-warning" rel="{{ $item->id }}">
                                                            <i title="Editar" class="fa fa-edit"></i>
                                                        </a>
                                                     </div>
                                                @endif
                                                @if($auth->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_EXTRATO]))
                                                    <div class="col-md-3">
                                                        <a href="#" rel="modal" data-modal="{{ $item->id }}" class="btn btn-default">
                                                            <i title="Ver" class="fa fa-eye"></i> 
                                                        </a>
                                                      </div>
                                                    <div class="col-md-3"> 
                                                        <a href="{{ route('pedido.extrato.excel', $item->id) }}" class="btn" style="background-color:#D81B60">
                                                            <i title="Baixar em Excel" style="color: #fff" class="fa fa-file-excel-o"></i>  
                                                        </a>
                                                    </div>
                                                @endif
                                                @if($auth->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_DELETAR]))
                                                    <div class="col-md-3"> 
                                                        <form class="swal-confirmation" action="{{ route('admin.pedido.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <a href="#"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Deletar" class="fa fa-trash"></i></a>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">Nenhum pedido em aberto</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Produtos dos pedidos acima</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($produtos->count() > 0)
                            <?php $peso = 0.0;?>
                            <?php $quantidade = 0;?>
                            @foreach($produtos as $item)
                                <?php $peso += ($item->peso * $item->quantidade);?>
                                <?php $quantidade += $item->quantidade;?>
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td>{{ $item->peso * $item->quantidade }}kg</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>Total</th>
                                <td>{{ $quantidade }}</td>
                                <td>{{ $peso }}kg</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    @include('portal-franqueado.admin.pedido.modals.extrato-filtro')
    @if($auth->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_EXTRATO]))
        @include('portal-franqueado.admin.pedido.modals.extratos')
    @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent('form');
                swal({
                    title: "Deletar pedido?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar pedido",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        form.submit();
                    } else {
                        return false;
                    }
                });
                return false;
            });
        });
    </script>
@endsection