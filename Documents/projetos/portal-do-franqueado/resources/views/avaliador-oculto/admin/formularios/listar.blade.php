@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Lista de Formulários
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
                            <th>Nome</th>
                            <th>Lojas</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>
                                        @foreach($item->lojas as $loja)
                                            <div>{{ $loja->nome }}</div>
                                        @endforeach
                                    </td>
                                    <td>{!! $item->status_string_formatted !!}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="options">
                                        <div class="row" style="width: 95%">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_EDITAR]))
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('avaliadoroculto.formularios.edit', $item->id) }}"  class="btn btn-warning" style="width: 100%"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @if($item->status == \App\Models\AvaliadorOculto\Formulario::STATUS_INATIVO)
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('avaliadoroculto.formularios.toggle.active', $item->id) }}"  class="btn btn-success" style="width: 100%;"><i title="Habilitar" class="fa fa-check"></i></a>
                                                </div>  
                                            @else
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('avaliadoroculto.formularios.toggle.active', $item->id) }}"  class="btn btn-danger" style="width: 100%;"><i title="Desabilitar" class="fa fa-close"></i></a>
                                                </div>
                                            @endif

                                            @endif
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('avaliadoroculto.formularios.estatisticas', $item->id) }}"  class="btn" style="width: 100%; background-color: #001F3F; color: #fff"><i title="Estatísticas" class="fa fa-pie-chart"></i></a>
                                                </div>
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_AVALIADOR_OCULTO_FORMULARIOS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('avaliadoroculto.formularios.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-3"> 
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
                                    <td colspan="5">Nenhum formulário encontrado</td>
                                @else
                                    <td colspan="5">Nenhum formulário cadastrado</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <div class="center pagination-black">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div>
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
                        title: "Deletar formulário?",
                        text: "Esta operação não pode ser desfeita, deseja continuar?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, deletar formulário",
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