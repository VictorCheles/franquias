Foi postado um feedback na solicitação<br>
<b>Solicitacao</b>: {{ $solicitacao->titulo }}<br>
<b>Feedback feito por</b>: {{ $feeder->nome }}<br>
<a target="_blank" href="{{ route('solicitacao.show', $solicitacao->id) }}">Clique aqui para ver.</a>