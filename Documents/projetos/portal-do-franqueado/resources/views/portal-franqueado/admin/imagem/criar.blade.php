@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Nova imagem
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div>
                {!! Form::open(['url' => route('admin.imagem.store'), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('imagem') ? 'has-error' : '' }}">
                        <label for="imagem">Imagem</label>
                        {!! Form::file('imagem' , ['required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::text('descricao' , '' , ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary']) !!}
                    {!! link_to(route('admin.imagem.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger pull-right']) !!}
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