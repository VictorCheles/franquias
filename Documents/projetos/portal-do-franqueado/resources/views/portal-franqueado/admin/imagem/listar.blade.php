@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Imagens
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
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Descrição</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($lista->count() > 0)
                                @foreach($lista as $item)
                                    <tr>
                                        <td>
                                            <a class="colorbox" href="{{ $item->thumbnail }}">
                                                <img height="50" width="50" src="{{ $item->thumbnail }}">
                                            </a>
                                        </td>
                                        <td>{{ $item->descricao }}</td>
                                        <td class="options">
                                            <div class="btn-group">
                                                @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_DELETAR]))
                                                    <form class="swal-confirmation" action="{{ route('admin.imagem.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <a href="#" class="btn btn-flat btn-default fake-submit"><i rel="tooltip" title="Excluir" class="fa fa-trash"></i></a>
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
            $('a.colorbox').colorbox({
                rel: 'colorbox',
                width: '50%'
            });
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar imagem?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar imagem",
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