Uma nova solicitação foi registrada no sistema<br>
<b>Setor</b>: {{ $solicitacao->setor->nome }}<br>
<b>Solicitante</b>: {{ $solicitacao->user->nome }}<br>
<p>
    {!! str_limit(strip_tags($solicitacao->descricao, '<br></br><p>'), 150) !!}
    <a target="_blank" href="{{ route('solicitacao.show', $solicitacao->id) }}">Clique aqui para ler mais.</a>
</p>