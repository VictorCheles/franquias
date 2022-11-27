@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Arquivos
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
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nome</th>
                                <th>Pasta</th>
                                <th>Criado em</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->extensao }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->pasta->nome . ' - ' . \App\Models\Pasta::$setores[$item->pasta->setor_id]}}</td>
                                    <td>{{ $item->created_at->format('d/m/Y \a\s H:i:s') }}</td>
                                    <td class="options" style="width: 25%">
                                        <div class="row">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_VER_DOWNLOADS]))
                                                <div class="col-md-3">
                                                    <a href="#" class="btn btn-success open-modal-downloads" rel="{{ $item->id }}" target="_blank"><i title="Downloads únicos" class="fa fa-download"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_EDITAR]))
                                                <div class="col-md-3">
                                                    <a href="{{ route('admin.arquivo.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_VER_URL]))
                                                <div class="col-md-3">
                                                    <a href="{{ route('admin.arquivo.edit', $item->id) }}" class="btn btn-info open-modal-url" rel="{{ $item->id }}"><i title="URL do arquivo" class="fa fa-external-link"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.arquivo.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-3">
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
                                    <td colspan="5">Nenhum arquivo encontrado</td>
                                @else
                                    <td colspan="5">Nenhum arquivo cadastrado</td>
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
    @include('portal-franqueado.admin.arquivo.modals.filtro')
    @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_VER_DOWNLOADS]))
        @include('portal-franqueado.admin.arquivo.modals.downloads')
    @endif

    @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_VER_URL]))
        @include('portal-franqueado.admin.arquivo.modals.url')
    @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar arquivo?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar arquivo",
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