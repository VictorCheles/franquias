<div class="modal modal-notificacao fade">
    <div class="modal-dialog" style="width: 65% !important; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nova notificação</h4>
            </div>
            {{--{!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}--}}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('uf', 'Estado') !!}
                        {!! Form::select('notificacao_id' , $notificacoes->pluck('nome_valor_formatted', 'id')->toArray() , null, ['placeholder' => 'Notificação', 'class' => 'form-control', 'id' => 'notificacao_id']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('quantidade', 'Quantidade') !!}
                        {!! Form::number('quantidade' , 1, ['class' => 'form-control', 'id' => 'quantidade']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Cancelar', ['class' => 'btn btn-flat btn-default pull-left', 'data-dismiss' => 'modal']) !!}
                <a href="#" class="btn btn-flat btn-primary adicionar-nova-notificacao" data-dismiss="modal">Adicionar</a>
            </div>
            {{--{!! Form::close() !!}--}}
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>

        var notificacoes = {!! $notificacoes->toJson() !!};
        $.each(notificacoes, function(i, n){
            n.isUsed = false;
        });

        function limparNotificacao()
        {
            $('#notificacao_id').val($('#notificacao_id option:first').val());
            $('#quantidade').val(1);
        }

        function adicionarRowNotificacao(notificacao, quantidade)
        {
            var template = `
            <tr>
                <td><input type="hidden" name="notificacao[`+ notificacao.id +`]" value="`+ notificacao.id +`">`+ notificacao.descricao +`</td>
                <td>`+ notificacao.valor_un +`</td>
                <td><input name="notificacao[`+ notificacao.id +`][quantidade]" type="hidden" value="`+ quantidade +`">`+ quantidade +`</td>
                <td>`+ (notificacao.valor_un * quantidade) +`</td>
                <td><a href="#" class="btn btn-default btn-flat remover-notificacao" data-id="`+ notificacao.id +`"><i class="fa fa-times"></i> Remover</a></td>
            </tr>
            `;

            $('#notificacoes-list').append(template);
        }

        $(function(){
            $('.adicionar-nova-notificacao').click(function(){
                var id = $('#notificacao_id').val();
                var quantidade = $('#quantidade').val();
                var notifi = $.grep(notificacoes, function(e){ return e.id == id; });
                if(notifi.length > 0 && !notifi[0].isUsed)
                {
                    notifi[0].isUsed = true;
                    n = notifi[0];
                    adicionarRowNotificacao(n, quantidade);
                }
                limparNotificacao();
            });

            $('.nova-notificacao').click(function(e){
                e.preventDefault();
                $('.modal-notificacao').modal();
            });

            $('#notificacoes-list').on('click', '.remover-notificacao', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var notifi = $.grep(notificacoes, function(e){ return e.id == id; });
                if(notifi && notifi[0])
                {
                    notifi[0].isUsed = false;
                }
                $(this).parent().parent().remove();
            });
        });
    </script>
@endsection