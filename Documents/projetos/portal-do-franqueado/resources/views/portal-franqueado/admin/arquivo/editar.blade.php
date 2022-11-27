@extends('layouts.portal-franqueado')

@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar Arquivo - {{ $item->nome }}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! $quick_actions or '' !!}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['url' => route('admin.arquivo.update', $item->id), 'files' => true]) !!}
                <input type="hidden" name="_method" value="PATCH">
                <div class="box-body">
                    <div class="form-group">
                        <label for="arquivo">Arquivo Atual</label>
                        <br>
                        {{ $item->arquivo_src }}
                    </div>
                    <div class="form-group {{ $errors->has('imagem') ? 'has-error' : '' }}">
                        <label for="arquivo">Arquivo</label>
                        {!! Form::file('arquivo') !!}
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , $item->nome , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::textarea('descricao' , $item->descricao, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('pasta_id') ? 'has-error' : '' }}">
                        {!! Form::label('pasta_id', 'Pasta') !!}
                        {!! Form::select('pasta_id' , $listKv, $item->pasta_id, ['placeholder' => 'Selecione uma pasta','class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to('/admin/comunicados/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script type="text/javascript">
        $(function () {
            $('.chosen').chosen({
                no_results_text: "Sem resultados para",
                placeholder_text_single: "Selecione uma opção",
                placeholder_text_multiple: "Selecione os destinatários"
            });
            $('form').on('click', '.group-result', function () {
                var unselected = $(this).nextUntil('.group-result').not('.result-selected');
                if (unselected.length) {
                    unselected.trigger('mouseup');
                } else {
                    $(this).nextUntil('.group-result').each(function () {
                        $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
                    });
                }
            });
        });
    </script>
@endsection