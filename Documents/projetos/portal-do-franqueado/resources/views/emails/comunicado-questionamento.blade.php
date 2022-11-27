Um questionamento em comunicado foi registrado no sistema<br>
<b>Comunicado</b>: {{ $comunicado->titulo }}<br>
<b>Feito por</b>: {{ $questionamento->user->nome }}<br>
<p>
    {!! str_limit(strip_tags($questionamento->texto, '<br></br><p>'), 150) !!}
    <a target="_blank" href="{{ url('comunicados/ler', $comunicado->id) }}">Clique aqui para ler mais.</a>
</p>
