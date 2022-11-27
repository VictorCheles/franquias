@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        input#email
        {
            text-transform: lowercase;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Avaliador Oculto - Novo Usuários
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
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('autocadastro', 'Link para auto cadastro') !!}
                        {!! Form::text('autocadastro' , urlClienteOculto() . '/auto-cadastro/' . \Crypt::Encrypt(\Carbon\Carbon::now()->addDays(15)->format('Y-m-d')) , ['class' => 'form-control']) !!}
                    </div>
                </div>
                {!! Form::open(['url' => route('avaliadoroculto.users.store'), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('foto') ? 'has-error' : '' }}">
                        <label for="imagem">Foto</label>
                        {!! Form::file('foto') !!}
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', 'E-mail') !!}
                        {!! Form::text('email' , '' , ['class' => 'form-control', 'id' => 'email']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('telefone') ? 'has-error' : '' }}">
                        {!! Form::label('telefone', 'Telefone') !!}
                        {!! Form::text('telefone' , '' , ['class' => 'form-control', 'data-mask' => '(99) 99999-9999']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('uf') ? 'has-error' : '' }}">
                        {!! Form::label('uf', 'Estado') !!}
                        {!! Form::select('uf' , \App\Models\AvaliadorOculto\User::MAPA_UFS ,'' , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cidade') ? 'has-error' : '' }}">
                        {!! Form::label('cidade', 'Cidade') !!}
                        {!! Form::text('cidade' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cpf') ? 'has-error' : '' }}">
                        {!! Form::label('cpf', 'CPF') !!}
                        {!! Form::text('cpf' , '' , ['class' => 'form-control', 'data-mask' => '999.999.999-99']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('rg') ? 'has-error' : '' }}">
                        {!! Form::label('rg', 'RG') !!}
                        {!! Form::text('rg' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('data_nascimento') ? 'has-error' : '' }}">
                        {!! Form::label('data_nascimento', 'Data de nascimento') !!}
                        {!! Form::text('data_nascimento' , '' , ['class' => 'form-control', 'data-mask' => '99/99/9999']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('escolaridade') ? 'has-error' : '' }}">
                        {!! Form::label('escolaridade', 'Escolaridade') !!}
                        {!! Form::select('escolaridade' , \App\Models\AvaliadorOculto\User::MAPA_ESCOLARIDADE ,'' , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('banco_id') ? 'has-error' : '' }}">
                        {!! Form::label('banco_id', 'Banco') !!}
                        {!! Form::select('banco_id' , \App\Models\Banco::orderBy('codigo')->pluck('nome', 'id') ,'' , ['placeholder' => 'Selecione uma opção','class' => 'form-control select2']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('agencia') ? 'has-error' : '' }}">
                        {!! Form::label('agencia', 'Agência') !!}
                        {!! Form::text('agencia' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('conta_corrente') ? 'has-error' : '' }}">
                        {!! Form::label('conta_corrente', 'Conta corrente') !!}
                        {!! Form::text('conta_corrente' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password', 'Senha') !!}
                        {!! Form::password('password' , ['placeholder' => 'Deixe em branco para gerar uma senha aleatória', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        {!! Form::label('password_confirmation', 'Confirme a senha') !!}
                        {!! Form::password('password_confirmation' , ['placeholder' => 'Deixe em branco para gerar uma senha aleatória', 'class' => 'form-control']) !!}
                    </div>
                    <br>
                    <h4>Quais formulários devem ser respondidos?</h4>
                    <div class="form-group {{ $errors->has('data_visita') ? 'has-error' : '' }}">
                        {!! Form::label('data_visita', 'Data e hora da visita') !!}
                        {!! Form::text('data_visita' , '' , ['class' => 'form-control default-datetimepicker']) !!}
                    </div>
                    <div class="usuarios_formularios">
                        <div class="form-to">
                            <div class="form-group">
                                {!! Form::label('loja', 'Loja') !!}
                                {!! Form::select('loja[]' , $lojas , null, ['required' => true, 'placeholder' => 'Selecione uma loja','class' => 'form-control']) !!}
                            </div>
                            <div class="target"></div>
                        </div>
                    </div>
                    <a href="#" class="btn btn-flat btn-info add-form">Adicionar formulario</a>
                </div>
                <div class="box-footer">
                    {!! link_to(route('avaliadoroculto.users.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Criar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
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