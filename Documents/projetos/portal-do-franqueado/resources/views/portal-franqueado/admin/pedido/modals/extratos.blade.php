@if($lista->count() > 0)
    @foreach($lista as $item)
        <div class="modal modal-{{ $item->id }} fade">
            <div class="modal-dialog" style="width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Extrato do pedido</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-responsive">
                            <tr>
                                <th>Produtos</th>
                                <td>
                                    {!! $item->produtos_formatted !!}
                                </td>
                            </tr>
                            <tr>
                                <th>Observações</th>
                                <td>{{ $item->observacoes }}</td>
                            </tr>
                            <tr>
                                <th>Solicitado em</th>
                                <td>
                                    {{ $item->created_at->format('d/m/Y \a\s H:i:s') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Data prevista de entrega</th>
                                <td>
                                    {{ $item->data_entrega ? $item->data_entrega->format('d/m/Y') : 'data ainda não definida' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Multa por atraso</th>
                                <td>{{ maskMoney($item->multa) }}</td>
                            </tr>
                            <tr>
                                <th>Peso total</th>
                                <td>{{ $item->pesoTotal() }}kg</td>
                            </tr>
                            <tr>
                                <th>Valor total</th>
                                <td>{{ maskMoney($item->valorTotal() + $item->multa) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-default pull-left" data-dismiss="modal">Fechar</button>
                        <a target="_blank" href="{{ route('pedido.imprimir', $item->id) }}" class="btn btn-flat btn-info"><i class="fa fa-print"></i> Imprimir</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    @endforeach
@endif
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('[rel=modal]').click(function (e) {
                e.preventDefault();
                var id = $(this).data('modal');
                $('.modal-' + id.toString()).modal();
            });
        });
    </script>
@endsection