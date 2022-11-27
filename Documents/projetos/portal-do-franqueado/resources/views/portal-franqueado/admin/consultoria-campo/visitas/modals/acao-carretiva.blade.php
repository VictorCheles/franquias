<div class="modal modal-acao-corretiva-{{ $pergunta->id }} fade" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 85% !important; margin: 30px auto;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ação corretiva para:<br> {{ $pergunta->pergunta }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-12">
                        {!! Form::label('acao_corretiva['. $pergunta->id .'][descricao]', 'Descrição') !!}
                        {!! Form::textarea('acao_corretiva['. $pergunta->id .'][descricao]' , '', ['class' => 'form-control turn-required-' . $pergunta->id]) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('acao_corretiva['. $pergunta->id .'][data_correcao]', 'Data para correção') !!}
                        {!! Form::text('acao_corretiva['. $pergunta->id .'][data_correcao]' , '', ['class' => 'form-control datepicker turn-required-' . $pergunta->id]) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-flat btn-primary" style="width: 100%;" data-dismiss="modal">Concluir</a>
            </div>
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-modal-acao-corretiva').click(function(e){
                var id = $(this).data('modal');
                $('.turn-required-' + id).each(function(i, item){
                    $(item).attr('required', 'required');
                    $(item).attr('oninvalid', 'swal(\'Preencha todos os dados de ação corretiva\');');
                });
                $('.modal-acao-corretiva-' + id).modal({close_on_background_click: false});
            });

            $('.remove-required').click(function(){
                var id = $(this).data('modal');
                $('.turn-required-' + id).each(function(i, item){
                    $(item).removeAttr('required');
                    $(item).removeAttr('oninvalid');
                });
            });
            $('.datepicker').datepicker( "option", "dateFormat", 'yy-mm-dd' );
        });
    </script>
@endsection