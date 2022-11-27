@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-danger box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => url()->current(), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('imagem') ? 'has-error' : '' }}">
                        <label for="imagem">Imagem <small>(1170x450)</small></label>
                        {!! Form::file('imagem' , ['required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::textarea('descricao' , '' , ['class' => 'form-control wysihtml5']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('regulamento') ? 'has-error' : '' }}">
                        {!! Form::label('regulamento', 'Regulamento') !!}
                        {!! Form::textarea('regulamento' , '' , ['class' => 'form-control wysihtml5']) !!}
                    </div>
                    <div rel="tooltip" data-placement="bottom" title="Texto que vai aparecer quando compartilhar via WhatsApp ou Telegram" class="form-group {{ $errors->has('texto_mobile') ? 'has-error' : '' }}">
                        {!! Form::label('texto_mobile', 'Texto Mobile') !!}
                        {!! Form::text('texto_mobile' , '' , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('inicio') ? 'has-error' : '' }}">
                        {!! Form::label('inicio', 'Início') !!}
                        {!! Form::text('inicio' , date('Y-m-d'), ['class' => 'form-control datepicker', 'data-startdate' => 'd']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('fim') ? 'has-error' : '' }}">
                        {!! Form::label('fim', 'Fim') !!}
                        {!! Form::text('fim' , date('Y-m-d', strtotime('+1 day')), ['class' => 'form-control datepicker', 'data-startdate' => '+1d']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('forcar_termino') ? 'has-error' : '' }}">
                        {!! Form::label('forcar_termino', 'Forçar término?') !!}
                        {!! Form::select('forcar_termino' , \App\Models\Promocao::$map_forcar_termino , null, ['class' => 'form-control']) !!}
                    </div>
                    <div rel="tooltip" data-placement="bottom" title="O valor 0 (zero) significa que o cupom vai valer até o fim da promoção" class="form-group {{ $errors->has('validade_cupom') ? 'has-error' : '' }}">
                        {!! Form::label('validade_cupom', 'Validade do Cupom') !!}
                        {!! Form::number('validade_cupom' , 0, ['class' => 'form-control']) !!}
                    </div>
                    <div rel="tooltip" data-placement="bottom" title="O valor 0 (zero) significa infinitos cupons" class="form-group {{ $errors->has('max_cupons_usados') ? 'has-error' : '' }}">
                        {!! Form::label('max_cupons_usados', 'Máximo de cupons que podem ser usados') !!}
                        {!! Form::number('max_cupons_usados' , 0, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('ordem') ? 'has-error' : '' }}">
                        {!! Form::label('ordem', 'Ordem') !!}
                        {!! Form::number('ordem' , 1, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('url_externa') ? 'has-error' : '' }}">
                        {!! Form::label('url_externa', 'Url Externa') !!}
                        {!! Form::text('url_externa' , null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to('/backend/promocoes/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection