<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <!-- Page title -->
    <title>Zahid Scan | Reset4</title>

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{url('vendor/fontawesome/css/font-awesome.css')}}"/>
    <link rel="stylesheet" href="{{url('vendor/animate.css/animate.css')}}"/>
    <link rel="stylesheet" href="{{url('vendor/bootstrap/css/bootstrap.css')}}"/>

    <!-- App styles -->
    <link rel="stylesheet" href="{{url('styles/pe-icons/pe-icon-7-stroke.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/pe-icons/helper.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/stroke-icons/style.css')}}"/>
    <link rel="stylesheet" href="{{url('styles/style.css')}}">
</head>
<body class="blank">
<div class="wrapper">


    <!-- Main content-->
    <section class="content">
        <div class="back-link">
            <a href="{{url('/login')}}" class="btn btn-accent">Login</a>
        </div>

        <div class="container-center animated slideInDown">


            <div class="view-header">
                <div class="header-icon">
                    <i class="pe page-header-icon pe-7s-id"></i>
                </div>
                <div class="header-title">
                    <h3>Reset password</h3>
                    <small>
                        Please enter your email to reset your password.
                    </small>
                </div>
            </div>

            <div class="panel panel-filled">
                <div class="panel-body">
                    <form method="POST" action="{{ route('password.email') }}" id="loginForm" novalidate>
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label" for="email">Email adress</label>
                            <input type="email" placeholder="example@gmail.com" title="Please enter your email" required="" value="" name="email" id="email" class="form-control">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <span class="help-block small">Your address email to sent new password</span>
                        </div>
                        <div>
                            <button class="btn btn-accent">Sent new password</button>
                            <a class="btn btn-default" href="{{url('/login')}}">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
    <!-- End main content-->

</div>

<script src="{{url('vendor/pacejs/pace.min.js')}}"></script>
<script src="{{url('vendor/jquery/dist/jquery.min.js')}}"></script>
<script src="{{url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>

<!-- App scripts -->
<script src="{{url('scripts/luna.js')}}"></script>
</body>

</html>


{{--
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> Password</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <forms class="forms-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="forms-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="forms-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="forms-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </forms>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
--}}
