<?php
$menuActive = collect([
    route('admin.mensagens'),
    route('admin.mensagens.create'),
    route('admin.mensagens.enviadas'),
]);

$mensagensCount = Auth::user()->notificacoesMensagens()->count();
?>

<style>
    .grad
    {
        background: #fc0707 !important; /* For browsers that do not support gradients */
        background: -webkit-linear-gradient(#fc0707, #b0281a) !important; /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#fc0707, #b0281a) !important; /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#fc0707, #b0281a) !important; /* For Firefox 3.6 to 15 */
        background: linear-gradient(#fc0707, #b0281a) !important; /* Standard syntax (must be last) */
    }
    .grad a:hover
    {
        background: #d40000 !important; /* For browsers that do not support gradients */
        background: -webkit-linear-gradient(#d40000, #891b0f) !important; /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#d40000, #891b0f) !important; /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#d40000, #891b0f) !important; /* For Firefox 3.6 to 15 */
        background: linear-gradient(#d40000, #891b0f) !important; /* Standard syntax (must be last) */
    }
</style>

<li class="{!! menuActive($menuActive) !!} grad">
    <a href="{{ route('admin.mensagens') }}">
        <i class="fa fa-envelope"></i> Mensagens
        @if($mensagensCount > 0)
            <span class="label label-danger" style="background: #000 !important; padding: 3px 4px; top: 5px;">{{ $mensagensCount }}</span>
        @endif
    </a>
</li>