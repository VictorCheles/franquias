@parent
<div class="row">
    <div class="col-sm-12">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Cupons resgatados por Loja</h3>
                <div class="box-tools pull-right">
                    {!! Form::select('g2' , $opcoesVisualizacoes , Request::get('g2') ?: 'column', ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="box-body">
                <div id="grafico-cupons-loja"></div>
                <table id="tabelacupons-loja" class="hide">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Cupons resgatados</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($franquias as $franquia)
                        <tr>
                            <th>{!! $franquia->nome !!}</th>
                            <td>{{ $franquia->cupons_resgatados->count() }}</td>
                        </tr>
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
            $('#grafico-cupons-loja').highcharts({
                data: {
                    table: 'tabelacupons-loja'
                },
                chart: {
                    type: '{!! Request::get('g2') ?: 'column' !!}'
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

            $('[name=g2]').change(function(){
                var g1 = $('[name=g1]').val();
                var params = $.param({
                    g1: g1,
                    g2: $(this).val()
                });
                window.location.href='{{ url()->current() }}?' + params;
            });
        });
    </script>
@endsection