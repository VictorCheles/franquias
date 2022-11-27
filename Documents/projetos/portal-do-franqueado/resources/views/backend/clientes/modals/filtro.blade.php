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
                        {!! Form::text('filter[email]' , Request::input('filter.email') , ['class' => 'form-control']) !!}
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