@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Pastas
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
                                <th width="33%">Tipo</th>
                                <th width="33%">Setor</th>
                                <th width="33%">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td width="33%">{{ $item->nome }}</td>
                                    <td width="33%">{{ $item->setor }}</td>
                                    <td width="50%" class="options">
                                        <div class="row">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_CRIAR]))
                                                <div class="col-md-2"> 
                                                    <a href="{{ route('admin.arquivo.create', ['pasta_id' => $item->id]) }}" class="btn btn-success" target="_blank"><i title="Adicionar arquivo" class="fa fa-plus"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PASTAS_EDITAR]))
                                                <div class="col-md-2"> 
                                                    <a href="{{ route('admin.pasta.edit', $item->id) }}" class="btn btn-warning" target="_blank"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PASTAS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.pasta.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-2"> 
                                                        <a href="#" class="btn btn-danger fake-submit" style="width: 100%;"><i title="Deletar" class="fa fa-trash"></i></a>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="4">Nenhum usuário encontrado</td>
                                @else
                                    <td colspan="4">Nenhum comunicado cadastrado</td>
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
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar pasta?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar pasta",
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