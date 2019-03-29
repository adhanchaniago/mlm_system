<?php
//use App\Models\plantable;
//$plans = plantable::get();
?>
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Company | Registration Page</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">
    <link rel="stylesheet" href="{{asset('public/css/style2.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    @include('frontEnd.mainHeader')
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">

    </div>

    <div class="register-box-body">
        <p class="login-box-msg">{{trans('auth.register_title')}}</p>

        <form method="post" enctype="multipart/form-data" action="{{ url('/register') }}">
            @if(Session::has('error'))

                <p class="alert alert-danger">{{ Session::get('error') }}</p>
                <?php
                Session::forget('error');
                ?>
            @endif

            {!! csrf_field() !!}

            <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{trans('auth.reg_name_placeholder')}}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('phno') ? ' has-error' : '' }}">
                <input type="text" class="form-control" name="phno" value="+{{$code}}" id="phone_number" placeholder="{{trans('auth.reg_phone_placeholder')}}" onchange="validatePhone()">
                <span class="fa fa-phone form-control-feedback"></span>
                <p class="alert alert-danger" id="invalidPhone"></p>
                @if ($errors->has('phno'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phno') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="{{trans('auth.reg_email_placeholder')}}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" name="password" placeholder="{{trans('auth.reg_psw_placeholder')}}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <input type="password" name="password_confirmation" class="form-control" placeholder="{{trans('auth.reg_confirm_psw_placeholder')}}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('planid') ? ' has-error' : '' }}">
                <input type="text" readonly value="{{$plan->name}}" class="form-control">
                <input type="hidden" value="{{$plan->id}}" readonly name="planid" class="form-control">
                @if ($errors->has('planid'))
                    <span class="help-block">
                        <strong>{{ $errors->first('planid') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" required> {{trans('auth.terms_part1')}} <a class="register-link" href="#">{{trans('auth.terms_part2')}}</a>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{trans('auth.regbtn')}}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{ url('/login') }}" class="text-center register-link">{{trans('auth.regtologin')}}</a>
    </div>
    <!-- /.form-box -->
</div>
<div class="register-row"></div>
<!-- /.register-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
    function validatePhone() {
        var phone_number = $('#phone_number').val();
        var phone_number_1 = phone_number.substring(1);
        if(/^\d+$/.test(phone_number_1) && phone_number.length > 10)
        {
            $('#invalidPhone').css('display','none');
            $.ajax({
                url: "{{url('validatePhone')}}"+'/'+phone_number,
                success: function(result){
                    if (result == "success")
                    {
                        $('#aff-reg-btn').prop('type','submit');
                        $('#invalidPhone').css('display','none');
                    }
                    else
                    {
                        $('#aff-reg-btn').prop('type','button');
                        $('#invalidPhone').text('This phone Number is already exist');
                        $('#invalidPhone').css('display','block');
                    }
                }});
        }
        else
        {
            $('#aff-reg-btn').prop('type','button');
            $('#invalidPhone').text('Please Enter the Valid Phone Number');
            $('#invalidPhone').css('display','block');
        }

    }
</script>
</body>
</html>
