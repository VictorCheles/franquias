@if($lista->count() > 0)
    @foreach($lista as $item)
        <div class="modal modal-{{ $item->id }} fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">{{ $item->nome }}</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-responsive">
                            <tr>
                                <th>Foto</th>
                                <td>{!! $item->foto ? '<img class="img-responsive" src="' . $item->foto . '">' : 'sem foto' !!}</td>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <td>{{ $item->nome }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $item->email }}</td>
                            </tr>
                            <tr>
                                <th>RG</th>
                                <td>{{ $item->rg }}</td>
                            </tr>
                            <tr>
                                <th>CPF</th>
                                <td>{{ $item->cpf }}</td>
                            </tr>
                            <tr>
                                <th>Data nascimento</th>
                                <td>
                                    {{ $item->data_nascimento ? $item->data_nascimento->format('d/m/Y') : '' }}
                                    <br>
                                    Idade: {{ $item->idade }}
                                </td>
                            </tr>
                            <tr>
                                <th>Cidade</th>
                                <td>{{ $item->cidade }}</td>
                            </tr>
                            <tr>
                                <th>Escolaridade</th>
                                <td>{{ $item->escolaridade_formatted }}</td>
                            </tr>
                            <tr>
                                <th>Banco</th>
                                <td>{{ $item->banco ? $item->banco->nome : '' }}</td>
                            </tr>
                            <tr>
                                <th>Agencia</th>
                                <td>{{ $item->agencia }}</td>
                            </tr>
                            <tr>
                                <th>Conta Corrente</th>
                                <td>{{ $item->conta_corrente }}</td>
                            </tr>
                            <tr>
                                <th>Aceite</th>
                                <td>{{ $item->aceite_formatted }}</td>
                            </tr>
                            <tr>
                                <th>Data do aceite</th>
                                <td>{{ $item->data_aceite ? $item->data_aceite->format('d/m/Y H:i:s') : '' }}</td>
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