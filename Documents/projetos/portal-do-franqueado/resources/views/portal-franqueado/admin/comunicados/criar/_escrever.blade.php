<div class="box-body">
    <div class="col-xs-12">
        <h4>Escrever comunicado</h4>
    </div>
    <div class="col-xs-12">
        <div class="form-group {{ $errors->has('titulo') ? 'has-error' : '' }}">
            {!! Form::label('titulo', 'Título') !!}
            {!! Form::text('titulo', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-xs-12">
        <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
            {!! Form::label('descricao', 'Comunicado') !!}
            {!! Form::textarea('descricao', null, ['class' => 'form-control', 'required']) !!}
        </div>
    </div>
    <div class="col-xs-12">
        <div class="form-group {{ $errors->has('video') ? 'has-error' : '' }}">
            {!! Form::label('videos', 'Vídeos (um por linha)') !!}
            {!! Form::textarea('videos', null, ['class' => 'form-control','rows' => 5]) !!}
        </div>
    </div>
</div>