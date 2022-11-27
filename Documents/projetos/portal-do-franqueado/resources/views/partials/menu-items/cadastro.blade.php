<?php
$menuActive = collect([
    route('clientes_loja.index'),
]);
?>

@if($user->hasAnyRole(\App\ACL\Recurso::PUB_CLIENTE_LOJA))
    <li class="dropdown {!! menuActive($menuActive) !!}"  style="padding-left: 11px; padding-right: 11px">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-pencil"></i> Cadastro<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            @if($user->hasRoles([\App\ACL\Recurso::PUB_CLIENTE_LOJA_LISTAR]))
                <li><a href="{{ route('clientes_loja.index') }}"  style="padding-left: 11px; padding-right: 11px">Clientes</a></li>
            @endif
        </ul>
    </li>
@endif