@extends('layouts.app')
<?php
$user = Auth::user();
?>
@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> Validar Cupom
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title text-center">Digite o código do cupom</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post">
                    {{ csrf_field() }}
                    <div class="box-body">
                        <div class="form-group">
                            <input data-mask="***-***" name="cupom" class="form-control input-lg input-fudidamente-lg" id="cupom" autofocus autocomplete="off">
                        </div>
                        <div>
                            <div class="form-group">
                                {!! Form::label('loja_id', 'Loja') !!}
                                {!! Form::select('loja_id' , $user->lojas->pluck('nome', 'id')->toArray() , null, ['placeholder' => 'Selecione uma loja', 'class' => 'form-control', 'id' => 'loja']) !!}
                            </div>
                        </div>  
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-flat btn-success btn-lg btn-100">Validar</button>
                    </div>
                </form>
                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script type="text/javascript">
        $(document).ajaxStart(function () {
            $('.overlay').show()
        });
        $(document).ajaxStop(function () {  
            $('.overlay').hide();
        });

        $(document).ready(function () {
            var token = '';
            var cupom = '';
            $('form').submit(function () {
                cupom = $('input#cupom').val();
                loja = $('select#loja').val();
                $.get("{{ url('/backend/novo_csrf') }}", function(data){
                    token = data.token;
                }).success(function(){
                    $.post("{{ url('/backend/cupons/validar') }}", {'loja': loja, 'cupom': cupom, '_token' : token }, function (data) {
                        swal(data);
                    }).error(function (data) {
                        swal({
                            title: 'Erro ao validar cupom',
                            text: 'Entre em contato com a equipe de suporte imediatamente',
                            type: 'error'
                        });
                    });
                }).error(function(data){
                    swal({
                        title: 'Erro ao validar cupom',
                        text: 'Entre em contato com a equipe de suporte imediatamente',
                        type: 'error'
                    });
                });
                $('input#cupom').val('');
                return false;
            });
        });
    </script>
@endsection
