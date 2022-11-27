<div class="modal modal-notificacao fade">
    <div class="modal-dialog" style="width: 65% !important; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Nova notificação</h4>
            </div>
            {!! Form::open(['url' => route('admin.programa-de-qualidade.visitas.notificacao.create', $item->id), 'method' => 'put']) !!}
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
                {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.nova-notificacao').click(function(e){
                e.preventDefault();
                $('.modal-notificacao').modal();
            });
        });
    </script>
@endsection