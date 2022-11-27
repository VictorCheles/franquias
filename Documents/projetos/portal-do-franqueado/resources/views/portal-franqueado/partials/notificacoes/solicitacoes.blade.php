<?php
$notificacoes = Auth::user()->notificacoesSolicitacao()->get();
$ids = $notificacoes->map(function ($n) {
    return $n->atributos['solicitacao_id'];
});
$tickets = \App\Models\Solicitacao::whereIn('id', $ids->toArray())->get()->groupBy('id');
?>
<li class="dropdown notifications-menu alert-notifications">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o faa-flash {{ $notificacoes->count() == 0 ?: 'faa-fast animated' }}"></i>
        @if($notificacoes->count() > 0)
            <span class="label label-success">{{ $notificacoes->count() }}</span>
        @endif
    </a>
    <ul class="dropdown-menu">
        @if($notificacoes->count() == 0)
            <li class="header">Nenhuma nova solicitação/atualização</li>
        @else
            <li class="header">Você tem {{ $notificacoes->count() }} solicitações/atualizações</li>
            <li>
                <ul class="menu">
                    @foreach($notificacoes as $n)
                        <li>
                            <a title="{{ $n->atributos['solicitacao_titulo'] }}" href="{{ route('solicitacao.show', $n->atributos['solicitacao_id']) }}">
                                <small><b><i>{{ @$tickets[$n->atributos['solicitacao_id']][0]->tag }}</i></b></small>
                                <br>
                                {{ $n->atributos['solicitacao_titulo'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="footer"><a href="{{ route('limpar.notificacoes', \App\Models\Notificacao::TIPO_SOLICITACAO) }}">Marcar todos como lidos</a></li>
        @endif
    </ul>
</li>