@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th colspan="4"></th>
                            <th colspan="4" class="text-center" style="border-left: 2px solid #f4f4f4;border-right: 2px solid #f4f4f4;">Cupons</th>
                            <th colspan="1"></th>
                        </tr>
                        <tr>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Início</th>
                            <th>Fim</th>
                            <th style="border-left: 2px solid #f4f4f4;">Máximo</th>
                            <th>Gerados</th>
                            <th>Usados</th>
                            <th style="border-right: 2px solid #f4f4f4;">Vencidos</th>
                            <th>Opções</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($lista->count() > 0)
                            @foreach($lista as $item)
                                <?php $modificadoPor = $item->modificadorPor()->first(); ?>
                                <tr>
                                    <td>
                                        <a class="colorbox" href="/uploads/{{ $item->imagem }}">
                                            <img height="50" width="50" src="/uploads/{{ $item->imagem }}">
                                        </a>
                                    </td>
                                    <td width="20%">{{ $item->nome }}</td>
                                    <td>{!! $item->status_formatted !!}</td>
                                    <td>{!! $item->inicio->format('d/m/Y') . '<br>' . $item->inicio->diffForHumans() !!}</td>
                                    <td>{!! $item->fim->format('d/m/Y') . '<br>' . $item->fim->diffForHumans() !!}</td>
                                    <td>{{ $item->max_cupons_usados_formatted }}</td>
                                    <td>{{ $item->cupons_criados }}</td>
                                    <td>{{ $item->cupons_usados }} ({{ $item->cupons_usados_porcentagem_formatted }})</td>
                                    <td>{{ $item->cupons_vencidos->count() }}</td>
                                    <td class="options">
                                        <div class="btn-group">
                                            <a href="{{ url('/backend/promocoes/editar', $item->id) }}" class="btn btn-flat btn-default"><i rel="tooltip" title="Editar" class="fa fa-edit"></i></a>
                                            <a href="#" rel="modal" data-modal="{{ $item->id }}" class="btn btn-flat btn-default"><i rel="tooltip" title="Mais informações" class="fa fa-eye"></i></a>
                                            @if($item->cupons()->count() == 0)
                                                <a href="#" rel="modal-remover" data-modal="{{ $item->id }}" class="btn btn-flat btn-default"><i rel="tooltip" title="Excluir promoção" class="fa fa-trash"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">Nenhuma promoção cadastrada</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="center pagination-red">
                        {{ $lista->appends(Request::all())->links() }}
                    </div>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
    @include('backend.promocoes.modals.lista-modals')
    @include('backend.promocoes.modals.lista-modals-remover')
@endsection
@section('extra_scripts')
    <script>
        $(function () {
            $('a.colorbox').colorbox({
                rel: 'colorbox',
                width: '50%'
            });
        });
    </script>
@endsection