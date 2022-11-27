@if($lista->count() > 0)
    @foreach($lista as $item)
        <div class="modal modal-{{ $item->id }} fade">
            <div class="modal-dialog" style="width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Enquete: {{ $item->nome }}</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Pergunta</th>
                                    <th>Possiveis respostas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->perguntas as $pergunta)
                                    <tr>
                                        <td>{{ $pergunta->pergunta }}</td>
                                        <td>
                                            {!! $pergunta->respostas->pluck('resposta')->implode('<br>') !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Fechar</button>
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
            $('.open-modal').click(function (e) {
                e.preventDefault();
                var id = $(this).data('rel');
                $('.modal-' + id.toString()).modal();
            });
        });
    </script>
@endsection