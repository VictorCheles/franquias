@extends('layouts.app')
@section('extra_metas')
    <meta property="og:title" content="{{ $item->nome }}">
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{ str_limit(strip_tags($item->descricao), 200) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('uploads/'. urlencode($item->imagem)) }}">
    <meta property="og:site_name" content="Cupons {{ env('APP_NAME') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <div class="box box-danger box-solid box-promocao no-border">
                <div class="box-body no-padding">
                    <div class="box-saia-sup"></div>
                    <img src="/uploads/{{ $item->imagem }}" class="img-responsive">
                </div>
                <div class="box-header text-center">
                    <h2>{{ $item->nome }}</h2>
                </div>
                <div class="box-footer box-saia"></div>
                <div class="box-body">
                    <div class="text-center col-xs-12 box-footer">
                        <div class="col-xs-12">
                            <a class="btn btn-flat button-cupom" href="{{ url('/promocao/' . $item->id . '/cupom') }}">
                                <div class="bolha">
                                    <i class="fa fa-pipoca"></i>
                                </div>
                                PEGAR CUPOM
                            </a>
                        </div>
                        {{--<div class="col-xs-12 col-sm-6">--}}
                            {{--<a href="{{ route('social.redirect', ['facebook', $item->id]) }}" class="btn btn-flat btn-social btn-facebook pull-left" style="border-radius: 8px; padding: 8px 12px 8px 44px;">--}}
                                {{--<i class="fa fa-facebook" style="top: 1px;"></i> PEGAR COM FACEBOOK--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="box-body">
                    <b>Descrição:</b>
                    {!! $item->descricao !!}
                </div>
                <div class="box-footer bg-redi">
                    <b>Regulamento:</b>
                    <br>
                    <b>{{ $item->texto_validade }}</b>
                    {!! $item->regulamento !!}
                </div>
            </div><!-- /.widget-user -->
        </div>
    </div>
@endsection