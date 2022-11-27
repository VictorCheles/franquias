<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cliente Oculto {{ env('APP_NAME') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    <style>
        .form-control
        {
            border-color: #fff;
            -webkit-border-radius:3px;
            -moz-border-radius:3px;
            border-radius:3px;
        }
        .control-label
        {
            color: #fff;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page" style="background: #CFB081;">
<div class="login-box">
    <div class="login-box-body" style="background: none;">
        <a href="{{ url('/') }}">
            <img class="img-responsive" src="{{ asset('images/logo-cliente-oculto-transparente.png') }}" style="margin: 5px auto;">
        </a>
        <h3 class="text-center">Seu link expirou!</h3>
    </div>
</div>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>
