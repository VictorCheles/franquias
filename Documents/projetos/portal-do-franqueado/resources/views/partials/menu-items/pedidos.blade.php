<?php
$menuActive = collect([
    route('pedido.index'),
    route('pedido.create'),
    route('admin.categoria.index'),
    route('admin.categoria.create'),
    route('admin.produto.index'),
    route('admin.produto.create'),
    route('admin.pedidos.abertos'),
    route('admin.fornecimento.datapedido.index'),
    route('admin.fornecimento.pedidominimo.index'),
]);
?>

@if($user->isAdmin())
    <li class="dropdown {!! menuActive($menuActive) !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Fornecimento<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu" style="min-width: 345px;">
            <li><a href="{{ route('pedido.index') }}">Meus pedidos</a></li>
            <li><a href="{{ route('pedido.create') }}">Fazer pedido</a></li>
            <li class="divider"></li>
            @if($user->hasRoles([\App\ACL\Recurso::ADM_CATEGORIAS_LISTAR]))
                <li><a href="{{ route('admin.categoria.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Gerenciar Categorias</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_PRODUTOS_LISTAR]))
                <li><a href="{{ route('admin.produto.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small>Gerenciar Produtos</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_FORNECIMENTO_PEDIDOS_LISTAR]))
                <li><a href="{{ route('admin.pedidos.abertos') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Fornecimento - Pedidos em aberto</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_PRACAS_EDITAR]))
                <li><a href="{{ route('admin.fornecimento.datapedido.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Fornecimento - Data limite de Pedidos</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_LOJAS_EDITAR]))
                <li><a href="{{ route('admin.fornecimento.pedidominimo.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Fornecimento - Pedido Minimo por loja</a></li>
            @endif
        </ul>
    </li>
@else
    @if($user->hasRoles([\App\ACL\Recurso::PUB_PEDIDOS]))
        <li class="dropdown {!! menuActive($menuActive) !!}">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-shopping-cart"></i> Fornecimento<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('pedido.index') }}">Meus pedidos</a></li>
                <li><a href="{{ route('pedido.create') }}">Fazer pedido</a></li>
            </ul>
        </li>
    @endif
@endif