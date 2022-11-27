@extends('layouts.portal-franqueado')
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar produto - {{ $item->nome }}
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
                {!! Form::model($item ,['url' => route('admin.produto.update', $item->id), 'method' => 'put', 'files' => true]) !!}
                <div class="box-body">
                    <div class="form-group">
                        <a href="{{ $item->img }}">
                            <img class="img-responsive" style="width: 50%" src="{{ $item->img }}">
                        </a>
                    </div>
                    <div class="form-group {{ $errors->has('imagem') ? 'has-error' : '' }}">
                        <label for="imagem">Imagem
                            <small>(350x350) ou na mesma proporção</small>
                        </label>
                        {!! Form::file('imagem') !!}
                    </div>
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , null , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('descricao') ? 'has-error' : '' }}">
                        {!! Form::label('descricao', 'Descrição') !!}
                        {!! Form::textarea('descricao' , null , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('preco') ? 'has-error' : '' }}">
                        {!! Form::label('preco', 'Preço unitário') !!}
                        {!! Form::text('preco' , $item->preco_formatted , ['class' => 'form-control maskMoney', 'data-affixes-stay' => 'true', 'data-prefix' => 'R$ ', 'data-thousands' => '.', 'data-decimal' => ',']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('peso') ? 'has-error' : '' }}">
                        {!! Form::label('Peso', 'Peso(kg)') !!}
                        {!! Form::text('peso' , null , ['placeholder' => '1.700', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('categoria') ? 'has-error' : '' }}">
                        {!! Form::label('categoria', 'Produto disponível?') !!}
                        {!! Form::select('categoria' , \App\Models\CategoriaProduto::orderBy('nome')->lists('nome', 'id') , $item->categoria_id, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('disponivel') ? 'has-error' : '' }}">
                        {!! Form::label('disponivel', 'Produto disponível?') !!}
                        {!! Form::select('disponivel' , \App\Models\Produto::$mapDisponibilidade , (int) $item->disponivel, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to(route('admin.produto.index'), 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
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
        $(function(){
            $('.maskMoney').maskMoney();
        });
    </script>
@endsection