@include('frontEnd.mainHeader')
<style>
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: gray !important;
        font-size: 17px;
    }

    .summary {
        color: gray;
    }

    .summary_total {
        color: gray;
        font-weight: bold;
    }
    .help-block
    {
        color: red;
    }
    .proceedbtn{
        margin-top: 10%;
        background-color: #ff5722!important;
        color: white!important;
        font-size: 15px!important;
        border: 1px solid lightgray!important;
        width: 100px;
        padding: 10px;
        border-radius: 0;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <h1 class="text-center">{{trans('home.checkout')}}</h1>
        <div class="col-md-12 col-xs-12 stats_section1 admin_account">
            <div class="col-md-8 col-xs-12">
                <form method="post" enctype="multipart/form-data" action="{{ url('/register') }}">
                {{csrf_field()}}
                    <input type="hidden" value="{{$plan->stripe_plan_id}}" name="stripe_plan_id">
                    @if(Session::has('error'))
                        <p class="alert alert-danger">{{ Session::get('error') }}</p>
                        <?php
                        Session::forget('error');
                        ?>
                    @endif
                    @if(!isset($company))
                            <div class="col-md-4 col-xs-12 col-sm-12">
                                <input type="text" name="fname" class="form-control" placeholder="{{trans('myProfile.first_name')}}">
                                <input type="hidden" name="type" value="new">
                                @if ($errors->has('fname'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('fname') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="lname" class="form-control" placeholder="{{trans('myProfile.last_name')}}">
                                @if ($errors->has('lname'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('lname') }}</strong>
                            </span>
                                @endif
                                <input type="email" name="email" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                                @endif
                                <input type="password" name="password" class="form-control" placeholder="{{trans('auth.reg_psw_placeholder')}}">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                 <strong>{{ $errors->first('password') }}</strong>
                            </span>
                                @endif
                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{trans('auth.reg_confirm_psw_placeholder')}}">
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                 <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="phno" id="phone_number" value="+{{$code}}" class="form-control" placeholder="{{trans('auth.reg_phone_placeholder')}}">
                                @if ($errors->has('phno'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('phno') }}</strong>
                                <strong></strong>
                            </span>
                                @endif
                                <p class="help-block" id="invalidPhone"></p>
                                <input type="hidden" name="planid" class="form-control" value="{{$plan->id}}">
                            </div>
                            <div class="col-md-8 col-xs-12 col-sm-12">
                                <input type="text" name="bill_address" class="form-control Account_inputs" placeholder="{{trans('myProfile.billing_address')}}">
                                @if ($errors->has('bill_address'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('bill_address') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                                @if ($errors->has('address2'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('address2') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="city" class="form-control" placeholder="{{trans('myProfile.city')}}">
                                @if ($errors->has('city'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="state" class="form-control" placeholder="{{trans('myProfile.state')}}">
                                @if ($errors->has('state'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('state') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="zip" class="form-control" placeholder="{{trans('myProfile.zip')}}">
                                @if ($errors->has('zip'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('zip') }}</strong>
                            </span>
                                @endif
                                <select class="form-control Account_inputs" name="country">
                                    <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country}}">{{$country}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('country'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('country') }}</strong>
                            </span>
                                @endif
                            </div>
                    @else
                            <div class="col-md-4 col-xs-12 col-sm-12">
                                <input type="text" name="fname" value="{{$company->fname}}" class="form-control" placeholder="{{trans('myProfile.first_name')}}">
                                <input type="hidden" name="type" value="old">
                                <input type="hidden" name="userid" value="{{Auth::user()->id}}">
                                <input type="hidden" name="company_id" value="{{$company->id}}">
                                @if ($errors->has('fname'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('fname') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="lname" value="{{$company->lname}}" class="form-control" placeholder="{{trans('myProfile.last_name')}}">
                                @if ($errors->has('lname'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('lname') }}</strong>
                            </span>
                                @endif
                                <input type="email" name="email" readonly value="{{$company->email}}" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                                @endif

                                <input type="text" name="phno" id="phone_number" value="{{$company->phno}}" class="form-control" placeholder="{{trans('auth.reg_phone_placeholder')}}">
                                @if ($errors->has('phno'))
                                    <span class="help-block">
                                <strong>{{ $errors->first('phno') }}</strong>
                                <strong></strong>
                            </span>
                                @endif
                                <p class="help-block" id="invalidPhone1"></p>
                                <input type="hidden" name="planid" class="form-control" value="{{$plan->id}}">
                            </div>
                            <div class="col-md-8 col-xs-12 col-sm-12">
                                <input type="text" name="bill_address" value="{{$company->address}}" class="form-control Account_inputs" placeholder="{{trans('myProfile.billing_address')}}">
                                @if ($errors->has('bill_address'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('bill_address') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="address2" value="{{$company->address2}}" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                                @if ($errors->has('address2'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('address2') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="city" value="{{$company->city}}" class="form-control" placeholder="{{trans('myProfile.city')}}">
                                @if ($errors->has('city'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="state" value="{{$company->state}}" class="form-control" placeholder="{{trans('myProfile.state')}}">
                                @if ($errors->has('state'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('state') }}</strong>
                            </span>
                                @endif
                                <input type="text" name="zip" value="{{$company->zip}}" class="form-control" placeholder="{{trans('myProfile.zip')}}">
                                @if ($errors->has('zip'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('zip') }}</strong>
                            </span>
                                @endif
                                <select class="form-control Account_inputs" name="country">
                                    <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country}}" <?php if ($company->country == $country) { echo "selected";} ?>>{{$country}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('country'))
                                    <span class="help-block">
                        <strong>{{ $errors->first('country') }}</strong>
                            </span>
                                @endif
                            </div>
                    @endif

                <div class="col-md-12 col-xs-12">
                    <br><h3>{{trans('home.summary')}}</h3><br>
                </div>
                <div class="col-md-12 col-xs-12 table-responsive">
                    <table class="col-md-12 col-xs-12 table-responsive-table">
                        <tbody class="summary">
                        <tr>
                            <td class="col-md-6 col-xs-6">{{strtoupper($plan->type)}} , 1 {{trans('home.unit')}}</td>
                            <td class="col-md-6 col-xs-6 text-right">&dollar;{{$plan->amount}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-6  col-xs-6 summary_total">{{trans('home.total')}}</td>
                            <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;{{$plan->amount}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <h4 class="terms_condition"><input type="checkbox" required>{{trans('auth.terms_condition1')}}<a href="{{url('terms')}}" target="_blank" class="condition_terms">{{trans('auth.terms_condition2')}}</a> </h4>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <center><button type="submit" id="aff-reg-btn" class="btn proceedbtn">{{trans('auth.regbtn')}}</button></center>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!--Footer section-->
@include('frontEnd.footer')
<script>
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
