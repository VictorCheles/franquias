<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login | {{ env('APP_NAME') }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css"/>
        <style>
            html, body {
                height:100%;
            }
            .intro {
                display: flex;
                height:100%;
                justify-content : center;
                align-items : center;
            }
            .flex-center {
                height: 100%;
                flex: 1;
            }
            .left {
                background: url('{{ asset('images/foto-base-min.jpg') }}') no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                height : 100%;
                color : #FFF;
            }
            .login {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-content {
                width: 70%;
            }
        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <section class="intro">
            <div class="hidden-xs flex-center left"></div>
            <div class="flex-center login">
                <div class="login-content">
                    <div class="login-logo">
                        <a href="{{ url('/') }}">
                            <img class="img-responsive" src="{{ asset('images/logo.png') }}">
                        </a>
                    </div>
                    @if($errors->count() > 0)
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger login-alert" role="alert">{!! $error !!}</div>
                        @endforeach
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {!! session('success') !!}
                            <?php session()->forget('success'); ?>
                        </div>
                    @endif
                    <p class="login-box-msg">Bem vindo ao seu portal do Franqueado!</p>
                    <form action="{{ url()->current() }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                            <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}" autofocus>
                            <span class="fa fa-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <input name="password" type="password" class="form-control" placeholder="Password">
                            <span class="fa fa-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button style="background: #000" type="submit" class="btn btn-flat btn-primary btn-block btn-flat">Entrar</button>
                            </div>
                            <div class="form-group col-xs-12">
                                <a href="{{ url('password/reset') }}" class="btn btn-flat btn-default btn-block btn-flat">Esqueci minha senha</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <script src="{{ elixir('js/app.js') }}"></script>
    </body>
</html>
