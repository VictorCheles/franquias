@extends('layouts.portal-franqueado')

@section('extra_styles')
    <style>
        .row-doida
        {
            width: 104%;
            height: 100px;
            position: relative;
            top: -85px;
        }
        .row-doida2
        {
            width: 19%;
            height: 36px;
            position: relative;
            top: -42px;
            float: right;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <section class="col-xs-12">
            <div class="headline-row">
                <h1 class="section-title caticon sbx">
                    <img src="{{ asset('images/brand_small.png') }}"> {{ $item->titulo }} - {!! '<label class="label" style="background: '. $item->tag->cor .'">'. $item->tag->titulo .'</label>' !!}
                </h1>
            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <div class="box box-black box-solid no-border">
                <div class="box-header text-center">
                    <h2>{{ $item->titulo }}</h2>
                </div>
                <div class="box-body">
                    {!! $item->descricao !!}
                </div>
                <div class="box-body no-padding" id="player">

                </div>
                <div>
                    <span class="label label-default" style="font-size: 15px;">Clique 2x no vídeo para maximizar</span>
                </div>
                <br>
                <i>O vídeo só será contabilizado como visto, quando assistido até o fim (dentro do portal)</i>
            </div>
        </div>
    </div>
@endsection
@if($item->quemAssistiu()->where('user_id', Auth()->user()->id)->count() == 0)
    @section('extra_scripts')
        @parent
        <script>
            // 2. This code loads the IFrame Player API code asynchronously.
            var tag = document.createElement('script');
            
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            // 3. This function creates an <iframe> (and YouTube player)
            //    after the API code downloads.
            var player;
            function onYouTubeIframeAPIReady() {
                player = new YT.Player('player', {
                    height: '350',
                    width: '100%',
                    videoId: '{{ $item->video_id }}',
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    },
                    playerVars: {
                        'autoPlay': 0,
                        'controls': 0,
                        'showinfo': 0,
                        'rel' : 0,
                        'disablekb': 1,
                        'origin': '{{ url()->current() }}'
                    }
                });
            }

            // 4. The API will call this function when the video player is ready.
            function onPlayerReady(event) {
                $('iframe#player').after('<div class="row-doida"></div>');
                //event.target.playVideo();
            }

            // 5. The API calls this function when the player's state changes.
            //    The function indicates that when playing a video (state=1),
            //    the player should play for six seconds and then stop. 
            var done = false;
            function onPlayerStateChange(event) {
                if(event.data == YT.PlayerState.ENDED)
                {
                    done = true;
                    var url = '{{ url('ajaxVerVideo') }}';
                    $.post(url, {'video_id' : '{{ $item->id }}', '_method': 'PUT'},function(data){
                        if(data.success)
                        {
                            swal({
                                type: 'success',
                                title: 'Parabéns',
                                text: 'Você assistiu o vídeo até o fim!',
                                showCancelButton: true,
                                confirmButtonText: 'Fechar',
                                closeOnConfirm: true,
                                cancelButtonText: "Voltar para o Canal do franqueado",
                                cancelButtonColor: "#FF0000"
                            }, function(isConfirm){
                                if(!isConfirm)
                                {
                                    window.location.href = "{{ url('videos/dashboard') }}";
                                }
                            });
                        }
                        else
                        {
                            swal({
                                type: 'error',
                                title: 'Ocorreu um erro',
                                text: 'Entre em contato com o suporte'
                            });
                        }
                    });
                }
                if(event.data == YT.PlayerState.PAUSED)
                {
                    swal({
                        type: 'warning',
                        title: 'Aviso importante',
                        text: 'O vídeo só será contabilizado como visto, quando assistido até o fim (dentro do portal)',
                        showCancelButton: false,
                        confirmButtonText: 'Voltar a assistir',
                        closeOnConfirm: true
                    }, function(isConfirm){
                        if(isConfirm)
                        {
                            player.playVideo();
                        }
                    });
                }

                if (event.data == YT.PlayerState.PLAYING && !done) {

                }
            }
            function stopVideo() {
                player.stopVideo();
            }
        </script>
    @endsection
@else
@section('extra_scripts')
    @parent
    <script>
        // 2. This code loads the IFrame Player API code asynchronously.
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // 3. This function creates an <iframe> (and YouTube player)
        //    after the API code downloads.
        var player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '350',
                width: '100%',
                videoId: '{{ $item->video_id }}',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars: {
                    'autoPlay': 0,
                    'controls': 1,
                    'showinfo': 0,
                    'rel' : 0,
                    'disablekb': 0,
                    'origin': '{{ url()->current() }}'
                }
            });
        }

        // 4. The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            $('iframe#player').after('<div class="row-doida2"></div>');
            //event.target.playVideo();
        }

        // 5. The API calls this function when the player's state changes.
        //    The function indicates that when playing a video (state=1),
        //    the player should play for six seconds and then stop.
        var done = false;
        function onPlayerStateChange(event) {

        }
        function stopVideo() {
            player.stopVideo();
        }
    </script>
@endsection
@endif