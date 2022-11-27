<?php
$menuActive = collect([
    route('admin.vitrine.index'),
    route('admin.vitrine.create'),
    route('admin.setor.index'),
    route('admin.setor.create'),
    route('admin.grupos.index'),
    route('admin.grupos.create'),
    url('/backend/usuarios/listar'),
    url('/backend/usuarios/criar'),
    route('backend.praca.index'),
    route('backend.praca.create'),
    url('/backend/franquias/listar'),
    url('/backend/franquias/criar'),
    route('admin.enquetes.create'),
    route('avaliadoroculto.dashboard'),
    route('avaliadoroculto.users.index'),
    route('avaliadoroculto.users.create'),
    route('avaliadoroculto.formularios.index'),
    route('avaliadoroculto.formularios.create'),
    url('logs/accesses'),
    url('logs/changes'),
]);
?>

@if($user->isAdmin() and $user->hasRoles([\App\ACL\Recurso::ADM_VER_MENU]))
    <li class="dropdown {!! menuActive($menuActive) !!}">
        <a href="#" class="dropdown-toggle open-modal-menu-admin" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> Administração <b class="caret"></b></a>
    </li>
@endif