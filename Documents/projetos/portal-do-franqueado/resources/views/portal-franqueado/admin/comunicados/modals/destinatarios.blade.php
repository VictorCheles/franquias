@foreach($lista as $item)
    <div class="modal modal-destinatario-{{ $item->id }} fade">
        <div class="modal-dialog" style="width: 65% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Destinatários do comunicado "{{ $item->titulo }}"</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Destinatário</th>
                                <th>Leu o comunicado?</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->destinatarios as $dest)
                                <tr>
                                    <td>{{ $dest->user->nome }}</td>
                                    <td>{!! $dest->status_formatted !!}</td>
                                    <td>
                                        @if($dest->status)
                                            {{ $dest->updated_at->format('d/m/Y H:i:s') }}
                                            Lido depois de
                                            {{ $dest->updated_at->diffInHours($item->created_at) }}h
                                        @endif
                                    </td>
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
        $('.open-modal-destinatario').click(function(e){
            e.preventDefault();
            var rel = $(this).attr('rel');
            $('.modal-destinatario-' + rel).modal();
        });
    </script>
@endsection