@foreach($lista as $item)
    <div class="modal modal-url-{{ $item->id }} fade">
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
                            <th>Arquivo</th>
                            <th width="70%">URL do arquivo</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $item->nome }}</td>
                                <td>
                                    <input class="form-control" value="{{ route('arquivo.download', $item->id) }}">
                                </td>
                            </tr>
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
        $('.open-modal-url').click(function(e){
            e.preventDefault();
            var rel = $(this).attr('rel');
            $('.modal-url-' + rel).modal();
        });
    </script>
@endsection