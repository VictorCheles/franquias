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
                    <div class="form-group col-sm-6">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('filter[nome]' , Request::input('filter.nome') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('email', 'E-mail') !!}
                        {!! Form::email('filter[email]' , Request::input('filter.email') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('nivel_acesso', 'Nível de Acesso') !!}
                        {!! Form::select('filter[nivel_acesso]' , \App\User::$mapAcesso , Request::input('filter.nivel_acesso'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('grupo_id', 'Grupo') !!}
                        {!! Form::select('filter[grupo_id]' , \App\ACL\Grupo::orderBy('nome')->pluck('nome','id')->toArray() , Request::input('filter.grupo_id'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('filter[status]' , \App\User::$mapStatus , Request::input('filter.status'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('loja_id', 'Franquia') !!}
                        {!! Form::select('filter[loja_id]' , \App\Models\Loja::orderBy('nome', 'asc')->lists('nome', 'id') , Request::input('filter.loja_id'), ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::submit('Filtrar', ['class' => 'btn btn-flat btn-primary']) !!}
                {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default pull-left', 'data-dismiss' => 'modal']) !!}
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