<?php
$menuActive = collect([
    url('/comunicados/listar'),
    url('/admin/comunicados/listar'),
    url('/admin/comunicados/criar'),
]);
?>

@if($user->isAdmin())
    <li class="dropdown {!! menuActive($menuActive) !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bullhorn"></i> Comunicados<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu" style="min-width: 245px;">
            @if($user->hasRoles([\App\ACL\Recurso::PUB_COMUNICADOS]))
                <li><a href="{{ url('/comunicados/listar') }}"> Todos os Comunicados</a></li>
            @endif
            <li class="divider"></li>
            @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_LISTAR]))
                <li><a href="{{ url('/admin/comunicados/criar') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Novo comunicado</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_COMUNICADOS_LISTAR]))
                <li><a href="{{ url('/admin/comunicados/listar') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Gerenciar comunicados</a></li>
            @endif
        </ul>
    </li>
@else
    @if($user->hasRoles([\App\ACL\Recurso::PUB_COMUNICADOS]))
        <li class="{!! menuActive($menuActive) !!}"><a href="{{ url('/comunicados/listar') }}"><i class="fa fa-bullhorn"></i> Todos os Comunicados</a></li>
    @endif
@endif