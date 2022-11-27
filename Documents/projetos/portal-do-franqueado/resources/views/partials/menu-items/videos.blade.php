<?php
$menuActive = menuActive(collect([
    url('/videos/dashboard'),
    route('admin.tag.index'),
    route('admin.tag.create'),
    route('admin.video.index'),
    route('admin.video.create'),
]));
?>

@if($user->isAdmin())
    <li class="dropdown {!! $menuActive !!}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-video-camera"></i> Canal do franqueado<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            @if($user->hasRoles([\App\ACL\Recurso::PUB_CANAL_FRANQUEADO]))
                <li><a href="{{ url('/videos/dashboard') }}">Canal do franqueado</a></li>
            @endif
            <li class="divider"></li>
            @if($user->hasRoles([\App\ACL\Recurso::ADM_TAGS_LISTAR]))
                <li><a href="{{ route('admin.tag.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Gerenciar Tags</a></li>
            @endif
            @if($user->hasRoles([\App\ACL\Recurso::ADM_VIDEOS_LISTAR]))
                <li><a href="{{ route('admin.video.index') }}"><small class="label pull-left bg-red" style="margin-top: 2px; margin-right: 7px;">admin</small> Gerenciar Vídeos</a></li>
            @endif
        </ul>
    </li>
@else
    @if($user->hasRoles([\App\ACL\Recurso::PUB_CANAL_FRANQUEADO]))
        <li class="{!! $menuActive !!}"><a href="{{ url('/videos/dashboard') }}"><i class="fa fa-video-camera"></i> Canal do franqueado</a></li>
    @endif
@endif