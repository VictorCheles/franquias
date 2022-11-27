@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Clientes
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_CRIAR]))
                <a href="{{ route('clientes_loja.create') }}" class="btn btn-flat btn-app">
                    <i class="fa fa-plus"></i> Novo cliente
                </a>
            @endif
            <a href="{{ route('clientes_loja.exportar.excel') }}{{ Request::input('filter') ? '?' . http_build_query(Request::input('filter')) : '' }}" class="btn btn-flat btn-app">
                <i class="fa fa-file-excel-o"></i> Exportar lista em Excel
            </a>
        </div>
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
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Estabelecimento</th>
                                <th>Loja Vinculada</th>
                                <th>Quem cadastrou</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($lista->count() > 0)
                                @foreach($lista as $item)
                                    <tr>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->telefone }}</td>
                                        <td>{{ $item->estabelecimento->nome }}</td>
                                        <td>{{ $item->loja->nome }}</td>
                                        <td>{{ $item->user->nome }}</td>
                                        <td class="options">
                                            @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_EDITAR]))
                                                <a href="{{ route('clientes_loja.edit', $item->id) }}" class="btn btn-flat btn-default">
                                                    <i class="fa fa-edit"></i> Editar
                                                </a>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('clientes_loja.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <a href="#" class="btn btn-flat btn-default fake-submit"><i class="fa fa-trash"></i> Deletar</a>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
    @include('portal-franqueado.admin.cliente-loja.cliente.modals.filtro')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar Cliente?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar cliente",
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