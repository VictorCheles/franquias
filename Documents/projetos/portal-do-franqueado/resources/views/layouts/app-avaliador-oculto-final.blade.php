<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="pt-br">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, minimal-ui" name="viewport">
        <link href="{{ asset('images/favicon.png') }}" rel="apple-touch-icon">
        <link href="{{ asset('images/favicon.png') }}" rel="icon" type="image/png">
        <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/png">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <title>{{ $tituloAba or 'Cliente Oculto | ' . env('APP_NAME') }}</title>
        @yield('extra_metas')
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
        @yield('extra_styles')
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue layout-top-nav" style="background: black;">
        <div class="wrapper" style="background: red;">
        @include('avaliador-oculto.partials.nav-bar')
            <div class="content-wrapper">
                <div class="container" style="background: black;">
                @include('partials.system-alerts')
                    <section class="content">
                        @yield('content')
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
