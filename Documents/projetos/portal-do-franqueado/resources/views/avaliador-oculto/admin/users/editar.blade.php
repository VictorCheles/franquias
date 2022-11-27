@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Editar Usuários
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-black box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Usuário</h3>
                </div>
                {!! Form::model($item, ['url' => route('avaliadoroculto.users.update', $item->id), 'files' => true, 'method' => 'patch']) !!}
                <div class="box-body">
                    @if($item->foto)
                        <div class="form-group">
                            <img style="width: 25%;" class="img-responsive" src="{{ $item->foto }}">
                        </div>
                    @endif
                    <div class="form-group {{ $errors->has('foto') ? 'has-error' : '' }}">
                        <label for="imagem">Imagem</label>
                        {!! Form::file('foto') !!}
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', 'E-mail') !!}
                        {!! Form::text('email' , null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('telefone') ? 'has-error' : '' }}">
                        {!! Form::label('telefone', 'Telefone') !!}
                        {!! Form::text('telefone' , null , ['class' => 'form-control', 'data-mask' => '(99) 99999-9999']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('uf') ? 'has-error' : '' }}">
                        {!! Form::label('uf', 'Estado') !!}
                        {!! Form::select('uf' , \App\Models\AvaliadorOculto\User::MAPA_UFS , null , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cidade') ? 'has-error' : '' }}">
                        {!! Form::label('cidade', 'Cidade') !!}
                        {!! Form::text('cidade' , null , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cpf') ? 'has-error' : '' }}">
                        {!! Form::label('cpf', 'CPF') !!}
                        {!! Form::text('cpf' , null, ['class' => 'form-control', 'data-mask' => '999.999.999-99']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('rg') ? 'has-error' : '' }}">
                        {!! Form::label('rg', 'RG') !!}
                        {!! Form::text('rg' , null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('data_nascimento') ? 'has-error' : '' }}">
                        {!! Form::label('data_nascimento', 'Data de nascimento') !!}
                        {!! Form::text('data_nascimento' , \Carbon\Carbon::parse($item->data_nascimento)->format('d/m/Y'), ['class' => 'form-control', 'data-mask' => '99/99/9999']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('escolaridade') ? 'has-error' : '' }}">
                        {!! Form::label('escolaridade', 'Escolaridade') !!}
                        {!! Form::select('escolaridade' , \App\Models\AvaliadorOculto\User::MAPA_ESCOLARIDADE , null, ['placeholder' => 'Selecione uma opção','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password', 'Senha') !!}
                        {!! Form::password('password' , ['placeholder' => 'Deixe em branco para gerar uma senha aleatória', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password_confirmation', 'Confirme a senha') !!}
                        {!! Form::password('password_confirmation' , ['placeholder' => 'Deixe em branco para gerar uma senha aleatória', 'class' => 'form-control']) !!}
                    </div>
                    {{--<br>--}}
                    {{--<h4>Quais formulários devem ser respondidos?</h4>--}}
                    {{--<div class="usuarios_formularios">--}}
                        {{--<div class="form-to">--}}
                            {{--<div class="form-group">--}}
                                {{--{!! Form::label('loja', 'Loja') !!}--}}
                                {{--{!! Form::select('loja[]' , $lojas , null, ['required' => true, 'placeholder' => 'Selecione uma loja','class' => 'form-control']) !!}--}}
                            {{--</div>--}}
                            {{--<div class="target"></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<a href="#" class="btn btn-flat btn-info add-form">Adicionar formulario</a>--}}
                </div>
                <div class="box-footer">
                    {!! link_to(route('avaliadoroculto.users.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
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

        function template(){
            return '<div class="form-to">'+
                        '<div class="form-group">' +
                            '<hr style="border: 1px dashed #ccc;">'+
                            '{!! Form::label('loja', 'Loja') !!} <a href="#" class="remove-form"><i class="fa fa-remove"></i> Remover</a>'+
                            '{!! Form::select('loja[]' , $lojas , null, ['required' => true, 'placeholder' => 'Selecione uma loja','class' => 'form-control']) !!}'+
                        '</div>'+
                        '<div class="target"></div>' +
                    '</div>';
        }

        $('.add-form').click(function(e){
            e.preventDefault();

            $('.form-to').last().after(template());
        });

        $('.usuarios_formularios').on('click', '.remove-form', function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        });

        $('.usuarios_formularios').on('change', 'select', function(){
            var diz = $(this);
            if(id = $(this).val())
            $.ajax({
                url: '{{ url('ajax_avaliadoroculto/formularios_da_loja') }}/' + id
            }).done(function(data){
                diz.parent().parent().children('.target').html(data);
            });
        });
    </script>
@endsection