@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Comunicados
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
                    <!-- display inline essas benga -->
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
                            <th>Imagem</th>
                            <th width="20%">Título</th>
                            <th>Criado em</th>
                            <th>Setor</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>
                                        <a class="colorbox" href="{{ $item->img }}">
                                            <img height="50" width="50" src="{{ $item->img }}">
                                        </a>
                                    </td>
                                    <td>{{ $item->titulo }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y \a\s H:i:s') }}</td>
                                    <td>{{ $item->setor ? $item->setor->nome : '' }}</td>
                                    <td class="options" style="width: 25%">
                                        <div class="btn-group-vertical">
                                            <div class="row">
                                                <div class="col-md-2"> 
                                                    <a href="{{ url('/comunicados/ler', $item->id) }}" class="btn btn-default" target="_blank"><i title="Ver" class="fa fa-eye"></i></a>
                                                </div>
                                                {{--@if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_CRIAR]))--}}
                                                    {{--<div class="col-md-2"> --}}
                                                        {{--<a href="{{ url('/admin/comunicados/criar/' . '?original=' . $item->id) }}" class="btn" style="background-color: #605ca8; color: #fff" target="_blank"><i title="Copiar comunicado" class="fa fa-copy"></i></a>--}}
                                                    {{--</div>--}}
                                                {{--@endif--}}
                                                @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_VER_DESTINATARIO]))
                                                    <div class="col-md-2"> 
                                                        <a href="#" class="btn open-modal-destinatario" style="background-color: #00c0ef; color: #fff" rel="{{ $item->id }}"><i title="Destinatários" class="fa fa-group"></i></a>
                                                    </div>
                                                @endif
                                                @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_EDITAR]))
                                                    <div class="col-md-2"> 
                                                        <a href="{{ url('/admin/comunicados/editar', $item->id) }}" class="btn btn-warning" rel="{{ $item->id }}"><i title="Editar" class="fa fa-edit"></i></a>
                                                    </div>
                                                @endif
                                                @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_DELETAR]))
                                                    <form class="swal-confirmation" action="{{ url('admin/comunicados/deletar', $item->id) }}" method="POST" style="display: inline;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <div class="col-md-3"> 
                                                            <a href="#"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Deletar" class="fa fa-trash"></i></a>
                                                        </div>
                                                    </form>
                                                @endif
                                        </div>
                                      </div>    
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="3">Nenhum usuário encontrado</td>
                                @else
                                    <td colspan="3">Nenhum comunicado cadastrado</td>
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
    @include('portal-franqueado.admin.comunicados.modals.filtro')
    @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_VER_DESTINATARIO]))
        @include('portal-franqueado.admin.comunicados.modals.destinatarios')
    @endif
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
                    title: "Deletar comunicado?",
                    text: "Esta operação não pode ser desfeita, deseja continuar?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, deletar comunicado",
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