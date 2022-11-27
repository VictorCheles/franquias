@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-danger box-solid box-promocao" style="border: 1px solid #ccc;">
                <div class="box-header text-center">
                    <h2>{{ $item->nome }}</h2>
                    <h5>Preencha o formulário abaixo para obter seu cupom promocional</h5>
                </div>
                <div class="box-body">
                    {!! Form::open(['url' => url()->current()]) !!}
                    <div class="form-group {{ $errors->has('nome') ? 'has-error' : '' }}">
                        {!! Form::label('nome', 'Nome') !!}
                        {!! Form::text('nome' , '' , ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        {!! Form::label('email', 'E-mail') !!}
                        {!! Form::email('email' , '' , ['class' => 'form-control', 'required', 'style' => 'text-transform: lowercase;']) !!}
                    </div>
                    <div class="form-group {{ $errors->has('municipio_id') ? 'has-error' : '' }}">
                        {!! Form::label('municipio', 'Cidade') !!}
                        {!! Form::select('municipio_id' , $municipios , '' , ['placeholder' => 'Selecione uma cidade...', 'class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="text-center col-xs-12 box-footer">
                        <div class="col-xs-12" style="margin-bottom: 5px;">
                            <button type="submit" class="btn btn-flat button-cupom">
                                <div class="bolha">
                                    <i class="fa fa-pipoca"></i>
                                </div>
                                PEGAR CUPOM
                            </button>
                        </div>
                        {{--<div class="col-xs-12 col-sm-6">--}}
                            {{--<a href="{{ route('social.redirect', ['facebook', $item->id]) }}" class="btn btn-flat btn-social btn-facebook pull-left" style="border-radius: 8px; padding: 8px 12px 8px 44px;">--}}
                                {{--<i class="fa fa-facebook" style="top: 1px;"></i> PEGAR COM FACEBOOK--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- /.widget-user -->
        </div>
    </div>
@endsection