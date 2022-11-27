<div class="modal fade" id="modalAtividade">
    <div class="modal-dialog" style="width: 65% !important; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Atividade para:<br> {{ $item->titulo }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {!! Form::open(['url' => route('admin.modulo-de-metas.atividades.store', $item->id)]) !!}
                    <div class="form-group col-sm-12">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::text('descricao' , '', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('valor', 'Valor da atividade') !!}
                        {!! Form::number('valor' , '', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="box-footer">
                    {!! Form::submit('Concluir', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>
