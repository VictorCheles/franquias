@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Tags
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
                    {{--<div class="box-tools pull-right">--}}
                        {{--<a href="#" class="open-modal-filter btn btn-flat btn-box-tool p-2">--}}
                            {{--<button class="btn btn-flat btn-default btn-sm"><i class="fa fa-filter"></i> Filtro</button>--}}
                        {{--</a>--}}
                        {{--<a href="{{ url()->current() }}" class="btn btn-flat btn-box-tool p-2">--}}
                            {{--<button class="btn btn-flat btn-default btn-sm"><i class="fa fa-close"></i> Limpar filtro</button>--}}
                        {{--</a>--}}
                    {{--</div>--}}
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Cor</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{!! $item->titulo_formatted !!}</td>
                                    <td>{{ $item->cor }}</td>
                                    <td class="options">
                                        <div class="row" style="width: 100%">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_TAGS_EDITAR]))
                                                <div class="col-md-2"> 
                                                    <a href="{{ route('admin.tag.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_TAGS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.tag.destroy', $item->id) }}" method="POST" style="display: inline;">
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
                                    <td colspan="3">Nenhuma tag encontrada</td>
                                @else
                                    <td colspan="3">Nenhuma tag cadastrada</td>
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
{{--    @include('portal-franqueado.admin.setor.modals.filtro')--}}
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar tag?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar tag",
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