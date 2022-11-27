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
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="model">Usuário</label>
                        <select class="form-control" name="filters[user_id]">
                            <option value>Usuário</option>
                            @foreach($users as $id => $user)
                                <option value="{{ $id }}" @if(request('filters.user_id') == $id) selected @endif>{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="model">Método</label>
                        <select class="form-control" name="filters[method]">
                            <option value="">Método</option>
                            @foreach($methods as $method)
                                <option value="{{ $method }}" @if(request('filters.method') == $method) selected @endif>{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="model">Status</label>
                        <select class="form-control" name="filters[status]">
                            <option value="">Todos</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @if(request('filters.status') == $status) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="created">Domínio</label>
                        <select class="form-control" name="filters[domain]">
                            <option value="">Todos</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain }}" @if(request('filters.domain') == $domain) selected @endif>{{ $domain }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="created">Caminho</label>
                        <input type="text" class="form-control" name="filters[url]" value="{{ request('filters.url') }}">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="created">Endereço IP</label>
                        <input type="text" class="form-control" name="filters[ip]" value="{{ request('filters.ip') }}">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        <label for="created">Início</label>
                        <input type="text" class="form-control" name="filters[start_at]" value="{{ request('filters.start_at') }}">
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
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