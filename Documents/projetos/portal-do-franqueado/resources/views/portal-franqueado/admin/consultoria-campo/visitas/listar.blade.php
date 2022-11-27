@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Consultoria de Campo - Lista de Visitas
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
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Formulário</th>
                                <th>Loja</th>
                                <th>Score</th>
                                <th>Consultor</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->data->format('d/m/Y') }}</td>
                                    <td>{{ $item->formulario->descricao }}</td>
                                    <td>{{ $item->loja->nome }}</td>
                                    <td>{!! $item->score_formatted !!}</td>
                                    <td>{{ $item->user->nome }}</td>
                                    <td>{!! $item->status_formatted !!}</td>
                                    <td class="options">
                                        <div class="btn-group-vertical">
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR]) and $item->status == \App\Models\ConsultoriaCampo\Visita::STATUS_INICIADA)
                                                @if($user->id == $item->user_id or $user->isAdmin())
                                                    <a href="{{ route('admin.consultoria-de-campo.visitas.show', $item->id) }}" class="btn btn-flat btn-default">
                                                        <i class="fa fa-forward"></i> Continuar Visita
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-flat btn-default disabled" rel="tooltip" data-placement="top" title="Você não é o avaliador dessa visita">
                                                        <i class="fa fa-forward"></i> Continuar Visita
                                                    </a>
                                                @endif
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR]) and in_array($item->status, [\App\Models\ConsultoriaCampo\Visita::STATUS_FINALIZADA, \App\Models\ConsultoriaCampo\Visita::STATUS_VALIDADA]))
                                                <a href="{{ route('admin.consultoria-de-campo.visitas.detalhes', $item->id) }}" class="btn btn-flat btn-default">
                                                    <i class="fa fa-eye"></i> Detalhes da visita
                                                </a>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_PROGRAMA_QUALIDADE_AVALIACAO_EDITAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.consultoria-de-campo.visitas.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <a href="#" class="btn btn-flat btn-default fake-submit" style="width: 100%"><i class="fa fa-trash"></i> Deletar</a>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="5">Nenhum registro encontrado</td>
                                @else
                                    <td colspan="5">Nenhum registro cadastrado</td>
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
    @include('portal-franqueado.admin.consultoria-campo.visitas.modals.filter')
@endsection
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent('form');
                swal({
                        title: "Deletar visita?",
                        text: "Esta operação não pode ser desfeita, e também serão excluidas todas as ações corretivas, notificações e respostas vinculadas a esta visita, deseja continuar?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, deletar visita",
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

            $('.btn-validar').click(function (e) {
                e.preventDefault();
                var goto = $(this).data('goto');
                swal({
                        title: "Validar visita?",
                        text: "Esta operação possui carater definitivo",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#00a65a",
                        confirmButtonText: "Sim, validar visita",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            window.location.href = goto;
                        } else {
                            return false;
                        }
                    });
                return false;
            });
        });
    </script>
@endsection