@foreach($lista as $item)
    <div class="modal modal-destinatario-{{ $item->id }} fade">
        <div class="modal-dialog" style="width: 65% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Download do arquivo "{{ $item->nome }}"</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Quem baixou</th>
                            <th>Quantas vezes</th>
                            <th>Primeiro download</th>
                            <th>Ultimo download</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($item->downloads->count() > 0)
                                @foreach($item->downloads->sortBy('created_at') as $download)
                                    <tr>
                                        <td>{{ $download->user->nome }}</td>
                                        <td>{{ $download->downloads }}</td>
                                        <td>{{ $download->created_at->format('d/m/Y \a\s H:i:s') }}</td>
                                        <td>{{ $download->updated_at->format('d/m/Y \a\s H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">Ninguem</td>
                                </tr>
                            @endif
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
        $('.open-modal-downloads').click(function(e){
            e.preventDefault();
            var rel = $(this).attr('rel');
            $('.modal-destinatario-' + rel).modal();
        });
    </script>
@endsection