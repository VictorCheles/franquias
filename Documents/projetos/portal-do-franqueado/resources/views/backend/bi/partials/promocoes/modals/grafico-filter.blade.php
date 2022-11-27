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
                        {!! Form::label('promocoes', 'Promoções') !!}
                        {!! Form::select('filter[promocoes][]', $selectPromocoes, Request::input('filter.promocoes') ?: null, ['class' => 'multiselect form-control' ,'multiple']) !!}
                    </div>
                    <div class="form-group col-sm-6 col-xs-12">
                        {!! Form::label('periodo', 'Período') !!}
                        {!! Form::text('filter[periodo]', Request::input('periodo') , ['id' => 'periodo' ,'class' => 'form-control']) !!}
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
        $(function () {
            $('.open-modal-filter').click(function (e) {
                e.preventDefault();
                $('.modal-filter').modal();
                $('.modal-filter').on('shown.bs.modal', function (e) {
                    $('select.multiselect').select2();
                });
            });

            var periodo_val = $('#periodo').val();
            var $periodo = $('#periodo').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            if(periodo_val === '')
            {
                $periodo.val('');
            }
        });
    </script>
@endsection