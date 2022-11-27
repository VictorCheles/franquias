@if($changes->count() > 0)
    @foreach($changes as $change)
        <div class="modal modal-payload-{{ $change->id }} fade">
            <div class="modal-dialog" style="width: 90%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Payload</h4>
                    </div>
                    <div class="modal-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Chanve</th>
                                    <th>Valor Antes</th>
                                    <th>Valor Depois</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $keys = array_keys($change->payload_before);
                                ?>
                                @foreach($keys as $key)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>
                                            @if(isset($change->payload_after[$key]))
                                                @if(is_array($change->payload_after[$key]))
                                                    <pre>
                                                        {{ print_r($change->payload_after[$key]) }}
                                                    </pre>
                                                @else
                                                    {{ $change->payload_after[$key] }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($change->payload_before[$key]))
                                                @if(is_array($change->payload_before[$key]))
                                                    <pre>
                                                        {{ print_r($change->payload_before[$key]) }}
                                                    </pre>
                                                @else
                                                    @if($change->created)
                                                        {{ $change->payload_before[$key] }}
                                                    @else
                                                        @if($change->payload_before[$key] != $change->payload_after[$key])
                                                            <span class="label label-danger" title="Registro modificado">{{ $change->payload_before[$key] }}</span>
                                                        @else
                                                            {{ $change->payload_before[$key] }}
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Fechar', ['class' => 'btn btn-flat btn-default', 'data-dismiss' => 'modal']) !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
@section('extra_scripts')
    @parent
    <script>
        $(function(){
            $('.open-modal-payload').click(function(e){
                e.preventDefault();
                var id = $(this).data('payload');
                $('.modal-payload-' + id).modal();
            });
            $('span[title]').tooltip();
        });
    </script>
@endsection