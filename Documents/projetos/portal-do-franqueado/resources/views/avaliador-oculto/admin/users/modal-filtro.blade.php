<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 65% !important;">
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
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('filter[nome]' , Request::input('filter.nome') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('data_cadastro', 'Data de cadastro') !!}
                        {!! Form::text('filter[data_cadastro]' , Request::input('filter.data_cadastro') , ['id' => 'data_cadastro','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('uf', 'Estado') !!}
                        {!! Form::select('filter[uf]' , \App\Models\AvaliadorOculto\User::MAPA_UFS , Request::input('filter.uf') , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        {!! Form::label('cidade', 'Cidade') !!}
                        {!! Form::text('filter[cidade]' , Request::input('filter.cidade') , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-6">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('filter[quem_respondeu]', 1, Request::input('filter.quem_respondeu') ? true : false) !!}
                                Apenas quem respondeu algo
                            </label>
                        </div>
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
            var filter_data = "{{ Request::input('filter.data_cadastro') }}";

            $('.open-modal-filter').click(function(e){
                e.preventDefault();
                $('.modal-filter').modal();
            });

            $('#data_cadastro').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            if(filter_data.length === 0)
            {
                $('#data_cadastro').val('');
            }
        });
    </script>
@endsection