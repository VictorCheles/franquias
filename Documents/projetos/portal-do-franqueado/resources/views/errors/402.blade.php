<!DOCTYPE html>
<html lang="pr-br">
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
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <title>Trial expirou - {{ env('APP_NAME') }}</title>
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
<body class="hold-transition skin-blue layout-top-nav nav-cupons">

<div class="wrapper">
    <!-- Full Width Column -->
    <div class="content-wrapper" style="display: flex;align-items: center; justify-content: center;">
        <div class="container">
            <section class="content">
                <div class="error-page">
                    <h2 class="headline text-yellow"> Trial</h2>
                    <div class="error-content">
                        <h3><i class="fa fa-warning text-yellow"></i> Seu tempo de trial acabou!</h3>
                        <p>
                            Entre em contato conosco para mais detalhes<br>
                            <a href="https://avviso.com.br">
                                Avviso Sistemas Integrados
                            </a>
                        </p>
                    </div>
                    <!-- /.error-content -->
                </div>
            </section>
        </div><!-- /.container -->
    </div><!-- /.content-wrapper -->

</div><!-- ./wrapper -->
<script src="{{ elixir('js/app.js') }}"></script>
@yield('extra_scripts')
@include('analytics')
</body>
</html>
