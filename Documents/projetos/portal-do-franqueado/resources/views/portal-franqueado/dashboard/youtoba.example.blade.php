@extends('layouts.portal-franqueado')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div id="player"></div>
        </div>
    </div>
@endsection

@section('extra_scripts')
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
                height: '390',
                width: '640',
                videoId: 'M7lc1UVf-VE',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars: {
                    'autoplay': 0,
                    'controls': 0,
                    'rel' : 0,
                    'disablekb': 1,
                    'origin': '{{ url()->current() }}'
                }
            });
        }

        // 4. The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            event.target.playVideo();
        }

        // 5. The API calls this function when the player's state changes.
        //    The function indicates that when playing a video (state=1),
        //    the player should play for six seconds and then stop.
        var done = false;
        function onPlayerStateChange(event) {
            if(event.data == YT.PlayerState.ENDED)
            {
                console.log('aew');
            }
//            if (event.data == YT.PlayerState.PLAYING && !done) {
//                setTimeout(stopVideo, 6000);
//                done = true;
//            }
        }
        function stopVideo() {
            player.stopVideo();
        }
    </script>
@endsection