<div class="modal modal-filter fade">
    <div class="modal-dialog" style="width: 65% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Filtro por dia</h4>
            </div>
            {!! Form::open(['url' => url()->current(), 'method' => 'get']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        {!! Form::label('data', 'Data (início)') !!}
                        {!! Form::text('filter[data]', Request::input('filter.data'), ['class' => 'form-control date-range']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::label('franquia', 'Franquia') !!}
                        {!! Form::select('filter[franquia]', \App\Models\Loja::pluck('nome', 'id')->toArray(), Request::input('filter.franquia'), ['class' => 'form-control', 'placeholder' => 'Selecione uma franquia']) !!}
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
        $(function () {
            $('.open-modal-filter').click(function (e) {
                e.preventDefault();
                $('.modal-filter').modal();
            });

            var range = $('.date-range').daterangepicker({
                locale: {
                    applyLabel: 'Selecionar',
                    cancelLabel: 'Cancelar',
                    format: 'DD/MM/YYYY',
                    language: 'pt-BR'
                }
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            @if(!Request::input('filter.data'))
                range.val('');
            @endif
        });
    </script>
@endsection