<div class="modal modal-destinatario fade">
    <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Destinatários do comunicado</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                {!! Form::text('busca' , null , ['id' => 'q', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <a href="#" class="btn btn-flat btn-info por-loja" style="width: 100%;">Por loja</a>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <a href="#" class="btn btn-flat btn-default por-praca" style="width: 100%;">Por praça</a>
                    </div>
                </div>
                <div class="row row-destinatarios">
                    <?php $i = 1;?>
                    <div class="col-xs-12 text-center"><div class="checkbox"><label><input class="check-all" data-check="1" type="checkbox">Marcar/Desmarcar todos</label></div></div>
                    @foreach($destinatarios as $destinatario)
                        <div data-find="{{ str_replace('<br>', ' ', $destinatario->fake_name) }}" class="col-xs-6 col-sm-2 width100">
                            <label for="checkbox-{{ $i }}">
                                <img src="{{ $destinatario->thumbnail }}" class="img-responsive" style="max-height: 75px; margin: 0 auto;">
                                {!! $destinatario->nome !!}
                            </label>
                            @if($item and $item->destinatarios->pluck('user.id')->search($destinatario->id) !== false)
                                <input class="checkboxdestinatarios" data-parent="1" type="checkbox" name="destinatario[]" id="checkbox-{{ $i++ }}" value="{{ $destinatario->id }}" checked>
                            @else
                                <input class="checkboxdestinatarios" data-parent="1" type="checkbox" name="destinatario[]" id="checkbox-{{ $i++ }}" value="{{ $destinatario->id }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-success pull-right" data-dismiss="modal">Concluir</button>
            </div>
        </div>
    </div>
</div>
@section('extra_scripts')
    @parent
    <script>
        $('.open-modal-destinatarios').click(function(e){
            e.preventDefault();
            $('.modal-destinatario').modal();
        });
    </script>
@endsection