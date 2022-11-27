<div class="box-body">
    <div class="col-xs-12">
        <h4>Selecionar Destinatários</h4>
    </div>
    <div class="row" style="margin: unset;">
        <div class="col-xs-12 col-sm-3">
            <div class="form-group">
                {!! Form::label('periodo_importancia', 'Período de importância') !!}
                <div class="input-group">
                    @if($hasImportante)
                        <small>
                            <i>
                                Existe um comunicado importante em vigência<br>
                                de {{ $hasImportante->inicio_importancia->format('d/m/Y') }} até {{ $hasImportante->fim_importancia->format('d/m/Y') }}<br>
                                <a href="{{ url('/admin/comunicados/listar') . '?' . http_build_query(['filter' => ['titulo' => $hasImportante->titulo]]) }}">Ver comunicado</a>
                            </i>
                        </small>
                    @else
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        {!! Form::text('periodo_importancia', null , ['class' => 'form-control']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group">
                {!! Form::label('periodo_acao', 'Marcar no calendário?') !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text('periodo_acao', null , ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <label>Ambiente</label>
            <div class="width48">
                <label for="radio-3">Interno</label>
                <input class="ambiente" type="radio" name="tipo_id" id="radio-3" value="{{ \App\Models\Comunicado::TIPO_INTERNO }}" data-color="#00c0ef" required>
                <label for="radio-4">Externo</label>
                <input class="ambiente" type="radio" name="tipo_id" id="radio-4" value="{{ \App\Models\Comunicado::TIPO_FRANQUEADO }}" data-color="#f39c12" required>
            </div>
        </div>
    </div>
    <div class="row" style="margin: unset;">
        <div class="col-xs-12 col-xs-3"></div>
        <div class="col-xs-12 col-xs-3">
            <label>Setor de onde está sendo enviado</label>
            <div class="form-group {{ $errors->has('setor_id') ? 'has-error' : '' }}">
                {!! Form::select('setor_id' , Auth::user()->setores->lists('nome', 'id') , $item ? $item->setor_id : null, ['placeholder' => 'Setor','class' => 'form-control select2', 'required']) !!}
            </div>
        </div>
        <div class="col-xs-12 col-xs-3">
            <label>Destinatários do comunicado</label>
            <div class="form-group">
                <a href="#" class="btn btn-flat btn-default open-modal-destinatarios" style="width: 100%">Destinatários <span id="dest-count">(0)</span></a>
                @include('portal-franqueado.admin.comunicados.modals.form-destinatarios')
            </div>
        </div>
        <div class="col-xs-12 col-xs-3">
            <label>Abrir para discussão</label>
            <div class="width48">
                <label for="radio-5">Sim</label>
                <input class="check-discussao" type="radio" name="aberto_para_questionamento" id="radio-5" value="1" data-color="#007fff" required>
                <label for="radio-6">Não</label>
                <input class="check-discussao" type="radio" name="aberto_para_questionamento" id="radio-6" value="0" data-color="red" required>
            </div>
        </div>
    </div>
</div>
<hr>