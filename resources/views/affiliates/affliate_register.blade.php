<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.6.3/css/all.css'
          integrity='sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/' crossorigin='anonymous'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}">

    <!--navigation bar-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--navigation bar end-->
    <style>
        .toggle-button{
            float:right;
        }
        .icons-span{
            z-index: 0!important;
        }

    </style>
</head>
<body>
<!--resopnsive navigation bar-->
<div class="container-fluid navbar-container">
    <div class="row">
        <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header toggle-button">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-left">
                @if(isset($company_name))
                    <h4 class="leftheader">{{strtoupper($company_name)}}</h4>
                @else
                    <h4 class="leftheader">WATCHSQUAD</h4>
                @endif
            </div>
        </div>
    </div>
</div>
<!--resopnsive navigation bar end-->

<div class="container">
    <div class="row">
        <div class="col-md-12 section2_numbers">
            <div class="col-md-5">
                @if(isset($company->logo))
                    <img class="registericon" src="{{asset('public/avatars').'/'.$company->logo}}">
                @else
                    <img class="registericon" src="{{asset('public/pictures/default.jpg')}}">
                @endif
                <form method="post" enctype="multipart/form-data" action="{{ url('/register/affliate') }}">
                    @if(Session::has('error'))
                        <p class="alert alert-danger">{{ Session::get('error') }}</p>
                        <?php
                        Session::forget('error');
                        ?>
                    @endif
                    {!! csrf_field() !!}
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control Account_inputs" name="first_name" placeholder="{{trans('myProfile.first_name')}}">
                        <span class="glyphicon glyphicon-user form-control-feedback icons-span"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control Account_inputs" name="last_name" placeholder="{{trans('myProfile.last_name')}}">
                        <span class="glyphicon glyphicon-user form-control-feedback icons-span"></span>
                    </div>
                    <div class="form-group has-feedback">
                        @if($data['email'] != "")
                        <input type="email" class="form-control Account_inputs" name="email" readonly value="{{$data['email']}}" placeholder="{{trans('auth.reg_email_placeholder')}}">
                        @else
                            <input type="email" class="form-control Account_inputs" name="email" value="{{$data['email']}}" placeholder="{{trans('auth.reg_email_placeholder')}}">
                        @endif
                        <span class="fa fa-envelope form-control-feedback register-form-icons icons-span"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control Account_inputs" value="+{{$code}}" id="phone_number" name="phone" placeholder="{{trans('auth.reg_phone_placeholder')}}" onchange="validatePhone()">
                        <span class="fa fa-phone form-control-feedback register-form-icons icons-span"></span>
                        <p class="help-block" id="invalidPhone"></p>
                    </div>

                    {{--<div class="form-group has-feedback">--}}
                        <input type="hidden" name="company_id" value="{{$data['company']}}" class="form-control" readonly>
                        <input type="hidden" name="invitee" value="{{$data['invitee']}}" class="form-control" readonly>
                        {{--<input type="text" value="{{$company->name}}" class="form-control Account_inputs" readonly>--}}
                        {{--<span class="fas fa-building form-control-feedback register-form-icons icons-span"></span>--}}
                    {{--</div>--}}
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control Account_inputs" name="password" placeholder="{{trans('auth.reg_psw_placeholder')}}">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password_confirmation" class="form-control Account_inputs" placeholder="{{trans('auth.reg_confirm_psw_placeholder')}}">
                        <span class="glyphicon glyphicon-lock form-control-feedback icons-span"></span>
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-12 terms_condition1">
                        <div class="checkbox icheck">
                            <h6>
                                {{trans('auth.terms_condition1')}}&ensp;<a
                                        href="{{url('terms')}}" target="_blank" class="affiliate-terms">{{trans('auth.terms_condition2')}}</a>
                            </h6>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <center><button type="submit" id="aff-reg-btn"
                                class="btn btn-save-affiliate btn-block btn-flat">{{trans('affiliate.regbtn')}}</button></center>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!--Footer section-->
@include('frontEnd.footer')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var html = '<img class="table-img" src="' + e.target.result + '">';
                $('#image').html(html);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function validatePhone() {
        var phone_number = $('#phone_number').val();
//        alert(phone_number.charAt(0));
        var phone_number_1 = phone_number.substring(1);
        if (/^\d+$/.test(phone_number_1) && phone_number.length > 10) {
            if (phone_number.charAt(0) == '+')
            {
                $('#invalidPhone').css('display', 'none');
                $.ajax({
                    url: "{{url('validatePhone')}}" + '/' + phone_number,
                    success: function (result) {
                        if (result == "success") {
                            $('#aff-reg-btn').prop('type', 'submit');
                            $('#invalidPhone').css('display', 'none');
                        }
                        else {
                            $('#aff-reg-btn').prop('type', 'button');
                            $('#invalidPhone').text('{{trans('phoneError.phone_exists')}}');
                            $('#invalidPhone').css('display', 'block');
                        }
                    }
                });
            }
            else
            {
                $('#aff-reg-btn').prop('type', 'button');
                $('#invalidPhone').text('{{trans('phoneError.phone_valid')}}');
                $('#invalidPhone').css('display', 'block');
            }
        }
        else {
            $('#aff-reg-btn').prop('type', 'button');
            $('#invalidPhone').text('{{trans('phoneError.phone_valid')}}');
            $('#invalidPhone').css('display', 'block');
        }

    }
</script>