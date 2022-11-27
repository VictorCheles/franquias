@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-danger box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Editar</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($item, ['url' => url()->current(), 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group">
                        <a href="/uploads/{{ $item->imagem }}">
                            <img class="img-responsive" style="width: 50%" src="/uploads/{{ $item->imagem }}">
                        </a>
                    </div>
                    <div class="form-group {{ $errors->has('imagem') ? 'has-error' : '' }}">
                    <label for="imagem">Imagem <small>(1170x450)</small></label>
                    {!! Form::file('imagem') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('modificador_por', 'Esta promoção esta sendo modificada por') !!}
                        {!! Form::text('modificador_por' , Auth::user()->nome , ['disabled','readonly','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , $item->nome , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::textarea('descricao' , $item->descricao , ['class' => 'form-control wysihtml5']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('regulamento') ? 'has-error' : '' }}">
                        {!! Form::label('regulamento', 'Regulamento') !!}
                        {!! Form::textarea('regulamento' , $item->regulamento , ['class' => 'form-control wysihtml5']) !!}
                    </div>
                    <div rel="tooltip" data-placement="bottom" title="Texto que vai aparecer quando compartilhar via WhatsApp ou Telegram" class="form-group {{ $errors->has('texto_mobile') ? 'has-error' : '' }}">
                        {!! Form::label('texto_mobile', 'Texto Mobile') !!}
                        {!! Form::text('texto_mobile' , $item->texto_mobile , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('inicio') ? 'has-error' : '' }}">
                        {!! Form::label('inicio', 'Início') !!}
                        {!! Form::date('inicio' , $item->inicio, ['class' => 'form-control datepicker']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('fim') ? 'has-error' : '' }}">
                        {!! Form::label('fim', 'Fim') !!}
                        {!! Form::date('fim' , $item->fim, ['class' => 'form-control datepicker']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('forcar_termino') ? 'has-error' : '' }}">
                        {!! Form::label('forcar_termino', 'Forçar término?') !!}
                        {!! Form::select('forcar_termino' , \App\Models\Promocao::$map_forcar_termino, null, ['class' => 'form-control']) !!}
                    </div>
                    <div rel="tooltip" data-placement="bottom" title="O valor 0 (zero) significa que o cupom vai valer até o fim da promoção" class="form-group {{ $errors->has('validade_cupom') ? 'has-error' : '' }}">
                        {!! Form::label('validade_cupom', 'Validade do Cupom') !!}
                        {!! Form::number('validade_cupom' , $item->validade_cupom, ['class' => 'form-control']) !!}
                    </div>
                    <div rel="tooltip" data-placement="bottom" title="O valor 0 (zero) significa infinitos cupons" class="form-group {{ $errors->has('max_cupons_usados') ? 'has-error' : '' }}">
                        {!! Form::label('max_cupons_usados', 'Máximo de cupons que podem ser usados') !!}
                        {!! Form::number('max_cupons_usados' , $item->max_cupons_usados, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('ordem') ? 'has-error' : '' }}">
                        {!! Form::label('ordem', 'Ordem') !!}
                        {!! Form::number('ordem' , $item->ordem, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('url_externa') ? 'has-error' : '' }}">
                        {!! Form::label('url_externa', 'Url Externa') !!}
                        {!! Form::text('url_externa' , $item->url_externa, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to('/backend/promocoes/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection