@foreach($lista as $item)
    <div class="modal modal-quem-leu-{{ $item->id }} fade">
        <div class="modal-dialog" style="width: 80% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Quem assistiu o vídeo "{{ $item->titulo }}"</h4>
                </div>
                <div class="modal-body no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Quem assistiu</th>
                            <th>Loja</th>
                            <th>Hora</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($item->quemAssistiu()->orderBy('created_at', 'desc')->get() as $dest)
                            <?php $u = $dest->user()->first(); ?>
                            <tr>
                                <td>{{ $u->nome }}</td>
                                <td>{{ $u->lojas->pluck('nome')->implode(' | ') }}</td>
                                <td>{{ $u->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default pull-right" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@section('extra_scripts')
    @parent
    <script>
        $('.open-modal-quem-leu').click(function(e){
            e.preventDefault();
            var rel = $(this).attr('rel');
            $('.modal-quem-leu-' + rel).modal();
        });
    </script>
@endsection