<div class="modal fade" id="modal-filter">
    <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filtro de mensagens</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::label('palavra_chave', 'Palavra chave') !!}
                        {!! Form::text('filter[palavra_chave]' , Request::input('filter.palavra_chave'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('to_id', 'Destinatário') !!}
                        {!! Form::select('filter[from_id]' , $users , Request::input('filter.from_id'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default pull-left', 'data-dismiss' => 'modal']) !!}
                {!! Form::submit('Filtrar', ['class' => 'btn btn-flat btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>