<?php
$menuActive = collect([
    route('modulo-de-metas'),
]);
?>

@if($user->hasRoles([\App\ACL\Recurso::PUB_METAS]))
    <li class="{!! menuActive($menuActive) !!}">
        <a href="{{ route('modulo-de-metas') }}"><i class="fa fa-area-chart"></i> Metas</a>
    </li>
@endif