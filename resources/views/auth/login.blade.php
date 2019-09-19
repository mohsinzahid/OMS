<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <!-- Page title -->
    <title>Zahid Scan | Login</title>

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.css')}}"/>
    <link rel="stylesheet" href="{{url('vendor/animate.css/animate.css')}}"/>
    <link rel="stylesheet" href="{{url('vendor/bootstrap/css/bootstrap.css')}}"/>

    <!-- App styles -->
    <link rel="stylesheet" href="{{url('styles/pe-icons/pe-icon-7-stroke.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/pe-icons/helper.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/stroke-icons/style.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/style.css')}}">

    {{--<style>--}}
        {{--.wrapper {--}}
            {{--background-image: url("images/logo.png");!important;--}}
            {{--opacity: 0.5;--}}
            {{--color: BLACK;--}}
            {{--position: absolute;--}}
            {{--bottom: 0;--}}
            {{--right: 0;--}}
        {{--}--}}
    {{--</style>--}}

</head>
<body class="blank" >
    <div style="background-image: url('/images/logofinal.png');!important; background-position: center;background-repeat: no-repeat;background-size:contain;">
        {{--<img src="images/final.jpg"   alt="">--}}
        <div class="wrapper" >
        <!-- Main content-->
        <section class="content">
{{--            <div class="back-link">
                <a href="index-2.html" class="btn btn-accent">Back to Dashboard</a>
            </div>--}}

            <div class="container-center animated slideInDown">
                <div class="view-header">
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-unlock"></i>
                    </div>
                    <div class="header-title">
                        <h3>Login</h3>
                        <small>
                            Please enter your credentials to login.
                        </small>
                    </div>
                </div>

                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                <span class="help-block small">Your unique Email to app</span>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="control-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <span class="help-block small">Your strong password</span>
                            </div>
                            {{--<div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>--}}

                            <div class="form-group">
                                    <button type="submit" class="btn btn-accent">
                                        Login
                                    </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- End main content-->

    </div>
    </div>

    {{--<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>--}}

<script src="{{url('vendor/pacejs/pace.min.js')}}"></script>
<script src="{{url('vendor/jquery/dist/jquery.min.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- App scripts -->
<script src="{{url('scripts/luna.js')}}"></script>
</body>

</html>

