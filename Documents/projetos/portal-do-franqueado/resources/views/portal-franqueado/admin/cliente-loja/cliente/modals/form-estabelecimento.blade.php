<div class="modal modal-estabelecimento fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Criar Estabelecimento</h4>
            </div>
            {!! Form::open(['url' => '#', 'id' => 'form-estabelecimento']) !!}
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                            {!! Form::label('nome', 'Nome do estabelecimento') !!}
                            {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-left']) !!}
                    <button type="button" class="btn btn-flat btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-model-estabelecimento').click(function(e){
                e.preventDefault();
                $('.modal-estabelecimento').modal();
            });

            $('#form-estabelecimento').submit(function(e){
                e.preventDefault();
                var url = '{{ route('clientes_loja_estabelecimento.store') }}';
                var data = $(this).serializeArray();
                $.post(url, data, function(data){

                }).success(function(data){
                    var select = $('#estabelecimento_id');
                    select.select2('destroy');
                    select.find('option:selected').remove();
                    select.append('<option selected="selected" value="'+ data.data.id +'">'+ data.data.nome +'</option>');
                    select.select2();
                    $('.modal-estabelecimento').modal('toggle');
                }).error(function(data){
                    swal('não foi');
                });
            });
        });
    </script>
@endsection