{!! Form::model($item ,['url' => route('admin.solicitacao.update', $item->id), 'files' => true, 'method' => 'put']) !!}
    @if(Auth::user()->isAdmin())
        <div class="form-group">
            {!! Form::label('setor_id', 'Encaminhar para outro setor') !!}
            {!! Form::select('setor_id' , \App\Models\Setor::orderBy('nome')->pluck('nome','id')->toArray() , null, ['class' => 'form-control']) !!}
        </div>
    @endif
    <div class="form-group">
        {!! Form::label('prazo', 'Prazo') !!}
        {!! Form::text('prazo' , $item->prazo ? $item->prazo->format('Y-m-d') : '' , ['class' => 'form-control datepicker', 'data-startdate' => 'd']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('observacoes', 'Observações') !!}
        {!! Form::textarea('observacoes' , '' , ['class' => 'form-control ckeditor', 'id' => 'observacoes']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('anexos', 'Anexos') !!}
        {!! Form::file('anexos[]' , ['multiple']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('status', 'Status') !!}
        {!! Form::select('status' , \App\Models\Solicitacao::$mapStatus , null, ['class' => 'form-control']) !!}
    </div>
    {!! link_to(route('solicitacao.show', $item->id), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
    {!! Form::submit('Enviar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
{!! Form::close() !!}
@section('extra_scripts')
    @parent
    <script src="//cdn.ckeditor.com/4.7.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('observacoes', {
            language: 'pt-br',
            disableNativeSpellChecker : false
        });
    </script>
@endsection