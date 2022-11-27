@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Vídeos
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
                            <th>Título</th>
                            <th>Tag</th>
                            <th>URL</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            <?php $todos = \App\User::count();?>
                            @foreach($lista as $item)
                                <?php $assistiram = $item->quemAssistiu()->count(); ?>
                                <tr>
                                    <td style="width: 30%">{{ $item->titulo }}</td>
                                    <td>{!! $item->tag()->first()->titulo_formatted !!}</td>
                                    <td style="width: 20%"><a href="{{ $item->url }}" target="_blank">{{ $item->url }}</a></td>
                                    <td class="options">
                                        <div class="btn-group-vertical">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_VIDEOS_VER_QUEM_ASSISTIU]))
                                                <div class="col-md-3"> 
                                                    <a href="#" class="btn btn-default open-modal-quem-leu" style="background-color: #00c0ef; color: #fff" rel="{{ $item->id }}"><i title="Destinatários" class="fa fa-group"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_VIDEOS_EDITAR]))
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('admin.video.edit', $item->id) }}" class="btn btn-warning"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_VIDEOS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.video.destroy', $item->id) }}" method="POST" style="display: inline;">
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
        @include('portal-franqueado.admin.video.modals.filtro')
        @if($user->hasRoles([\App\ACL\Recurso::ADM_VIDEOS_VER_QUEM_ASSISTIU]))
            @include('portal-franqueado.admin.video.modals.quem-leu')
        @endif
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.swal-confirmation .fake-submit').click(function(){
                var form = $(this).parent().parent('form');
                swal({
                    title: "Deletar vídeo?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar vídeo",
                    closeOnConfirm: false
                },
                function(isConfirm){
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