<div class="modal fade" id="modalEditarAtividade{{ $index }}">
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
                    {!! Form::open(['url' => route('admin.modulo-de-metas.atividades.update', $atividade->id)]) !!}
                    <div class="form-group col-sm-12">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::text('descricao' , $atividade->descricao, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('valor', 'Valor da atividade') !!}
                        {!! Form::number('valor' , $atividade->valor, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="box-footer">
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>
