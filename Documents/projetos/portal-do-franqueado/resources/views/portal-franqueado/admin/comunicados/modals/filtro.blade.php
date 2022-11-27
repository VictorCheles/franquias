<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filtro de comunicados</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('loja_id', 'Loja') !!}
                        {!! Form::select('filter[loja_id]' , \App\Models\Loja::orderBy('nome')->pluck('nome','id')->toArray(), Request::input('filter.loja_id') , ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('titulo', 'Título') !!}
                        {!! Form::text('filter[titulo]' , Request::input('filter.titulo'), ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('data', 'Data') !!}
                        {!! Form::text('filter[data]' , Request::input('filter.data'), ['class' => 'form-control date-range']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('setor', 'Setor') !!}
                        {!! Form::select('filter[setor]' , \App\Models\Setor::orderBy('nome')->pluck('nome','id')->toArray(), Request::input('filter.setor') , ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('palavra_chave', 'Palavra chave') !!}
                        {!! Form::text('filter[palavra_chave]' , Request::input('filter.palavra_chave') , ['class' => 'form-control']) !!}
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
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-modal-filter').click(function(e){
                e.preventDefault();
                $('.modal-filter').modal();
            });
        });
    </script>
@endsection