<div class="modal modal-metas fade">
    <div class="modal-dialog" style="width: 95% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h3 class="modal-title text-center">Metas</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box-header">
                            <?php $min = 300;?>
                            <div id="container" style="width:100%; height:{{ $metas->count() * 75 > $min ? $metas->count() * 75 : $min }}px;"></div>
                        </div><!-- /.box -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default pull-left', 'data-dismiss' => 'modal']) !!}
                <a href="{{ route('modulo-de-metas') }}" class="btn btn-flat btn-default pull-right">Ver mais</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        Highcharts.chart('container', {
            //colors: ['#ff1a1a','#f39c12','#00a65a'], /*cor das barras*/
            colors: ['#ff1a1a','#00a65a'], /*só enquanto o cálculo da pontuação não tá certo*/
            chart: {
                type: 'bar',
                events: {
                    load: function (event) {
                        $('.js-ellipse').tooltip();
                    }
                }
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: {!! json_encode($metas->pluck('titulo')) !!},
                title: {
                    text: null
                },
                labels: {
                    formatter: function () {
                        var text = this.value,
                            formatted = text.length > 25 ? text.substring(0, 25) + '...' : text;

                        return '<div class="js-ellipse" style="width:150px; overflow:hidden" title="' + text + '">' + formatted + '</div>';
                    },
                    style: {
                        width: '150px'
                    },
                    useHTML: true
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Pontuação'
                }
            },
            tooltip: {
                pointFormat: '<b>{point.y:.2f}%</b>'
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            if( this.y != 0)
                                return Highcharts.numberFormat(this.y) + '%';
                        }
                    }
                },
                series: {
                    stacking: 'percent'
                }
            },
            series: [{
                name: 'Não Concluído',
                data: {!! json_encode($pontos_pendentes) !!}
            }, {
                name: 'Progresso',
                data: {!! json_encode($pontos_feitos) !!}
            }],
            credits : { enabled: false }
        });
        $(function(){
            $('.modal-metas').modal();
        });
    </script>
@endsection