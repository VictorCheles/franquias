{{--<header class="main-header public-header">--}}
    {{--<nav class="navbar navbar-static-top">--}}
        {{--<div class="container" style="padding: 0">--}}
            {{--<div class="navbar-header" style="margin: 0 auto; padding-top: 0;">--}}
                {{--<a href="{{ url('/') }}" class="navbar-brand">--}}
                    {{--<img src="{{ asset('images/cliente-oculto.jpeg') }}" style="max-height: 85px;">--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</nav>--}}
{{--</header>--}}
<header class="main-header" style="max-height: none;">
    <nav class="navbar navbar-static-top" style="background: #000;">
        <div class="container">
            <div class="navbar-header">
                <a href="{{ url('/') }}" class="">
                    <img src="{{ asset('images/cliente-oculto.jpeg') }}" class="img-responsive">
                </a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" style="position: absolute; top: 10px; right: 0;">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            @if(Auth::guard('avaliador_oculto')->check())
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse" style="width: 100%;">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/logout') }}">Sair</a></li>
                    </ul>
                </div>
            @endif
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>