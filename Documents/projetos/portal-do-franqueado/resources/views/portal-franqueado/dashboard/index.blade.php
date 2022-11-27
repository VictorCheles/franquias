@extends('layouts.portal-franqueado')
<?php $user = Auth::user(); ?>

@section('content')
    <div class="row">
        @include('portal-franqueado.dashboard.index.vitrine')
        @if($user->hasRoles([\App\ACL\Recurso::PUB_COMUNICADOS]))
            @include('portal-franqueado.dashboard.index.comunicados')
        @endif

        @if($show_modal_metas)
            @if($metas->count() > 0 and $user->hasRoles([\App\ACL\Recurso::PUB_METAS]))
                @include('portal-franqueado.dashboard.index.metas')
            @endif
        @endif

        @if($user->isAdmin())
            @include('portal-franqueado.dashboard.index.cupons.index')
        @endif

        <div id="calendar-container">
            {!! $calendario !!}
        </div>

        @if($user->hasRoles([\App\ACL\Recurso::PUB_SOLICITACOES]))
            @include('portal-franqueado.dashboard.index.solicitacoes')
        @endif

        @if($enquete and $user->hasRoles([\App\ACL\Recurso::PUB_ENQUETES]))
            @include('portal-franqueado.dashboard.index.modal-enquete')
        @endif
    </div>
@endsection
@section('extra_scripts')
    @parent
        <script>
            $(function(){
                $('.popover-trigger').popover();
                $('#calendar-container').on('click', '.reload-simple-calendar', function(){
                    $('.overlay').show();
                    params = $.param({
                        month: $(this).data('month'),
                        year: $(this).data('year')
                    });
                    url = '{{ url('dashboard/ajax/calendario') }}';
                   $.ajax({
                       type: 'GET',
                       url: url,
                       data: params
                   }).success(function(data){
                       $('#calendar-container').html(data);
                       $('.popover-trigger').popover();
                   }).error(function(data){

                   });
                });

                $('#calendar-container').on('click', '.tr-link', function(){
                    window.location.href = $(this).data('url');
                });

                $('#calendar-container').on('mouseenter', '.show-hide-events', function() {
                    $('.show-hide-events').stop(true, true).animate({'max-height': '1500px'});
                });

                $('#calendar-container').on('mouseleave', '.show-hide-events', function() {
                    $('.show-hide-events').stop(true, true).animate({'max-height': '323px'});
                });

            });
        </script>
        @if($alertPedido and !Auth::user()->isAdmin())
            <script>
                $(function(){
                    swal({
                        title: "Você tem menos de 24h para fazer seu pedido!",
                        text: "Para evitar multa, faça agora seu pedido!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#00a65a",
                        confirmButtonText: "Fazer pedido",
                        closeOnConfirm: false
                    },
                    function(){
                        window.location.href = '{{ route('pedido.create') }}';
                    });
                });
            </script>
        @endif
        @if(session('pedido_bloqueado'))
            <script>
                $(function(){
                    swal('Recurso bloqueado', 'entre em contato com o ⁠⁠⁠setor financeiro', 'error');
                });
            </script>

            <?php session()->forget('pedido_bloqueado'); ?>
        @endif
@endsection
