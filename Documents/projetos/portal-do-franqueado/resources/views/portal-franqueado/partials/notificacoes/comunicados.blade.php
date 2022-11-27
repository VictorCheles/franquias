<?php
$notificacoes = Auth::user()->notificacoesComunicado()->get();
?>
<li class="dropdown messages-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bullhorn faa-flash {{ $notificacoes->count() == 0 ?: 'faa-fast animated' }}"></i>
        @if($notificacoes->count() > 0)
            <span class="label label-success">{{ $notificacoes->count() }}</span>
        @endif
    </a>
    <ul class="dropdown-menu ">
        @if($notificacoes->count() == 0)
            <li class="header">Nenhum novo comunicado</li>
        @else
            <li class="header">Você tem {{ $notificacoes->count() }} comunicados</li>
            <li>
                <ul class="menu">
                    @foreach($notificacoes as $n)
                        <li>
                            <a href="{{ url('/comunicados/ler', $n->atributos['comunicado_id']) }}">
                                <div class="pull-left">
                                    <img src="{{ asset('images/brand_small.png') }}" class="img-circle" alt="User Image">
                                </div>
                                <h4>
                                    {{ env('APP_NAME') }}
                                    <small><i class="fa fa-clock-o"></i> {{ $n->created_at->diffForHumans() }}</small>
                                </h4>
                                <p style="word-wrap: break-word; white-space: normal;">
                                    {{ $n->mensagem }}
                                    <br>
                                    <b>{{ $n->atributos['comunicado_titulo'] }}</b>
                                </p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="footer"><a href="{{ route('limpar.notificacoes', \App\Models\Notificacao::TIPO_COMUNICADO) }}">Marcar todos como lidos</a></li>
        @endif
    </ul>
</li>
