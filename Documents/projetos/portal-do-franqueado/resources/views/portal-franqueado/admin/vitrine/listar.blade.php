@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Vitrines
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
        <div class="col-xs-12 text-right" style="margin-top: -3%">
            @if($user->hasRoles([\App\ACL\Recurso::ADM_VITRINES_CRIAR]))
                <a href="{{ route('admin.vitrine.create') }}" class="btn btn-sm btn-min-block btn-theme-padrao">
                    <i class="fa fa-plus"></i> Nova vitrine
                </a>
                <br/>
                <br/>   
            @endif
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
                                <th>Ordem</th>
                                <th>Imagem</th>
                                <th>Link</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->ordem }}</td>
                                    <td>
                                        <a class="colorbox" href="{{ $item->img }}">
                                            <img height="50" width="50" src="{{ $item->img }}">
                                        </a>
                                    </td>
                                    <td>
                                        @if($item->link)
                                            <a href="{{ $item->link }}" target="_blank">{{ $item->link }}</a>
                                        @endif
                                    </td>
                                    <td>{!! $item->status_styled !!}</td>
                                    <td class="options">
                                        <div class="row">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_VITRINES_EDITAR]))
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('admin.vitrine.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_VITRINES_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.vitrine.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-4"> 
                                                        <a href="#"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Deletar" class="fa fa-trash"></i></a>
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
                                    <td colspan="6">Nenhuma vitrine encontrada</td>
                                @else
                                    <td colspan="6">Nenhuma vitrine cadastrada</td>
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
    @include('portal-franqueado.admin.vitrine.modals.filtro')
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
                    title: "Deletar vitrine?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar vitrine",
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