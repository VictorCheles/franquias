<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="pt-br">
        <!-- Tell the browser to be responsive to screen width -->
        {{--<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">--}}
        <meta content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width, minimal-ui" name="viewport">
        <link href="{{ asset('images/favicon.png') }}" rel="apple-touch-icon">
        <link href="{{ asset('images/favicon.png') }}" rel="icon" type="image/png">
        <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/png">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta content="yes" name="mobile-web-app-capable">
        <title>{{ $tituloAba or 'Portal do Franqueado | ' . env('APP_NAME') }}</title>
        @yield('extra_metas')
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
        @yield('extra_styles')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
    <body class="hold-transition skin-blue layout-top-nav nav-franqueado">
        <div class="bg-portal">

        </div>
        <div class="wrapper">
        @include('portal-franqueado.partials.nav-bar')
        <!-- Full Width Column -->
            <div class="content-wrapper">
                <div class="container-fluid">
                @include('partials.system-alerts')
                    <!-- Content Header (Page header) -->
                    @if(isset($tituloPagina) or isset($subTituloPagina))
                        <section class="content-header">
                            <h1>
                                {!! $tituloPagina or 'Dashboard' !!}
                                <small>{!! $subTituloPagina or 'Painel de controle' !!}</small>
                            </h1>
                            @include('partials.breadcrumb')
                        </section>
                    @endif

                    {{--@include('partials.system-alerts')--}}
                    <section class="content">
                        @yield('content')
                    </section>
                </div><!-- /.container -->
            </div><!-- /.content-wrapper -->
            @include('partials.footer')
        </div><!-- ./wrapper -->
        <script src="{{ elixir('js/app.js') }}"></script>
        @yield('extra_scripts')
    </body>
</html>
