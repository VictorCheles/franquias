@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header">
                    <h3 class="box-title">{{ $franquias->pluck('nome')->implode(' - ') }}</h3>
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
                            <th>Data</th>
                            <th>Promoção</th>
                            <th>Cupons resgatados</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            <?php $total = 0; ?>
                            @foreach($lista as $item)
                                <?php $total += $item->total; ?>
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->data)->format('d/m/Y') }}</td>
                                    <td>{{ $item->promocao }}</td>
                                    <td>{{ $item->total }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2" style="border-top: 1px solid #dd4b39">Total: </th>
                                <td style="border-top: 1px solid #dd4b39">{{ $total }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="3">Nenhum cupon foi usado neste dia</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    @include('backend.bi.modals.filtro-cupons-por-dia-caixa')
@endsection