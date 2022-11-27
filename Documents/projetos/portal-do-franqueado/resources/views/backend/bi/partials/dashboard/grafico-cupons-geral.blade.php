@parent
<div class="row">
    <div class="col-sm-12">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Cupons por Status (Geral)</h3>
                <div class="box-tools pull-right">
                    {!! Form::select('g1' , $opcoesVisualizacoes , Request::get('g1') ?: 'bar', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="box-body">
                <div id="grafico-cupons"></div>
                <table id="tabelacupons" class="hide">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Quantidade</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cupons as $k => $v)
                        @if($k == 'total')
                            <tr>
                                <th>Total</th>
                                <td>{{ $v }}</td>
                            </tr>
                        @else
                            <tr>
                                <th>{{ str_plural(\App\Models\Cupom::$mapsStatus[$k]) }}</th>
                                <td>{{ $v }}</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('#grafico-cupons').highcharts({
                data: {
                    table: 'tabelacupons'
                },
                chart: {
                    type: '{!! Request::get('g1') ?: 'bar' !!}'
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
                                this.point.y + ' ' + this.point.name.toLowerCase();
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

        $('[name=g1]').change(function(){
            var g2 = $('[name=g2]').val();
            var params = $.param({
                g1: $(this).val(),
                g2: g2
            });
            window.location.href='{{ url()->current() }}?' + params;
        });

    </script>
@endsection