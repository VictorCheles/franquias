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

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('/backend/') }}">
                    <img class="img-responsive" src="{{ asset('images/logo.png') }}">
                </a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                @if($errors->count() > 0)
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger login-alert" role="alert">{{ $error }}</div>
                    @endforeach
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {!! session('success') !!}
                        <?php session()->forget('success'); ?>
                    </div>
                @endif
                <p class="login-box-msg">Acesso ao sistema</p>
                    {!! Form::open(['url' => url()->current()]) !!}
                        <div class="form-group has-feedback {{ $errors->has('nome') ? 'has-error' : '' }}">
                            {!! Form::text('nome' , '' , ['class' => 'form-control', 'placeholder' => 'Nome completo']) !!}
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            {!! Form::email('email' , '' , ['class' => 'form-control', 'placeholder' => 'E-mail']) !!}
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            {!! Form::password('password' , ['class' => 'form-control', 'placeholder' => 'Senha']) !!}
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            {!! Form::password('password_confirmation' , ['class' => 'form-control', 'placeholder' => 'Confirme a senha']) !!}
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('loja_id') ? 'has-error' : '' }}">
                            {!! Form::select('loja_id' , \App\Models\Loja::lists('nome', 'id')->sortBy('nome') , null, ['placeholder' => 'Selecione uma opção','class' => 'form-control']) !!}
                            <span class="fa fa-industry form-control-feedback"></span>
                        </div>

                        <div class="row">
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-flat btn-primary btn-block btn-flat">Registrar</button>
                            </div><!-- /.col -->
                        </div>
                    {!! Form::close() !!}
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <script src="{{ elixir('js/app.js') }}"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>
