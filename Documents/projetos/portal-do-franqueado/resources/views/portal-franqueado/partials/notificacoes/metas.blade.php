<?php
$auth = Auth()->user();
$videos = $auth->videosNaoAssistidos();
?>

<li class="dropdown tasks-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-video-camera faa-flash {{ $videos->count() == 0 ?: 'faa-fast animated' }}"></i>
        @if($videos->count() > 0)
            <span class="label label-danger">{{ $videos->count() }}</span>
        @endif
    </a>
    <ul class="dropdown-menu">
        @if($videos->count() == 0)
            <li class="header">Nenhum vídeo novo</li>
        @else
            <li class="header">Você tem {{ $videos->count() }} vídeos não assistidos</li>
            <li>
                <ul class="menu">
                    @foreach($videos as $v)
                        <li>
                            <a href="{{ route('video.show', $v->id) }}">
                                <h3>
                                    <i class="fa fa-video-camera"></i> {{ $v->titulo }}
                                </h3>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    </ul>
</li>