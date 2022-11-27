Um novo comunicado foi registrado no sistema<br>
<b>Comunicado</b>: {{ $comunicado->titulo }}<br>
<p>
    {!! str_limit(strip_tags($comunicado->descricao, '<br></br><p>'), 150) !!}
    <a target="_blank" href="{{ url('comunicados/ler', $comunicado->id) }}">Clique aqui para ler mais.</a>
</p>
