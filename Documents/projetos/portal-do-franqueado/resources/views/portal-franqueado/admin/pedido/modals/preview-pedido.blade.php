<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Preview do Pedido automático</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
            <div class="modal-body">
                <div class="row">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Nome</th>
                            <th>Valor unitário</th>
                            <th>Quantidade</th>
                            <th>Peso</th>
                            <th>Subtotal</th>
                        </tr>
                        <?php $total = 0;?>
                        @foreach($produtos as $produto)
                            @php
                            if($produto['disponivel']) {
                                $total += $produto['model']['pivot']->quantidade * $produto['model']->preco;
                            }
                            @endphp
                            <tr {!! $produto['disponivel'] ? '' : 'class="lineup"' !!}>
                                <td>{{ $produto['model']->nome }}</td>
                                <td>{{ maskMoney($produto['model']->preco) }}</td>
                                <td>{{ $produto['media_quantidade'] }}</td>
                                <td>{{ $produto['model']->peso * $produto['media_quantidade'] }}</td>
                                <td>{{ maskMoney($produto['model']['pivot']->quantidade * $produto['model']->preco) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <td>{{ maskMoney($total) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default pull-left', 'data-dismiss' => 'modal']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.modal-filter').modal();
        });
    </script>
@endsection