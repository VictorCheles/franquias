@if($lista->count() > 0)
    @foreach($lista as $item)
        <div class="modal modal-remover-{{ $item->id }} fade modal-danger">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Remover a promoção</h4>
                    </div>
                    <div class="modal-body">
                        <h2>{{ $item->nome }}</h2>
                        <p>Esta operação não pode ser desfeita</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-outline pull-left" data-dismiss="modal">Fechar</button>
                        <form method="post" action="{{ url('/backend/promocoes/excluir') }}">
                            <input type="hidden" name="promocao_id" value="{{ $item->id }}">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-flat btn-outline">Excluir</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    @endforeach
@endif
@section('extra_scripts')
    @parent
    <script>
        $(function () {
            $('[rel=modal-remover]').click(function (e) {
                e.preventDefault();
                var id = $(this).data('modal');
                $('.modal-remover-' + id.toString()).modal();
            });
        });
    </script>
@endsection