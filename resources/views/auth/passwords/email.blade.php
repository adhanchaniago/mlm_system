@include('frontEnd.mainHeader')
<body class="hold-transition login-page">
<div class="row reset-row"></div>
<div class="login-box">
    <div class="login-logo">
        <center>
            <img src="{{asset('public/image/logo.png')}}" class="img img-responsive LogoImage">
        </center>
        <h3>Samy</h3>
        <h5><i>Your Making Platform</i></h5>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body reset-page-box">
        <p class="login-box-msg">Enter Email to reset password</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="post" action="{{ url('/password/email') }}">
            {!! csrf_field() !!}

            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primaryy pull-right">
                        <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                    </button>
                </div>
            </div>

        </form>

    </div>
    <div class="row reset-row"></div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

@include('frontEnd.mainFooter')

