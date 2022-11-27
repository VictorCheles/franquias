<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filtro de usuários</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="model">Modelo</label>
                        <select class="form-control" name="filters[model]">
                            <option value="">Todos</option>
                            @foreach($models as $key => $model)
                                <option value="{{ $key }}" @if(request('filters.model') == $key) selected @endif>{{ $model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="created">Novo?</label>
                        <select class="form-control" name="filters[created]">
                            <option value="">Todos</option>
                            <option value="1" @if(request('filters.created') === '1') selected @endif>Sim</option>
                            <option value="0" @if(request('filters.created') === '0') selected @endif>Não</option>
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="created">Início</label>
                        <input type="text" class="form-control" name="filters[start_at]" value="{{ request('filters.start_at') }}">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="created">Fim</label>
                        <input type="text" class="form-control" name="filters[end_at]"  value="{{ request('filters.end_at') }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::submit('Filtrar', ['class' => 'btn btn-flat btn-primary pull-left']) !!}
                {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default', 'data-dismiss' => 'modal']) !!}
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