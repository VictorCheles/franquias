@extends('layouts.portal-franqueado')

@section('content')

    <section class="col-xs-12">
        <div class="headline-row">
            <h1 class="section-title caticon sbx">
                <img src="{{ asset('images/brand_small.png') }}"> Calendário
            </h1>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="external-event col-xs-3" data-filter="all">Todos</div>
                <div class="external-event bg-green col-xs-3" data-filter="comunicado">Comunicados</div>
                <div class="external-event bg-red col-xs-3" data-filter="solicitacao">Solicitações</div>
                <div class="external-event bg-yellow col-xs-3" data-filter="data_pedido">Prazo limite para pedido</div>
                <br>
                <br>
            </div>
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body no-padding">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.container').eq(1).css('width', '100%');
            var todos = {!! $calendarioJsonData->toJson() !!};
            var comunicado = {!! $comunicadoJsonData->toJson() !!};
            var solicitacao = {!! $solicitacaoJsonData->toJson() !!};
            var dataPedido = {!! $dataPedidoJsonData->toJson() !!};

            var calendar = $('#calendar').fullCalendar({
                lang: 'pt-br',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: todos
            });

            $('.external-event').click(function(){
                calendar.fullCalendar('removeEvents');
                var events;
                switch($(this).data('filter'))
                {
                    case 'all':
                        events = todos;
                        break;
                    case 'comunicado':
                        events = comunicado;
                        break;
                    case 'solicitacao':
                        events = solicitacao;
                        break;
                    case 'data_pedido':
                        events = dataPedido;
                        break;
                    default:
                        events = todos;
                        break;

                }
                calendar.fullCalendar('addEventSource', events);
            });
        });
    </script>
@endsection