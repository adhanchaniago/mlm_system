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
        @if(Auth::user() && (Auth::user()->samy_affiliate == 1 || Auth::user()->samy_bot == 1))
            <?php
            $address = App\Models\company::whereId(Auth::user()->company_id)->first();
            ?>
            <div class="col-md-12 col-xs-12 stats_section1 admin_account">
                <div class="col-md-8 col-xs-12">
                    <form method="post" enctype="multipart/form-data" action="{{ url('samybot/proceed_to_pay') }}">
                        {{csrf_field()}}
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <input type="text" name="first_name" class="form-control" placeholder="{{trans('myProfile.first_name')}}" value="{{$address->fname}}">
                            <input type="text" name="last_name" class="form-control" placeholder="{{trans('myProfile.last_name')}}" value="{{$address->lname}}">
                            <input type="email" name="email" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}" value="{{$address->email}}" required>
                            <input type="text" name="phno" id="phone_number" value="{{$address->phno}}" class="form-control" placeholder="{{trans('auth.reg_phone_placeholder')}}">
                            <p class="help-block" id="invalidPhone"></p>
                            <div><br>
                                <label><input type="checkbox" name="shipping" value="1" onclick="SetShippingAddress()">
                                    <span>{{trans('samybot/checkout.shipping_address_same_as_billing')}}</span></label>
                            </div>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-12">
                            <input type="text" name="bill_address" id="bill_address" class="form-control Account_inputs" placeholder="{{trans('myProfile.bill_address')}}" value="{{$address->address}}">
                            <input type="text" name="address2" id="address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}" value="{{$address->address2}}">
                            <input type="text" name="city" id="city" class="form-control" placeholder="{{trans('myProfile.city')}}" value="{{$address->city}}">
                            <input type="text" name="state" id="state" class="form-control" placeholder="{{trans('myProfile.state')}}" value="{{$address->state}}">
                            <input type="text" name="zip" id="zip" class="form-control" placeholder="{{trans('myProfile.zip')}}" value="{{$address->zip}}">
                            <select class="form-control Account_inputs" name="country" id="country" onchange="calculate_shipping()">
                                <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country}}" @if($country == $address->country) selected @endif>{{$country}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="shipping_address1" id="shipping_address1" class="form-control Account_inputs" placeholder="{{trans('samybot/checkout.shipping_address')}}" required>
                            <input type="text" name="shipping_address2" id="shipping_address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                            <input type="text" name="shipping_city" id="shipping_city" class="form-control" placeholder="{{trans('samybot/checkout.city')}}" required>
                            <input type="text" name="shipping_state" id="shipping_state" class="form-control" placeholder="{{trans('samybot/checkout.state')}}" required>
                            <input type="text" name="shipping_zip" id="shipping_zip" class="form-control" placeholder="{{trans('samybot/checkout.zip')}}" required>
                            <select class="form-control Account_inputs" name="shipping_country" id="shipping_country" onchange="calculate_shipping()" required>
                                <option value="" selected disabled>{{trans('samybot/checkout.select_country')}}</option>
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
                                <input type="hidden" value="{{$count}}" name="count" id="count">
                                <?php
                                $SelectedPack = $PlansInput['number_of_devices'];
                                for($i=1;$i<=$count;$i++){
                                if(isset($PlansInput['selected_qty'.$i]) && $PlansInput['selected_qty'.$i] != "" && $PlansInput['selected_qty'.$i] != 0){
                                $plan = App\Models\SamyBotPlans::whereId($PlansInput['selected_plan'.$i])->first();
                                if($PlansInput['selected_pack'.$i] == 1){$unit = "Unit";} else{$unit = "Pack"; }
                                ?>
                                <tr>
                                    <td class="col-md-6 col-xs-6">{{$plan->name}} , {{$PlansInput['selected_pack'.$i]}} {{$unit}}</td>
                                    <td class="col-md-6 col-xs-6 text-right">&dollar;{{$PlansInput['selected_plan_total'.$i]}}</td>

                                </tr>
                                <input type="hidden" value="{{$PlansInput['stripe_plan'.$i]}}" name="stripe_plan{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_plan'.$i]}}" name="selected_plan{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_price'.$i]}}" name="selected_price{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_qty'.$i]}}" name="selected_qty{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_pack'.$i]}}" name="selected_pack{{$i}}">
                                <input type="hidden" value="{{$PlansInput['final_grand_total']}}" name="selected_plan_total{{$i}}" id="GrandTotal{{$i}}">
                                <?php
                                }
                                }
                                ?>
                                <input type="hidden" class="total_shipping_charge" value="" name="shipping_charge">
                                <input type="hidden"  value="{{$PlansInput['grand_activation_charge']}}" name="activation_charge">
                                <input type="hidden" id="grandUserTotal" value="{{$PlansInput['final_grand_total']}}" name="grandTotal">
                                <input type="hidden" value="{{$SelectedPack}}" name="number_of_packs" id="number_of_packs">
                                <tr>
                                    <td class="col-md-6 col-xs-6">{{trans('samybot/common.activation')}}(&dollar;{{$activation->amount}}/{{trans('samybot/common.device')}})</td>
                                    <td class="col-md-6 col-xs-6 text-right">&dollar;{{$PlansInput['grand_activation_charge']}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-6  col-xs-6 summary_total">{{trans('samybot/checkout.shipping')}}(&dollar;<span id="shipping_charge_country_wise">10</span>/{{trans('samybot/common.device')}})</td>
                                    <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;<span id="shipping_charge"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-6  col-xs-6 summary_total"><b>{{trans('samybot/common.total')}}</b></td>
                                    <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;<span id="grand_total">{{$PlansInput['final_grand_total']}}</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <h4 class="terms_condition"><input type="checkbox" required value="1">{{trans('auth.terms_condition1')}}<a href="{{url('terms')}}" target="_blank" class="condition_terms">{{trans('auth.terms_condition2')}}</a> </h4>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <center><button type="submit" id="aff-reg-btn" class="btn proceedbtn">{{trans('auth.regbtn')}}</button></center>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="col-md-12 col-xs-12 stats_section1 admin_account">
                <div class="col-md-8 col-xs-12">
                    <form method="post" enctype="multipart/form-data" action="{{ url('samybot/proceed_payment') }}">
                        {{csrf_field()}}
                        <div class="col-md-4 col-xs-12 col-sm-12">
                            <input type="text" name="first_name" class="form-control" placeholder="{{trans('samybot/checkout.first_name')}}">
                            <input type="text" name="last_name" class="form-control" placeholder="{{trans('samybot/checkout.last_name')}}">
                            @if(isset($specialEmail) && $specialEmail != '' || !empty($specialEmail))
                                <input type="email" name="email" readonly value="{{$specialEmail}}" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}" required>
                            @else
                                <input type="email" name="email" class="form-control" placeholder="{{trans('auth.reg_email_placeholder')}}" required>
                            @endif
                            @if(isset($special) && $special != '' || !empty($special))
                                <input type="hidden" name="invitee" value="{{$special}}">
                            @endif
                            <input type="text" name="phno" id="phone_number_new" value="+{{$code}}" class="form-control" placeholder="{{trans('auth.reg_phone_placeholder')}}">
                            <span class="help-block" id="new_invalidPhone"></span>
                            <input type="password" name="password" class="form-control" placeholder="{{trans('samybot/checkout.password')}}" id="password" onkeyup="validatePassword()">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="{{trans('auth.reg_confirm_psw_placeholder')}}" id="cpassword" onkeyup="validatePassword()">
                            <span id="passwordMismatch" style="color: red;"></span>
                            <div><br>
                                <label><input type="checkbox" name="shipping" value="1" onclick="SetShippingAddress1()">
                                    <span>{{trans('samybot/checkout.shipping_address_same_as_billing')}}</span></label>
                            </div>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-12">
                            <input type="text" name="bill_address" id="new_bill_address" class="form-control Account_inputs" placeholder="{{trans('myProfile.billing_address')}}">
                            <input type="text" name="address2" id="new_address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                            <input type="text" name="city" id="new_city" class="form-control" placeholder="{{trans('myProfile.city')}}">
                            <input type="text" name="state" id="new_state" class="form-control" placeholder="{{trans('myProfile.state')}}">
                            <input type="text" name="zip" id="new_zip" class="form-control" placeholder="{{trans('myProfile.zip')}}">
                            <select class="form-control Account_inputs" name="country" id="new_country" onchange="calculate_shipping1()">
                                <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country}}">{{$country}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="shipping_address1" id="new_shipping_address1" class="form-control Account_inputs" placeholder="{{trans('samybot/checkout.shipping_address')}}" required>
                            <input type="text" name="shipping_address2" id="new_shipping_address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                            <input type="text" name="shipping_city" id="new_shipping_city" class="form-control" placeholder="{{trans('myProfile.city')}}" required>
                            <input type="text" name="shipping_state" id="new_shipping_state" class="form-control" placeholder="{{trans('myProfile.state')}}" required>
                            <input type="text" name="shipping_zip" id="new_shipping_zip" class="form-control" placeholder="{{trans('myProfile.zip')}}" required>
                            <select class="form-control Account_inputs" name="shipping_country" id="new_shipping_country" onchange="calculate_shipping1()" required>
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
                                <input type="hidden" value="{{$count}}" name="count" id="count">
                                <?php
                                $SelectedPack = $PlansInput['number_of_devices'];
                                for($i=1;$i<=$count;$i++){
                                if(isset($PlansInput['selected_qty'.$i]) && $PlansInput['selected_qty'.$i] != "" && $PlansInput['selected_qty'.$i] != 0){
                                $plan = App\Models\SamyBotPlans::whereId($PlansInput['selected_plan'.$i])->first();
                                if($PlansInput['selected_pack'.$i] == 1){$unit = "Unit";} else{$unit = "Pack"; }
                                ?>
                                <tr>
                                    <td class="col-md-6 col-xs-6">{{$plan->name}} , {{$PlansInput['selected_pack'.$i]}} {{$unit}}</td>
                                    <td class="col-md-6 col-xs-6 text-right">&dollar;{{$PlansInput['selected_plan_total'.$i]}}</td>
                                </tr>
                                <input type="hidden" value="{{$PlansInput['stripe_plan'.$i]}}" name="stripe_plan{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_plan'.$i]}}" name="selected_plan{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_price'.$i]}}" name="selected_price{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_qty'.$i]}}" name="selected_qty{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_pack'.$i]}}" name="selected_pack{{$i}}">
                                <input type="hidden" value="{{$PlansInput['selected_plan_total'.$i]}}" name="selected_plan_total{{$i}}">
                                <?php
                                }
                                }
                                ?>
                                <input type="hidden" id="NewgrandUserTotal" value="{{$PlansInput['final_grand_total']}}" name="grandTotal">
                                <input type="hidden" class="total_shipping_charge" value="" name="shipping_charge">
                                <input type="hidden" id="total_activation_charge" value="{{$PlansInput['grand_activation_charge']}}" name="activation_charge">
                                <input type="hidden" value="{{$SelectedPack}}" name="number_of_packs" id="number_of_packs">
                                <tr>
                                    <td class="col-md-6 col-xs-6">{{trans('samybot/common.activation')}}(&dollar;{{$activation->amount}}/{{trans('samybot/common.device')}})</td>
                                    <td class="col-md-6 col-xs-6 text-right">&dollar;{{$PlansInput['grand_activation_charge']}}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-6  col-xs-6 summary_total">{{trans('samybot/checkout.shipping')}}(&dollar;<span id="new_shipping_charge_country_wise">10</span>/{{trans('samybot/common.device')}})</td>
                                    <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;<span id="new_shipping_charge"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-md-6  col-xs-6 summary_total"><b>{{trans('home.total')}}</b></td>
                                    <td class="col-md-6  col-xs-6 text-right summary_total">&dollar;<span id="new_grand_total">{{$PlansInput['final_grand_total']}}</span></td>
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
        @endif
    </div>
</div>
<!--Footer section-->
@include('frontEnd.mainFooter')
<script>
    $( document ).ready(function() {
        @if(!isset($PlansInput['final_grand_total']) || empty($PlansInput['final_grand_total']))
        //            window.onbeforeunload = function(event)
        //            {
        //                return confirm("Confirm refresh");
        //            };
        window.history.back();
        @endif
    });
    @if(Auth::user() && (Auth::user()->samy_affiliate == 1 || Auth::user()->samy_bot == 1))
        calculate_shipping();
    @else
        calculate_shipping1();
    @endif
    function calculate_shipping(){
        if($('#shipping_country').val() == "" || $('#shipping_country').val() == null){
            var shipping = '{{$shipping->other}}';
        }else{
            if($('#shipping_country').val() == "United States"){
                var shipping = '{{$shipping->usa}}';
            }else{
                var shipping = '{{$shipping->other}}';
            }
        }
        $('#shipping_charge_country_wise').html(shipping);
        var total_shipping = parseInt(shipping)* parseInt($('#number_of_packs').val());
        $('#shipping_charge').text(total_shipping);
        $('.total_shipping_charge').val(total_shipping);
        var grand_total = parseInt(total_shipping)+ parseInt({{$PlansInput['final_grand_total']}});
        $('#grand_total').text(grand_total);
        $('#grandUserTotal').val(grand_total);
    }

    function calculate_shipping1(){
        if($('#new_shipping_country').val() == "" || $('#new_shipping_country').val() == null){
            var shipping = '{{$shipping->other}}';
        }else{
            if($('#new_shipping_country').val() == "United States"){
                var shipping = '{{$shipping->usa}}';
            }else{
                var shipping = '{{$shipping->other}}';
            }
        }
        $('#new_shipping_charge_country_wise').html(shipping);
        var total_shipping = parseInt(shipping)* parseInt($('#number_of_packs').val());
        $('#new_shipping_charge').text(total_shipping);
        $('.total_shipping_charge').val(total_shipping);
        var grand_total = parseInt(total_shipping)+ parseInt({{$PlansInput['final_grand_total']}});
        $('#new_grand_total').text(grand_total);
        $('#NewgrandUserTotal').val(grand_total);
    }

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

    function SetShippingAddress() {
        $('#shipping_address1').val($('#bill_address').val());
        $('#shipping_address2').val($('#address2').val());
        $('#shipping_city').val($('#city').val());
        $('#shipping_state').val($('#state').val());
        $('#shipping_zip').val($('#zip').val());
        $('#shipping_country').val($('#country').val());
        calculate_shipping();
    }

    function SetShippingAddress1() {
        $('#new_shipping_address1').val($('#new_bill_address').val());
        $('#new_shipping_address2').val($('#new_address2').val());
        $('#new_shipping_city').val($('#new_city').val());
        $('#new_shipping_state').val($('#new_state').val());
        $('#new_shipping_zip').val($('#new_zip').val());
        $('#new_shipping_country').val($('#new_country').val());
        calculate_shipping1();
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
