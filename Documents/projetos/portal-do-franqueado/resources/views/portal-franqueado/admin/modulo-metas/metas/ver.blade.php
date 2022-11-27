@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Módulo de metas - Meta {{ $item->titulo }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row" id="root">
        <div id="container2" style="height:400px; width: 100%;"></div>
        <div id="container" style="height:300px; width: 100%;"></div>

        <div class="col-sm-12">
            <div class="box box-black box-solid">

                <div class="box-header with-border text-center">
                    <h3 class="box-title">Meta {{ $item->titulo }}</h3>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Início:</th>
                            <td>{{ $item->inicio->format('d/m/Y') }}</td>
                            <th>Fim:</th>
                            <td>{{ $item->fim->format('d/m/Y') }}</td>
                            <th>Métrica:</th>
                            <td>{{ $item->metrica }}</td>
                            <th>Valor:</th>
                            <td>{{ $item->valor }}</td>
                            <th>Loja:</th>
                            <td>{{ $item->loja->nome }}</td>
                        </tr>
                    </table>
                    <div class="box-group" id="accordion">
                        <div class="panel box box-default box-solid">
                            <div class="box-header with-border">
                                <div class="row">
                                    <div class="col-xs-7">
                                        <h4 class="box-title">Atividades da meta</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                    $index = 0;
                                    @endphp
                                    @foreach($item->atividades as $atividade)

                                        <tr>
                                            <td>{{ $atividade->descricao }}</td>
                                            <td>{{ $atividade->valor }}</td>
                                            <td class="options">
                                                @include ('portal-franqueado.admin.modulo-metas.metas.modals.editar-atividade')

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default">Opções</button>
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_METAS_EDITAR]))
                                                            <li>
                                                                <button type="button" data-toggle="modal" data-target="#modalEditarAtividade{{$index++}}" class="btn btn-sm btn-default" style="width: 100%;">
                                                                    <i class="fa fa-edit"></i> Editar
                                                                </button>
                                                            </li>
                                                        @endif
                                                        @if($user->hasRoles([\App\ACL\Recurso::ADM_METAS_DELETAR]))
                                                            <li>
                                                                <form class="swal-confirmation" action="{{ route('admin.modulo-de-metas.atividades.destroy', $atividade->id) }}" method="POST" style="display: inline;">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <a href="#" class="btn btn-flat btn-default fake-submit" style="width: 100%"><i class="fa fa-trash"></i> Deletar</a>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $item->atividades->sum('valor') }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    @if($user->hasRoles([\App\ACL\Recurso::ADM_METAS_ATIVIDADE_CRIAR]))
                        <button type="button" class="btn btn-flat btn-primary pull-right" data-toggle="modal" data-target="#modalAtividade">Adicionar atividade</button>
                    @endif
                    {!! link_to(route('modulo-de-metas'), 'Voltar', ['class' => 'btn btn-flat btn-danger pull-left']) !!}
                </div>
            </div>
        </div>
    </div>
    @include($views_path . 'modals.nova-atividade')
@endsection
@section('extra_scripts')
    @parent
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script>
        Highcharts.chart('container', {
            colors: ['#00a65a'], /*cor da linha */
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: 'Pontos' /*trocar para datas ou semanas, sentar c Jean p ver como fazer*/
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    pointStart: 0
                }
            },
            xAxis: {
                categories: {!! json_encode($item->atividades->pluck('descricao')) !!}
            },

            series: [{
                name: 'Pontuação',
                data: {!! json_encode($atividades_grafico->pluck('valor')) !!}
            }],
            credits : { enabled: false }

        });

        Highcharts.chart('container2', {
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
                            if (this.y !== 0) {
                                return Highcharts.numberFormat(this.y) + '%';
                            }
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
    </script>
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                        title: "Deletar atividade?",
                        text: "Esta operação não pode ser desfeita. Deseja continuar?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, deletar Atividade",
                        closeOnConfirm: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            form.submit();
                        } else {
                            return false;
                        }
                    });
                return false;
            });
        });
    </script>
@endsection
