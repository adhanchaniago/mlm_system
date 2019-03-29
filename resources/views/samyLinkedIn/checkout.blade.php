@include('frontEnd.mainHeader')
<style>
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: gray !important;
        font-size: 17px;
    }
    .summary {
        border: 1px solid lightgray;
        color: gray;
        line-height: 1.5;
    }
    .summary_total {
        color: gray;
        font-weight: bold;
    }
    .proceedbtn{
        margin-top: 10%;
        background-color: #ff5722!important;
        color: white!important;
        font-size: 15px!important;
        border: 1px solid lightgray!important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <br>
        @include('flash::message')
        <h1 class="text-center">{{trans('samybot/checkout.checkout')}}</h1>
        @if((Auth::user() && Auth::user()->samy_affiliate == 1))
            <?php
            $address = App\Models\company::whereId(Auth::user()->typeid)->first();
            ?>
            <div class="col-md-12 col-xs-12 stats_section1 admin_account">
                <div class="col-md-8 col-xs-12">
                    <form method="post" enctype="multipart/form-data" action="{{ url('samylinkedIn/proceed_to_pay') }}">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$plan->id}}" name="plan">
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <input type="text" name="first_name" class="form-control" placeholder="{{trans('myProfile.first_name')}}" value="{{$address->first_name}}">
                            <input type="text" name="last_name" class="form-control" placeholder="{{trans('myProfile.last_name')}}" value="{{$address->last_name}}">
                            <input type="email" name="email" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}" value="{{$address->email}}">
                            <input type="text" name="phno" id="phone_number" value="{{$address->phno}}" class="form-control" placeholder="{{trans('auth.reg_phone_placeholder')}}" onchange="validatePhone()">
                            <p class="help-block" id="invalidPhone"></p>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-12">
                            <input type="text" name="bill_address" id="bill_address" class="form-control Account_inputs" placeholder="{{trans('myProfile.bill_address')}}" value="{{$address->bill_address}}">
                            <input type="text" name="address2" id="address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}" value="{{$address->address2}}">
                            <input type="text" name="city" id="city" class="form-control" placeholder="{{trans('myProfile.city')}}" value="{{$address->city}}">
                            <input type="text" name="state" id="state" class="form-control" placeholder="{{trans('myProfile.state')}}" value="{{$address->state}}">
                            <input type="text" name="zip" id="zip" class="form-control" placeholder="{{trans('myProfile.zip')}}" value="{{$address->zip}}">
                            <select class="form-control Account_inputs" name="country" id="country">
                                <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country}}" @if($country == $address->country) selected @endif>{{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <h3>{{trans('home.summary')}}</h3>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <table class="col-md-12 col-xs-12 summary">
                                <tbody>
                                <tr>
                                    <td class="col-md-6 col-xs-6">{{$plan->name}}</td>
                                    <td class="col-md-6 col-xs-6 text-right">&dollar;{{$plan->amount}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-6  col-xs-6 summary_total"><b>{{trans('samybot/common.total')}}</b></td>
                                    <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;{{$plan->amount}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3 col-xs-12 col-md-offset-9">
                            <button type="submit" class="btn proceedbtn" id="aff-reg-btn">{{trans('samybot/checkout.place_your_order')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="col-md-12 col-xs-12 stats_section1 admin_account">
                <div class="col-md-8 col-xs-12">
                    <form method="post" enctype="multipart/form-data" action="{{ url('samylinkedIn/proceed_payment') }}">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$plan->id}}" name="plan">
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <input type="text" name="first_name" class="form-control" placeholder="{{trans('samybot/checkout.first_name')}}">
                            <input type="text" name="last_name" class="form-control" placeholder="{{trans('samybot/checkout.last_name')}}">
                            <input type="email" name="email" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}">
                            <input type="text" name="phno" id="phone_number_new" value="" class="form-control" placeholder="{{trans('auth.reg_phone_placeholder')}}" onchange="validatePhone1()">
                            <span class="help-block" id="new_invalidPhone"></span>
                            <input type="password" name="password" class="form-control" placeholder="{{trans('samybot/checkout.password')}}" id="password" onkeyup="validatePassword()">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{trans('auth.reg_confirm_psw_placeholder')}}" id="cpassword" onkeyup="validatePassword()">
                            <span id="passwordMismatch" style="color: red;"></span>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-12">
                            <input type="text" name="bill_address" id="new_bill_address" class="form-control Account_inputs" placeholder="{{trans('myProfile.billing_address')}}">
                            <input type="text" name="address2" id="new_address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                            <input type="text" name="city" id="new_city" class="form-control" placeholder="{{trans('myProfile.city')}}">
                            <input type="text" name="state" id="new_state" class="form-control" placeholder="{{trans('myProfile.state')}}">
                            <input type="text" name="zip" id="new_zip" class="form-control" placeholder="{{trans('myProfile.zip')}}">
                            <select class="form-control Account_inputs" name="country" id="new_country">
                                <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country}}">{{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <h3>{{trans('home.summary')}}</h3>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <table class="col-md-12 col-xs-12 summary">
                                <tbody>
                                <tr>
                                    <td class="col-md-6 col-xs-6">{{$plan->name}}</td>
                                    <td class="col-md-6 col-xs-6 text-right">&dollar;{{$plan->amount}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-6  col-xs-6 summary_total"><b>{{trans('samybot/common.total')}}</b></td>
                                    <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;{{$plan->amount}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3 col-xs-12 col-md-offset-9">
                            <button type="submit" class="btn proceedbtn" id="new_aff-reg-btn">{{trans('samybot/checkout.place_your_order')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
<!--Footer section-->
@include('frontEnd.mainFooter')
<script>
function validatePhone() {
var phone_number = $('#phone_number').val();
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
function validatePhone1() {
var phone_number = $('#phone_number_new').val();
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
                    $('#new_invalidPhone').css('display', 'none');
                }
                else {
                    $('#aff-reg-btn').prop('type', 'button');
                    $('#new_invalidPhone').text('{{trans('phoneError.phone_exists')}}');
                    $('#new_invalidPhone').css('display', 'block');
                }
            }
        });
    }
    else
    {
        $('#aff-reg-btn').prop('type', 'button');
        $('#new_invalidPhone').text('{{trans('phoneError.phone_valid')}}');
        $('#new_invalidPhone').css('display', 'block');
    }
}
else {
    $('#aff-reg-btn').prop('type', 'button');
    $('#new_invalidPhone').text('{{trans('phoneError.phone_valid')}}');
    $('#new_invalidPhone').css('display', 'block');
}

}
function validatePassword() {
if($('#cpassword').val() != "") {
    if ($('#password').val() != $('#cpassword').val()) {
        $('#new_aff-reg-btn').prop('type', 'button');
        $('#passwordMismatch').text('Password Mismatched');
    } else {
        $('#passwordMismatch').text('');
        $('#new_aff-reg-btn').prop('type', 'submit');
    }
}
}
</script>
