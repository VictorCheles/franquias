@if($lista->count() > 0)
    @foreach($lista as $item)
        <div class="modal modal-{{ $item->id }} fade">
            <div class="modal-dialog" style="width: 75%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">{{ $item->nome }}</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-responsive">
                            <tr>
                                <th>Imagem</th>
                                <td><img class="img-responsive" src="/uploads/{{ $item->imagem }}"></td>
                            </tr>
                            <tr>
                                <th>URL</th>
                                <td>
                                    <input readonly class="form-control" value="{{ url('/promocao/' . $item->id) }}">
                                </td>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <td>{{ $item->nome }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{!! $item->status_formatted !!}</td>
                            </tr>
                            <tr>
                                <th>Descricao</th>
                                <td>{!! $item->descricao !!}</td>
                            </tr>
                            <tr>
                                <th>Regulamento</th>
                                <td>{!! $item->regulamento !!}</td>
                            </tr>
                            <tr>
                                <th>Texto Mobile</th>
                                <td>{{ $item->texto_mobile }}</td>
                            </tr>
                            <tr>
                                <th>Início</th>
                                <td>{!!  '<b>' . $item->inicio->format('d/m/Y') . '</b> - ' . $item->inicio->diffForHumans() !!}</td>
                            </tr>
                            <tr>
                                <th>Fim</th>
                                <td>{!!  '<b>' . $item->fim->format('d/m/Y') . '</b> - ' . $item->fim->diffForHumans() !!}</td>
                            </tr>
                            <tr>
                                <th>Ordem</th>
                                <td>{{ $item->ordem }}</td>
                            </tr>
                            <tr>
                                <th>Validade dos cupons</th>
                                <td>{{ $item->validade_cupom . ' ' . str_plural('dia' , (int) $item->validade_cupom) }}</td>
                            </tr>
                            <tr>
                                <th>Máximo de cupons usados</th>
                                <td>{{ $item->max_cupons_usados }}</td>
                            </tr>
                            <tr>
                                <th>Cupons gerados</th>
                                <td>{{ $item->cupons_criados }}</td>
                            </tr>
                            <tr>
                                <th>Cupons usados</th>
                                <td>{{ $item->cupons_usados }}</td>
                            </tr>
                            <tr>
                                <th>Promoção criada por</th>
                                <td>{{ $item->user()->first()->nome }}</td>
                            </tr>
                            <tr>
                                <th>Ultima modificação feita por</th>
                                <td>{{ $item->modificador_por ? $item->modificadorPor()->first()->nome : '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fechar</button>
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