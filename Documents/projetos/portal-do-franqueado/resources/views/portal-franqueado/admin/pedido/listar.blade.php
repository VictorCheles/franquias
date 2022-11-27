<?php $auth = Auth::user(); ?>
@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Pedidos - acompanhamento
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Loja</th>
                                <th>Solicitado em</th>
                                <th>Data prevista de entrega</th>
                                <th>Status</th>
                                <th>Recebido por</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>
                                        {{ $item->loja->nome }}
                                    </td>
                                    <td>
                                        {{ $item->created_at->format('d/m/Y \a\s H:i:s') }}
                                    </td>
                                    <td>
                                        {{ $item->data_entrega ? $item->data_entrega->format('d/m/Y') : 'data ainda não foi definida' }}
                                    </td>
                                    <td>
                                        {!! $item->status_formatted !!}
                                    </td>
                                    <td>
                                        {!! $item->recebido_em ? $item->recebido_em->format('d/m/Y H:i:s') .'<br>' . $item->recebido_por->nome : '' !!}
                                    </td>
                                    <td class="options">
                                        <div class="btn-group">
                                        @if($auth->isAdmin() and $auth->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_EDITAR]))
                                            <a href="{{ route('admin.pedido.edit', $item->id) }}"  class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                        @endif
                                        <a rel="modal" data-modal="{{ $item->id }}"  class="btn btn-default"><i title="Ver" class="fa fa-eye"></i></a>
                                        @if($auth->isAdmin() and $auth->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_EXTRATO]))
                                            <a href="{{ route('pedido.extrato.excel', $item->id) }}" class="btn btn-primary"><i title="Extrato Excel" class="fa fa-file-excel-o"></i></a>
                                        @endif
                                        <a href="{{ route('pedido.verificacao', $item->id) }}" class="btn btn-success"><i title="Verificação de pedido" class="fa fa-check-square-o"></i> {{ $item->pedido_mensagem->count() > 0 ? $item->pedido_mensagem->count() : '' }}</a>
                                        @if(($item->status == \App\Models\Pedido::STATUS_CONCLUIDO and $auth->loja_id == $item->loja_id) or ($auth->isAdmin() and $item->status == \App\Models\Pedido::STATUS_CONCLUIDO))
                                            <form class="swal-confirmation" action="{{ route('pedido.receber', $item->id) }}" method="GET">
                                                <a href="#" class="btn btn-flat btn-warning fake-submit" title="Tornar Recebido">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            </form>
                                        @endif
                                        </div>
                                    </td>                                
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="6">Nenhum pedido encontrado</td>
                                @else
                                    <td colspan="6">Nenhum pedido cadastrada</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-black">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
@include('portal-franqueado.admin.pedido.modals.extratos')
@include('portal-franqueado.admin.pedido.modals.filtro')
@endsection

@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.swal-confirmation .fake-submit').click(function (e) {
                var form = $(this).parent('form');
                swal({
                        title: "Deseja tornar o pedido recebido?",
                        text: "Esta operação não pode ser desfeita e tornará o pedido recebido. Deseja continuar??",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, confirmar o recebimento do pedido.",
                        closeOnConfirm: true,
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