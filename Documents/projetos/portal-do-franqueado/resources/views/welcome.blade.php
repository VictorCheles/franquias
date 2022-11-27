@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <a id="banner-app" href="{{ $linkApp }}" target="_blank">
                <img src="{{ asset('images/topbanner_sitecupons.png') }}" alt="Baixe nosso APP Wayne's" class="img-responsive">
            </a>
        </div>
    </div>
    <div class="row">
        @foreach($lista as $item)
            <div class="col-lg-6 col-sm-12">
                <div class="box box-danger box-solid box-promocao no-border no-background">
                    <div class="box-body no-padding">
                        <div class="box-saia-sup"></div>
                        <img src="/uploads/{{ $item->imagem }}" class="img-responsive">
                    </div>
                    <div class="box-header">
                        {{ $item->nome }}
                    </div>
                    <div class="box-footer box-saia"></div>
                    <div class="box-footer">
                        @if($item->status)
                            <div class="row">
                                @php
                                if(!empty($item->url_externa)) {
                                $urlCupom = $item->url_externa;
                                $urlDetalhe = $item->url_externa;
                                } else {
                                $urlCupom = url('/promocao/' . $item->id . '/cupom');
                                $urlDetalhe = url('/promocao/' . $item->id);
                                }
                                @endphp
                                <div class="col-sm-6 col-xs-12">
                                    <a class="btn btn-flat button-cupom pull-right" href="{{ $urlCupom }}">
                                        <div class="bolha">
                                            <i class="fa fa-pipoca"></i>
                                        </div>
                                        PEGAR CUPOM
                                    </a>
                                </div>
                                <div class="col-sm-6 hidden-xs">
                                    <a class="btn btn-flat button-cupom sem-bg pull-left" href="{{ $urlDetalhe }}">
                                        <div class="bolha">
                                            <i class="fa fa-plus"></i>
                                        </div>
                                        MAIS DETALHES
                                    </a>
                                </div>
                                @if($agent->isMobile())
                                    <div class="col-mobile">
                                        <div class="col-xs-6 visible-xs-*">
                                            <a href="whatsapp://send?abid=username&text={{ $item->texto_mobile . ' ' . url('/promocao/'. $item->id) }}" class="btn btn-flat btn-success pull-right"><i class="fa fa-whatsapp" style="font-size: 23px;"></i></a>
                                        </div>
                                        <div class="col-xs-6 visible-xs-*">
                                            <a href="https://telegram.me/share/url?url={{ $item->texto_mobile . ' ' . url('/promocao/'. $item->id) }}" class="btn btn-flat btn-info pull-right"><i class="fa fa-paper-plane" style="font-size: 23px"></i></a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <a href="#" onclick="return false;" class="btn btn-flat btn-default btn btn-flat pull-left">Promoção encerrada</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('extra_scripts')
    @if(!$agent->isMobile())
        <script>
            $(function(){
                $('#banner-app').click(function(e){
                    e.preventDefault();
                    var linkAndroid = 'https://play.google.com/store/apps/details?id=com.neemo.waynes';
                    var linkIphone = 'https://itunes.apple.com/br/app/waynes/id1411424514';
                    swal({
                            title: "Selecione seu dispositivo móvel",
                            text: "Escolha qual o seu dispositivo e baixe o APP Wayne's",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonText: "iPhone",
                            cancelButtonText: "Android"
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                window.location.href=linkIphone;
                            } else {
                                window.location.href=linkAndroid;
                            }
                        }
                    );
                });
            });
        </script>
    @endif
@endsection