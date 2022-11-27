<?php
exec('git describe --always', $version_mini_hash);
exec('git rev-list HEAD | wc -l', $version_number);
exec('git log -1', $line);
$version['short'] = 'v1.' . trim($version_number[0]) . '.' . $version_mini_hash[0];
$version['full'] = 'v1.' . trim($version_number[0]) . ".$version_mini_hash[0] (" . str_replace('commit ', '', $line[0]) . ')';
if (env('APP_ENV') == 'local') {
    $v = $version['full'];
} else {
    $v = $version['short'];
}

?>
<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">
            <b>Version {{ $v }}</b>
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ env('APP_NAME') }} DevTeam</a>.</strong> All rights reserved.
    </div>
</footer>