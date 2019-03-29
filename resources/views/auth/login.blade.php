@include('frontEnd.header')
<div class="login-box">
    <div class="login-logo">
        <center>
        @if($company!="" &&isset($company->logo))
            <img src="{{asset('public/avatars').'/'.$company->logo}}" class="img img-responsive LogoImage">
        @else
            <img src="{{asset('public/image/logo.png')}}" class="img img-responsive LogoImage">
        @endif
        </center>
        @if($company!="")
            <h3>{{$company->name}}</h3>
        @else
            <h3>{{trans('auth.samy')}}</h3>
            <h5><i>{{trans('auth.samy_tag')}}</i></h5>
        @endif
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">

        <form method="post" action="{{ url('/login') }}">
            {!! csrf_field() !!}
            @include('flash::message')
            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{trans('auth.email')}}">
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="{{trans('auth.psw')}}" name="password">
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif

            </div>
            <div class="row">
                    <div class="col-md-4"></div>
                <div class="col-md-4">
                    <center><button type="submit" class="btn btn-info btn-lg btn-login">{{trans('header.login')}}</button></center>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br/>
        <center>
            <a class="login-link" href="{{ url('/password/reset') }}">{{trans('auth.forgot')}}</a>
            <br>
            <?php
                $domain = request()->getHost();
            ?>
            @if($domain == 'samy-tech.com')
            <a class="login-link" href="{{ url('/password/reset') }}">{{trans('auth.new_register')}}</a><br>
            @endif
        </center>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
@include('frontEnd.footer')
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
