@extends('layouts.portal-franqueado')
@section('content')
    <?php $user = Auth::user(); ?>
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar setor - {{ $item->nome }}
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
                {!! Form::model($item ,['url' => route('admin.setor.update', $item->id), 'files' => true, 'method' => 'put']) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , null , ['id' => 'nome', 'class' => 'form-control', 'autocomplete' => false]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('tag') ? 'has-error' : '' }}">
                        {!! Form::label('tag', 'Tag') !!}
                        {!! Form::text('tag' , $item->tag_formatted , ['id' => 'tag', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('responsavel') ? 'has-error' : '' }}">
                        {!! Form::label('responsavel', 'Responsáveis') !!}
                        {!! Form::select('responsaveis[]' , \App\User::orderBy('nome', 'asc')->lists('nome', 'id') , $responsaveis, ['class' => 'form-control select2', 'multiple' => true]) !!}
                    </div>
                    <div class="form-group {{ $errors->has('interno') ? 'has-error' : '' }}">
                        {!! Form::label('interno', 'Setor interno?') !!}
                        {!! Form::select('interno' , \App\Models\Setor::$mapInterno , null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.setor.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script>
        $('.select2').select2({
            language: 'pt-BR'
        });
        $(function(){
            $('input#nome').keydown(function(){
                if($(this).val())
                {
                    $('input#tag').val('#' + $(this).val().substr(0, 3).toUpperCase());
                }
                else
                {
                    $('input#tag').val('');
                }

            });
        });
    </script>
@endsection