@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Grupos
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
        <div class="col-xs-12">
            <div class="box box-black box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="20%">Nome</th>
                                <th width="30%">Usuários</th>
                                <th width="40%">Recursos</th>
                                <th width="10%">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="box box-black collapsed-box">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Ver usuários ({{ $item->users->count() }})</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-flat btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="box-body">
                                                    @if($item->users->count() > 0)
                                                        {!! $item->users->implode('nome', '<br>') !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="box box-black collapsed-box">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Ver recursos ({{ $item->recursos->count() }})</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-flat btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div> 
                                                <div class="box-body">
                                                    @if($item->recursos->count() > 0)
                                                        {!! $item->recursos->implode('descricao', '<br>') !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="options">
                                        <div class="row">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_GRUPOS_EDITAR]))
                                                <div class="col-md-5"> 
                                                    <a href="{{ route('admin.grupos.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_GRUPOS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.grupos.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-7"> 
                                                        <a href="#"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Deletar" class="fa fa-trash"></i></a>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
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
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar grupo?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar grupo",
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