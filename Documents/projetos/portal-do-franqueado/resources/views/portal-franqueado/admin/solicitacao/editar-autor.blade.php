{!! Form::model($item ,['url' => route('solicitacao.update', $item->id), 'method' => 'put']) !!}
    <div class="form-group">
        {!! Form::label('observacoes', 'Observações') !!}
        {!! Form::textarea('observacoes' , '' , ['class' => 'form-control wysihtml5']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('status', 'Status') !!}
        {!! Form::select('status' , \App\Models\Solicitacao::$mapStatus , null, ['class' => 'form-control']) !!}
    </div>
    {!! link_to(route('solicitacao.show', $item->id), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
    {!! Form::submit('Enviar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
{!! Form::close() !!}