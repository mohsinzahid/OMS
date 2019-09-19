<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

    <!-- Page title -->
    <title>Zahid Scan | Register</title>

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
                <a href="{{url('/admin')}}" class="btn btn-accent">Back to Dashboard</a>
            </div>

            <div class="container-center lg animated slideInDown">


                <div class="view-header">
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-add-user"></i>
                    </div>
                    <div class="header-title">
                        <h3>Register</h3>
                        <small>
                            Please enter your data to register.
                        </small>
                    </div>
                </div>

                <div class="panel panel-filled">
                    <div class="panel-body">
                        <p>

                        </p>
                        <form method="POST" action="{{ route('register') }}" id="loginForm" novalidate>
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} col-lg-6">
                                    <label for="name" class="control-label">Name</label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                    <span class="help-block small">Your unique username to app</span>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} col-lg-6">
                                    <label for="email" class="control-label">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                    @endif
                                    <span class="help-block small">Your address email to contact</span>

                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} col-lg-6">
                                    <label for="password" class="control-label">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                    @endif
                                    <span class="help-block small">Your hard to guess password</span>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="password-confirm" class=" control-label">Confirm Password</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    <span class="help-block small">Please repeat your password</span>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Type</label>
                                    <select class="form-control" name="type_id" style="width: 100%" required>
                                        <option value="" selected disabled hidden>Choose here</option>
                                        <option value="1">Super Admin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Sales Man</option>
                                        <option value="4">Machine Man</option>
                                    </select>
                                    <span class="help-block small">Type of your account</span>
                                </div>

                            </div>
                            <div>
                                <button class="btn btn-accent">Register</button>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
        <!-- End main content-->

{{--<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

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
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>--}}


    </div>

    <script src="{{url('vendor/pacejs/pace.min.js')}}"></script>
    <script src="{{url('vendor/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{url('vendor/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- App scripts -->
    <script src="{{url('scripts/luna.js')}}"></script>
</body>

</html>
