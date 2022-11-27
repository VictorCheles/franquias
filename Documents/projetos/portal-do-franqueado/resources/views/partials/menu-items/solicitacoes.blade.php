<?php
$menuActive = collect([
    route('solicitacao.index'),
    route('solicitacao.create'),
    route('admin.solicitacao.index'),
    route('admin.solicitacao.create'),
]);
?>

@if($user->isAdmin())
    <li class="dropdown {!! menuActive($menuActive) !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check"></i> Solicitações<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('solicitacao.index') }}">Acompanhamento</a></li>
            <li><a href="{{ route('solicitacao.create') }}">Nova solicitação</a></li>
            <li class="divider"></li>
            @if($user->hasRoles([\App\ACL\Recurso::ADM_SOLICITACOES_LISTAR]))
                <li><a href="{{ route('admin.solicitacao.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Solicitações</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_SOLICITACOES_CRIAR]))
                <li><a href="{{ route('admin.solicitacao.create') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Nova solicitação</a></li>
            @endif
        </ul>
    </li>
@else
    @if($user->hasRoles([\App\ACL\Recurso::PUB_SOLICITACOES]))
        <li class="dropdown {!! menuActive($menuActive) !!}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-check"></i> Solicitações<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('solicitacao.index') }}">Acompanhamento</a></li>
                <li><a href="{{ route('solicitacao.create') }}">Nova solicitação</a></li>
            </ul>
        </li>
    @endif
@endif