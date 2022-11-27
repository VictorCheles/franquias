@parent
<div class="row">
    <div class="col-sm-12">
        <div class="box box-danger box-solid">
            <div class="box-header">
                <h3 class="box-title">
                    @if(Request::input('filter'))
                        O filtro retornou {{ $promocoes->count() }} promoções
                    @else
                        As 10 promoções com mais cupons
                    @endif
                </h3>
                <div class="box-tools pull-right">
                    <a href="#" class="open-modal-filter btn-box-tool">
                        <i class="fa fa-filter"></i> Filtro
                    </a>
                    <a href="{{ url()->current() }}" class="btn-box-tool">
                        <i class="fa fa-close"></i> Limpar filtro
                    </a>
                </div>
            </div>
            <div class="box-body">
                <div id="grafico-cupons"></div>
            </div>
            <table id="datatable" class="hide">
                <thead>
                <tr>
                    <th></th>
                    <th>Total</th>
                    <th>Usados</th>
                    {{--<th>Válidos</th>--}}
                    {{--<th>Vencidos</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($promocoes as $promocao)
                    <tr>
                        <th>{{ $promocao->nome }}</th>
                        <td>{{ $promocao->cupons_por_categoria['total'] }}</td>
                        <td>{{ $promocao->cupons_por_categoria[\App\Models\Cupom::STATUS_USADO] }}</td>
{{--                        <td>{{ $promocao->cupons_por_categoria[\App\Models\Cupom::STATUS_VALIDO] }}</td>--}}
                        {{--<td>{{ $promocao->cupons_por_categoria[\App\Models\Cupom::STATUS_VENCIDO] }}</td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    @include('backend.bi.partials.promocoes.modals.grafico-filter')
</div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('#grafico-cupons').highcharts({
                data: {
                    table: 'datatable'
                },
                chart: {
                    type: 'column'
                },
                title: {
                    text: ''
                },
                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Total'
                    }
                },
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.series.name + '</b><br>' +
                                this.point.y;
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                }
            });
        });
    </script>
@endsection