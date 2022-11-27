@if(str_is(env('APP_SUBDOMAIN_PORTAL_FRANQUEADO') . '*', Route::current()->domain()))
    <?php $layout = 'layouts.portal-franqueado'; $box = 'box-black' ?>
@else
    <?php $layout = 'layouts.app'; $box = 'box-danger' ?>
@endif

@extends($layout)

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Editar loja {{ $item->nome }}
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
            <div class="box {{ $box }} box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => url()->current()]) !!}
                <div class="box-body">
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , $item->nome , ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('praca_id') ? 'has-error' : '' }}">
                        {!! Form::label('praca_id', 'Praça') !!}
                        {!! Form::select('praca_id' , \App\Models\Praca::lists('nome', 'id') , $item->praca_id, ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cep') ? 'has-error' : '' }}">
                        {!! Form::label('cep', 'CEP') !!}
                        <div class="row">
                            <div class="col-sm-10">
                                {!! Form::text('cep', $item->cep, ['class' => 'form-control', 'data-mask' => '99999-999']) !!}
                            </div>
                            <div class="col-sm-2">
                                {!! link_to('http://www.buscacep.correios.com.br/sistemas/buscacep/', 'Não sei o CEP', ['class' => 'btn btn-flat btn-info','target' => '_blank']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('uf') ? 'has-error' : '' }}">
                        {!! Form::label('uf', 'UF') !!}
                        {!! Form::text('uf', $item->uf, ['class' => 'form-control', 'maxlength' => 2, 'readonly']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('cidade') ? 'has-error' : '' }}">
                        {!! Form::label('cidade', 'Cidade') !!}
                        {!! Form::text('cidade', $item->cidade, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('bairro') ? 'has-error' : '' }}">
                        {!! Form::label('bairro', 'Bairro') !!}
                        {!! Form::text('bairro', $item->bairro, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('endereco') ? 'has-error' : '' }}">
                        {!! Form::label('endereco', 'Endereço') !!}
                        {!! Form::text('endereco', $item->endereco, ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('numero') ? 'has-error' : '' }}">
                        {!! Form::label('numero', 'Número') !!}
                        {!! Form::text('numero', $item->numero, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('complemento', 'Complemento') !!}
                        {!! Form::text('complemento', $item->complemento, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('valor_minimo_pedido') ? 'has-error' : '' }}">
                        {!! Form::label('valor_minimo_pedido', 'Valor mínimo do pedido') !!}
                        {!! Form::text('valor_minimo_pedido' , maskMoney($item->valor_minimo_pedido) , ['class' => 'form-control maskMoney', 'data-affixes-stay' => 'true', 'data-prefix' => 'R$ ', 'data-thousands' => '.', 'data-decimal' => ',']) !!}
                    </div>
                </div>
                <div class="box-footer">
                    {!! link_to('/backend/franquias/listar', 'Cancelar', ['class' => 'btn btn-flat btn-danger']) !!}
                    {!! Form::submit('Editar', ['class' => 'btn btn-flat btn-primary pull-right']) !!}
                </div>
                {!! Form::close() !!}
                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    @parent
    <script type="text/javascript">
        $(function(){
            $('.maskMoney').maskMoney({
                allowZero: true
            });
        });
        $(document).ajaxStart(function () {
            $('.overlay').show()
        });
        $(document).ajaxStop(function () {
            $('.overlay').hide();
        });

        $(document).ready(function () {
            $('#cep').blur(function () {
                var cep = $(this).val();
                $.get("http://apps.widenet.com.br/busca-cep/api/cep/" + cep + ".json", function (data) {
                    if (data.status) {
                        $('#uf').val(data.state);
                        $('#cidade').val(data.city);
                        $('#bairro').val(data.district);
                        $('#endereco').val(data.address);
                        $('#numero').focus();
                    }
                    else {
                        swal({
                            title: 'CEP não encontrado',
                            text: 'O CEP informado não consta no banco de dados dos correios',
                            type: 'warning',
                            showCancelButton: true,
                            cancelButtonText: "Cancelar",
                            confirmButtonText: "Ir para o site dos Correios"
                        }, function (isConfirm) {
                            if (isConfirm) {
                                window.open('http://www.buscacep.correios.com.br/sistemas/buscacep/')
                            }
                        });
                        $('#uf').val('');
                        $('#cidade').val('');
                        $('#bairro').val('');
                        $('#endereco').val('');
                    }
                });
            });
        });
    </script>
@endsection