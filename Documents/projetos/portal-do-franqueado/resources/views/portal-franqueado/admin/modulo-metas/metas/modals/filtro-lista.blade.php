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
                    <div class="form-group col-sm-12">
                        {!! Form::label('periodo', 'Período') !!}
                        {!! Form::text('filter[periodo]' , Request::get('filter.periodo'), ['class' => 'form-control periodo']) !!}
                    </div>
                    @if(Auth::user()->isAdmin())
                        <div class="form-group col-sm-12">
                            {!! Form::label('loja_id', 'Lojas') !!}
                            {!! Form::select('filter[loja_id][]' , \App\Models\Loja::pluck('nome', 'id')->toArray() , Request::input('filter.loja_id'), ['class' => 'form-control select2', 'multiple' => true]) !!}
                        </div>
                    @endif
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
                $('.modal-filter').on('shown.bs.modal', function (e) {
                    if(!$(this).hasClass('opened'))
                    {
                        $(this).addClass('opened');
                        $('.select2').select2({
                            language: 'pt-BR'
                        });

                        $periodo = $('.periodo');

                        console.log($periodo.val());
                        if($periodo.val() === '') {
                            $periodo.daterangepicker({
                                locale: {
                                    applyLabel: 'Selecionar',
                                    cancelLabel: 'Cancelar',
                                    format: 'DD/MM/YYYY',
                                    language: 'pt-BR'
                                }
                            }).on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                            }).val('')
                        } else {
                            $periodo.daterangepicker({
                                locale: {
                                    applyLabel: 'Selecionar',
                                    cancelLabel: 'Cancelar',
                                    format: 'DD/MM/YYYY',
                                    language: 'pt-BR'
                                }
                            }).on('cancel.daterangepicker', function(ev, picker) {
                                $(this).val('');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection