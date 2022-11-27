<?php
$menuActive = [];
foreach (\App\Models\Pasta::$setores as $id => $setor) {
    $menuActive[] = route('arquivo.setor', $id);
}
$menuActive[] = route('admin.pasta.index');
$menuActive[] = route('admin.pasta.create');
$menuActive[] = route('admin.arquivo.index.admin');
$menuActive[] = route('admin.arquivo.create');
$menuActive = collect($menuActive);
?>

@if($user->isAdmin())
    <li class="dropdown {!! menuActive($menuActive) !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open"></i> Arquivos<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu" style="min-width: 215px;">
            @foreach(\App\Models\Pasta::$setores as $id => $setor)
                <li><a href="{{ route('arquivo.setor', $id) }}">{{ $setor }}</a></li>
            @endforeach
            <li class="divider"></li>
            @if($user->hasRoles([\App\ACL\Recurso::ADM_PASTAS_LISTAR]))
                <li><a href="{{ route('admin.pasta.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Gerenciar Pastas</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_ARQUIVOS_LISTAR]))
                <li><a href="{{ route('admin.arquivo.index.admin') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Gerenciar Arquivos</a></li>
            @endif
        </ul>
    </li>
@else
    <li class="dropdown {!! menuActive($menuActive) !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder"></i> Arquivos<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            @if($user->hasRoles([\App\ACL\Recurso::PUB_ARQUIVOS]))
                @foreach(\App\Models\Pasta::$setores as $id => $setor)
                    <li><a href="{{ route('arquivo.setor', $id) }}">{{ $setor }}</a></li>
                @endforeach
            @endif
        </ul>
    </li>
@endif