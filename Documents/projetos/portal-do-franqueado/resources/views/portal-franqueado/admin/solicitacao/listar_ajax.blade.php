<div class="box box-black box-solid">
    <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <div class="box-header">
        <h3 class="box-title">
            Todas as solicitações
        </h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Status</th>
                <th>Ticket</th>
                <th>Solicitação</th>
                <th>Solicitante</th>
                <th>Prazo</th>
                <th>Opções</th>
            </tr>
            </thead>
            <tbody>
            @forelse($lista as $item)
                <tr>
                    <td>
                        {!! $item->status_formatted_button !!}
                    </td>
                    <td>
                        <a href="{{ route('solicitacao.show', $item->id) }}" class="btn btn-info" style="background: transparent; color: #000; border-width: 2px">
                            {!! $item->tag !!}
                        </a>
                    </td>
                    <td>
                        {{ str_limit($item->titulo, 62) }}
                    </td>
                    <td>
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ $item->user->thumbnail }}">
                                <span style="margin-left: 6px">
                                            @if($auth->isAdmin())
                                        <a href="{{ url()->current() . '?' . http_build_query(['filter' => ['user_id' => $item->user->id]]) }}" rel="tooltip" title="Filtrar por solicitante">{!! $item->user->primeiro_nome !!}</a>
                                    @else
                                        {!! $item->user->primeiro_nome !!}
                                    @endif

                                    <p style="margin-left: 46px">{!! $item->user->lojas->pluck('nome')->implode('<br>') !!}</p>
                                            </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{ $item->prazo ? $item->prazo->format('d/m/Y') : 'Não definido' }}
                    </td>
                    <td class="options">
                        <div class="btn-group">
                            <a href="{{ route('solicitacao.show', $item->id) }}" class="btn btn-default"><i rel="tooltip" title="Ver solicitação" class="fa fa-eye"></i></a>
                            @if($auth->isAdmin() and $auth->hasRoles([\App\ACL\Recurso::ADM_SOLICITACOES_DELETAR]))
                                <form class="swal-confirmation" action="{{ route('admin.solicitacao.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <a href="#" class="btn btn-danger fake-submit"><i rel="tooltip" title="Deletar solicitação" class="fa fa-trash"></i></a>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty

            @endforelse
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <div class="center pagination-black">
            {{ $lista->appends(Request::all())->links() }}
        </div>
    </div>
</div>