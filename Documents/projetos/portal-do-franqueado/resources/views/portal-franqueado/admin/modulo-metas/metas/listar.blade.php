@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Módulo de Metas - Lista de Metas
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @php
                $min = 300;
            @endphp

            @if($lista->count() > 0)
                <div id="container" style="width:100%; height:{{ $lista->count() * 75 > $min ? $lista->count() * 75 : $min }}px; margin-left: 2%"></div>
            @endif
            <div class="box box-black box-solid">
                <div class="box-header">
                    <!-- display inline essas benga -->
                    <h3 class="box-title">Lista</h3>
                    <div class="box-tools pull-right">
                        <a href="#" class="open-modal-filter btn-box-tool">
                            <i class="fa fa-filter"></i> Filtro
                        </a>
                        <a href="{{ url()->current() }}" class="btn-box-tool">
                            <i class="fa fa-close"></i> Limpar filtro
                        </a>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Meta</th>
                                <th>Loja</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th style="width: 40px">Progresso</th>
                                <th style="width: 170px">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <tr>
                                    <td>{{ $item->titulo }}</td>
                                    <td>{{ $item->loja->nome }}</td>
                                    <td>{{ $item->inicio->format('d/m/Y') }}</td>
                                    <td>{{ $item->fim->format('d/m/Y') }}</td>
                                    <td>{{ number_format($item->progresso, 2, ',', '.') . "%" }}</td>
                                    <td class="options">
                                        <div class="row">
                                            <div class="col-md-3"> 
                                                <a href="{{ route('admin.modulo-de-metas.metas.show', $item->id) }}" class="btn btn-default"><i title="Ver" class="fa fa-eye"></i></a>
                                            </div>
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_METAS_EDITAR]))
                                                <div class="col-md-3"> 
                                                    <a href="{{ route('admin.modulo-de-metas.metas.edit', $item->id) }}" class="btn btn-warning" rel="{{ $item->id }}"><i title="Editar" class="fa fa-edit"></i></a>
                                                </div>
                                            @endif
                                            @if($user->hasRoles([\App\ACL\Recurso::ADM_METAS_DELETAR]))
                                                <form class="swal-confirmation" action="{{ route('admin.modulo-de-metas.metas.destroy', $item->id) }}" method="POST" style="display: inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="col-md-5"> 
                                                        <a href="#"  class="btn btn-danger fake-submit" style="width: 100%"><i title="Deletar" class="fa fa-trash"></i></a>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                @if(Request::get('filter'))
                                    <td colspan="6">Nenhum registro encontrado</td>
                                @else
                                    <td colspan="6">Nenhum registro cadastrado</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                {{--<div class="box-footer">--}}
                    {{--<div class="center pagination-black">--}}
                        {{--{{ $lista->appends(Request::all())->links() }}--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div><!-- /.box -->
        </div>
    </div>
    @include('portal-franqueado.admin.modulo-metas.metas.modals.filtro-lista')
@endsection
@section('extra_scripts')
    @parent

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script>
        if($('#container').length > 0) {

            Highcharts.chart('container', {

                colors: ['#ff1a1a', '#00a65a'], /*só enquanto o cálculo da pontuação não tá certo*/
                chart: {
                    type: 'bar',
                    events: {
                        load: function (event) {
                            $('.js-ellipse').tooltip();
                        }
                    }
                },
                title: {
                    text: 'Metas'
                },
                xAxis: {
                    categories: {!! json_encode($lista->pluck('titulo')) !!},
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
                            formatter: function () {
                                if (this.y !== 0)
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
                credits: {enabled: false}
            });
        }

    </script>
    <script>
        $(function () {
            $('.swal-confirmation .fake-submit').click(function () {
                var form = $(this).parent().parent('form');
                swal({
                        title: "Deletar metas?",
                        text: "Esta operação não pode ser desfeita, todas as suas atividades também serão deletadas, deseja continuar?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, deletar Meta",
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