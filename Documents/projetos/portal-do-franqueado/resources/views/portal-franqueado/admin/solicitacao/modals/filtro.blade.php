<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 80% !important; margin: 2% auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filtro de solicitações</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get', 'id' => 'real-filter']) !!}
            <div class="modal-body">
                <div class="row">
                    <input id="palavra_chave" type="hidden" name="filter[palavra_chave]" value="{{ Request::input('filter.palavra_chave') }}">
                    <div class="form-group col-sm-6">
                        {!! Form::label('setor', 'Setor') !!}
                        {!! Form::select('filter[setor]' , $setores->pluck('nome', 'id') , Request::input('filter.setor'), ['placeholder' => 'Selecione um setor' ,'class' => 'form-control select2']) !!}
                    </div>
                    @if($auth->isAdmin())
                        <div class="form-group col-sm-6">
                            {!! Form::label('solicitante', 'Solicitante') !!}
                            {!! Form::select('filter[solicitante]' , \App\User::orderBy('nome', 'asc')->lists('nome', 'id') ,Request::input('filter.solicitante'), ['placeholder' => 'Selecione um solicitante' ,'class' => 'form-control select2']) !!}
                        </div>
                    @endif
                    <div class="form-group col-sm-6">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('filter[status]' , collect(\App\Models\Solicitacao::$mapStatus)->put('todos' ,'Todos')->toArray() ,Request::input('filter.status'), ['placeholder' => 'Selecione um status' ,'class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('prazo', 'Prazo') !!}
                        {!! Form::select('filter[prazo]' , \App\Models\Solicitacao::$mapPrazo ,Request::input('filter.prazo'), ['placeholder' => 'Selecione um prazo' ,'class' => 'form-control select2']) !!}
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
            var jafoi = false;
            $('.open-modal-filter').click(function(e){
                e.preventDefault();
                $('.modal-filter').modal();
            });
            $('.modal-filter').on('shown.bs.modal', function() {
                if(!jafoi)
                {
                    jafoi = true;
                    $('.select2').select2({
                        language: 'pt-BR'
                    });
                }

            })
        });
    </script>
@endsection