<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 80% !important; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filtro</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('data', 'Data de cadastro') !!}
                        {!! Form::text('filter[data]' , Request::input('filter.data') , ['id' => 'data','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('status', 'Formulário') !!}
                        {!! Form::select('filter[status]' , \App\Models\ConsultoriaCampo\AcaoCorretiva::$mapStatus , Request::input('filter.status') , ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('loja_id', 'Loja') !!}
                        {!! Form::select('filter[loja_id]' , $lojas->pluck('nome', 'id')->toArray() , Request::input('filter.loja_id') , ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    {{--<div class="form-group col-sm-6">--}}
                        {{--{!! Form::label('user_id', 'Consultor') !!}--}}
                        {{--{!! Form::select('filter[user_id]' , $users->pluck('nome', 'id')->toArray() , Request::input('filter.user_id') , ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}--}}
                    {{--</div>--}}
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

            $('#data').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endsection