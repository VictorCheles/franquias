<div class="modal modal-new-product fade">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="system-alerts">
                <div class="alert alert-warning alert-dismissible" role="alert" style="border-radius: 0;">
                    <strong>Aviso!</strong> Os valores serão atualizados apenas após concluir a edição do pedido
                </div>
            </div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Adicionar produto ao pedido</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            {!! Form::label('novo_produto', 'Produto') !!}
                            {!! Form::select('novo_produto' , [] , null, ['id' => 'novo_produto', 'placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            {!! Form::label('novo_produto_quantidade', 'Quantidade') !!}
                            <input id="novo_produto_quantidade" min="1" type="number" class="form-control" name="novo_produto_quantidade" value="1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-success btn-add-product pull-left" data-dismiss="modal">Adicionar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('.open-modal-new-product').click(function (e) {
                e.preventDefault();
                $('.modal-new-product').modal();
            });
        });
    </script>
@endsection