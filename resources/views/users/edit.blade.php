@include('frontEnd.profile_header')
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<style>
    td, th {
        padding: 0;
        line-height: 1.5;
        vertical-align: text-bottom;
    }
    .renew_btn{
        background: #43409f;
        color: white;
        border-radius: 20px !important;
        padding: 4px 10px;
        margin-left: 5px;
    }
    .renew_btn:hover{
        color: white;
    }
    /*.switch {*/
        /*position: relative;*/
        /*display: inline-block;*/
        /*width: 60px;*/
        /*height: 34px;*/
    /*}*/
    /*.switch_on {*/
        /*background-color: limegreen;*/
        /*color:white;*/
    /*}*/
    /*.switch_on .toggle {*/
        /*float: right;*/
    /*}*/
    /*.switch_on .slider {*/
        /*left: 25px;*/
    /*}*/
    /*.switch_off{*/
        /*background-color: red;*/
        /*color:white;*/
    /*}*/
    /*.slider {*/
        /*position: absolute;*/
        /*top: 0;*/
        /*left: 0;*/
        /*right: 0;*/
        /*bottom: 0;*/
        /*-webkit-transition: .4s;*/
        /*transition: .4s;*/
    /*}*/
    /*.slider:before {*/
        /*position: absolute;*/
        /*content: "";*/
        /*height: 26px;*/
        /*width: 26px;*/
        /*left: 4px;*/
        /*bottom: 4px;*/
        /*background-color: white;*/
        /*-webkit-transition: .4s;*/
        /*transition: .4s;*/
    /*}*/
    /*.slider.round {*/
        /*border-radius: 34px;*/
    /*}*/

    /*.slider.round:before {*/
        /*border-radius: 50%;*/
    /*}*/
    .toggle.btn {
        width: 65px;
        height: 35px;
        border: 1px solid transparent;
        border-radius: 50px;
    }
</style>
<div class="container">
    <div class="row">
        <h1 class="text-center">My Account</h1>
        <div class="col-md-12 col-xs-12 col-sm-12 admin_account-style admin_account">
            <div class="col-md-9 col-xs-12 col-sm-12">
                <form method="post" enctype="multipart/form-data" action="{{url('myProfile').'/'.$company->id}}">
                    {{csrf_field()}}
                    @if ($message = Session::get('success'))
                        <div class="custom-alerts alert alert-success fade in">
                            {!! $message !!}
                        </div>
                        <?php Session::forget('success');?>
                    @endif
                    @if ($message_verification = Session::get('activated'))
                        <div class="custom-alerts alert alert-success fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            {!! $message_verification !!}
                        </div>
                        <?php Session::forget('activated');?>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="custom-alerts alert alert-danger fade in">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                            {!! $message !!}
                        </div>
                        <?php Session::forget('error');?>
                    @endif
                    @include('flash::message')
                    @include('adminlte-templates::common.errors')
                    <div class="col-md-5 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label>{{trans('myProfile.first_name')}}</label>
                            <input type="text" name="fname" value="{{$company->fname}}" class="form-control"
                                   placeholder="{{trans('myProfile.first_name')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.last_name')}}</label>
                            <input type="text" name="lname" value="{{$company->lname}}" class="form-control"
                                   placeholder="{{trans('myProfile.last_name')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.email')}}</label>
                            <input type="email" readonly value="{{$company->email}}" class="form-control"
                                   placeholder="{{trans('myProfile.email')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.phone')}}</label>
                            <input type="text" name="phno" value="{{$company->phno}}" id="phone_number" class="form-control"
                                   placeholder="{{trans('myProfile.phone')}}">
                            <p class="help-block" id="invalidPhone"></p>
                        </div>
                    </div>
                    <div class="col-md-7 col-xs-12 col-sm-12 company-ship-details">
                        <div class="form-group">
                            <label>{{trans('myProfile.address')}}</label>
                            <input type="text" name="address" id="autocomplete" value="{{$company->address}}" class="form-control Account_inputs" placeholder="{{trans('myProfile.address')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.address2')}}</label>
                            <input type="text" name="address2" class="form-control Account_inputs" value="{{$company->address2}}" placeholder="{{trans('myProfile.address2')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.city')}}</label>
                            <input type="text" name="city" value="{{$company->city}}" class="form-control" id="locality" placeholder="{{trans('myProfile.city')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.state')}}</label>
                            <input type="text" name="state" value="{{$company->state}}" id="administrative_area_level_1" class="form-control" placeholder="{{trans('myProfile.state')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.zip')}}</label>
                            <input type="text" name="zip" value="{{$company->zip}}" class="form-control" id="postal_code" placeholder="{{trans('myProfile.zip')}}">
                        </div>
                        <div class="form-group">
                            <label>{{trans('myProfile.country')}}</label>
                            <select name="country" id="country" class="form-control Account_inputs">
                                <option value="" selected disabled>{{trans('home.select_country')}}</option>
                                @foreach($countries as $country)
                                    <option value="{{$country}}" <?php if ($company->country == $country) {
                                        echo "selected";
                                    } ?>>{{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br/><br/>
                    <div class="col-md-12 col-sm-12">
                        <hr class="company-details"/>
                        <div class="col-md-5 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>{{trans('myProfile.company_name')}}</label>
                                <input type="text" id="company-name" name="name" class="form-control"
                                       value="{{$company->name}}" placeholder="{{trans('myProfile.company_name')}}"
                                       onchange="checkcompanyName(this.value)">
                                <p class="help-block" id="error-company-name"></p>
                            </div>
                            <div class="form-group">
                                <label>{{trans('myProfile.company_domain_name')}}</label>
                                <input type="url" id="domain_name" name="domain_name" class="form-control"
                                       value="{{$company->domain_name}}" placeholder="{{trans('myProfile.company_domain_name')}}"
                                       onchange="checkDomain1(this.value,'{{$company->id}}')">
                            </div>
                            <div class="form-group">
                                <label>{{trans('myProfile.domain_name')}}</label>
                                <input type="url" id="actual_domain" name="actual_domain" class="form-control"
                                       value="{{$company->actual_domain}}" placeholder="{{trans('myProfile.domain_name')}}"
                                       onchange="checkDomain(this.value,'{{$company->id}}')">
                                <p class="help-block" id="domain-error"></p>
                            </div>
                            <div class="form-group">
                                <label>{{trans('myProfile.apikey')}}</label>
                                <input type="text" class="form-control" readonly value="{{$company->apikey}}">
                            </div>
                        </div>
                        <div class="col-md-7 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label>{{trans('myProfile.cookie')}}</label>
                                <input type="text" name="cookie_duration" class="form-control"
                                       value="{{$company->cookie_duration}}" placeholder="{{trans('myProfile.cookie')}}">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div id="image">
                                    <br/>
                                    @if(isset($company->logo))
                                        <img src="{{asset('public/avatars').'/'.$company->logo}}"
                                             class="profile-table-img">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <br/>
                                <div class="form-group">
                                    <label>{{trans('myProfile.logo')}}</label>
                                    <input type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" class="form-control Account_inputs" name="logo"
                                           onchange="readURL(this)" placeholder="{{trans('myProfile.logo')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group" style="display:flex;justify-content: center;">
                            <div class="col-md-3 col-sm-4 col-xs-12">
                                <center><button type="submit" id="btn-save-account" class="btn btn-save-profile">{{trans('myProfile.save')}}</button></center>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 admin_account">
                        @if(Auth::user()->special_user != 1)
                            <div class="col-md-7 col-xs-12 col-sm-12 zeropadding account_section2">
                                <div class="col-md-4  col-xs-6 col-sm-4 zeropadding">
                                    <h4>{{trans('myProfile.cards_on_file')}}</h4>
                                </div>
                                <div class="col-lg-4 col-md-5 col-xs-6 col-sm-4">
                                    <button type="button" class="admin-account-button" data-toggle="modal"
                                            data-target="#addCard">{{trans('myProfile.add_new')}}
                                    </button>
                                </div>

                                @foreach($savedCards as $card)
                                    <?php
                                    $show_card_name = $card->digits;
                                    ?>
                                    @if($card->status == 1)
                                        <div class="col-md-12  col-sm-12 col-xs-12 zeropadding">
                                            <div class="col-lg-7 col-md-7  col-sm-7 col-xs-12 zeropadding">
                                                <p class="name_heading">{{$card->brand}} <span class="rightalign cardnumbr">{{$show_card_name}}</span></p>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 trashbtnstyle activeBtn">
                                                <i class="fa fa-trash-o trashicon"></i>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                <button type="button" class="default_active admin-account-button">{{trans('myProfile.default')}}
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-12 col-xs-12 col-sm-12 zeropadding account_section2">
                                            <div class="col-lg-7 col-md-7  col-sm-7 col-xs-12 zeropadding">
                                                <p class="name_heading">{{$card->brand}} <span class="rightalign cardnumbr">{{$show_card_name}}</span></p>
                                            </div>
                                            <div class="col-lg-1 col-md-1  col-sm-1 col-xs-6 trashbtnstyle">
                                                <a class="trashbtnstyle" href="{{url('deletecard').'/'.$card->id}}"
                                                   onclick="return confirm('{{trans('myProfile.sure')}}')"><i class="fa fa-trash-o trashicon"></i></a>
                                            </div>
                                            <div class="col-lg-4 col-md-4  col-sm-4 col-xs-6">
                                                <button class="admin-account-button" type="button"
                                                        onclick="activateCard('{{$card->id}}')">{{trans('myProfile.make_default')}}
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                            {{--````````````````````````````````  Need to work in future  ```````````````````````````````````--}}
                            <div class="col-md-5 col-xs-12 col-sm-5 zeropadding">
                                <div class="col-md-5 col-xs-6 col-sm-6 zeropadding">
                                    {{--                                    <h4>{{trans('myProfile.next_bill')}}</h4>--}}
                                </div>
                                <div class="col-lg-4 col-md-5 col-xs-6 col-sm-6  zeropadding rightalign">
                                    {{--<button type="button" class="admin-account-button" data-toggle="modal"--}}
                                    {{--data-target="#details">{{trans('myProfile.view_detail')}}--}}
                                    {{--</button>--}}
                                </div>
                            </div>
                            <div class="col-md-11 col-xs-12 col-sm-12 zeropadding">

                                <div class="col-md-12 col-xs-12 col-sm-12 zeropadding">
                                    <h4>{{trans('myProfile.your_plans')}}</h4>
                                </div>
                                <br><br><br>
                                <div class="col-md-12 col-xs-12 col-sm-12 account_section2 zeropadding">
                                    <div class="col-md-2 col-xs-12 col-sm-2 zeropadding">
                                        <p class="name_heading">{{trans('myProfile.samy_bot')}}</p>
                                    </div>
                                    @if(Auth::user()->samy_bot == 1 && !empty($samyBotPlans) && $company->bot_disabled != 1)
                                        <div class="col-md-8 col-xs-12 col-sm-8 name_discrip zeropadding">
                                            <table style="font-size: small;width: 100%">
                                                @foreach($samyBotPlans as $samyBotPlan)
                                                    <?php
                                                    $bot_plan = App\Models\SamyBotPlans::whereId($samyBotPlan->plan)->first();
                                                    if(empty($samyBotPlan->subscription_id)){
                                                        if($bot_plan->term == 'month')
                                                        {
                                                            $old_date = date('d-m-Y', strtotime($samyBotPlan->date));
                                                            $expiry_date = date('d-m-Y', strtotime($old_date. ' +30 days'));
                                                        }
                                                        else
                                                        {
                                                            $old_date = date('d-m-Y', strtotime($samyBotPlan->date));
                                                            $expiry_date = date('d-m-Y', strtotime($old_date. ' +365 days'));
                                                        }
                                                        $subscription_status = $samyBotPlan->status;
                                                    }
                                                    $stripe = Stripe::make(env('STRIPE_SECRET'));
                                                    try{
                                                        $subscription = $stripe->subscriptions()->find($company->stripe_id, $samyBotPlan->subscription_id);
                                                        $subscription_status = $samyBotPlan->status;
                                                        $expiry_date =  date('d-m-Y',$subscription['current_period_end']);
                                                    }catch (\Exception $e){
                                                        if($bot_plan->term == 'month')
                                                        {
                                                            $old_date = date('d-m-Y', strtotime($samyBotPlan->date));
                                                            $expiry_date = date('d-m-Y', strtotime($old_date. ' +30 days'));
                                                        }
                                                        else
                                                        {
                                                            $old_date = date('d-m-Y', strtotime($samyBotPlan->date));
                                                            $expiry_date = date('d-m-Y', strtotime($old_date. ' +365 days'));
                                                        }
                                                        $subscription_status = $samyBotPlan->status;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td>{{$bot_plan->name}}</td>
                                                        <td>@if($samyBotPlan->auto_renewal == 1){{trans('myProfile.renews_on')}}@else{{trans('myProfile.expires_on')}}@endif<br> {{$expiry_date}}</td>
                                                        <td>&dollar;{{number_format($samyBotPlan->price)}} / {{$bot_plan->term}}</td>
                                                        @if($subscription_status == "1")
                                                            @if($samyBotPlan->subscription_id == null || empty($samyBotPlan->subscription_id))
                                                                <td>
                                                                    {{--<label class="switch switch_off">--}}
                                                                        {{--<input type="checkbox" id="toggleBtn{{$samyBotPlan->id}}" disabled>--}}
                                                                        {{--<span class="slider round"></span>--}}
                                                                    {{--</label>--}}
                                                                    <input type="checkbox" data-toggle="toggle"  class="switch" data-onstyle="success" disabled id="toggleBtn{{$samyBotPlan->id}}" data-offstyle="danger" onchange="getToggleValue({{$samyBotPlan->id}})">
                                                                    {{--<button type="button" class="btn switch_off" title="{{trans('myProfile.activate')}}" disabled>OFF</button>--}}
                                                                </td>
                                                                <td>
                                                                    <a href="{{url('samybot/RenewBot').'/'.$samyBotPlan->id}}"><button type="button" class="btn renew_btn">{{trans('myProfile.renews')}}</button></a>
                                                                </td>
                                                            @elseif($samyBotPlan->subscription_id != null || !empty($samyBotPlan->subscription_id))
                                                                @if($samyBotPlan->auto_renewal == 1)
                                                                    <td>
                                                                        {{--<label class="switch switch_on">--}}
                                                                            {{--<input type="checkbox" checked  class="toggle" id="toggleBtn{{$samyBotPlan->id}}" onchange="getToggleValue({{$samyBotPlan->id}})">--}}
                                                                            {{--<span class="slider round"></span>--}}
                                                                        {{--</label>--}}
                                                                        <input type="checkbox" checked data-toggle="toggle" class="switch" data-onstyle="success" id="toggleBtn{{$samyBotPlan->id}}" data-offstyle="danger" onchange="getToggleValue({{$samyBotPlan->id}})">
                                                                        {{--<button type="button" class="btn switch_on" title="{{trans('myProfile.de_Activate')}}" onclick="autorenewOff('{{$samyBotPlan->id}}',0)">ON</button>--}}
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        {{--<label class="switch switch_off">--}}
                                                                            {{--<input type="checkbox" id="toggleBtn{{$samyBotPlan->id}}" onchange="getToggleValue({{$samyBotPlan->id}})">--}}
                                                                            {{--<span class="slider round"></span>--}}
                                                                        {{--</label>--}}
                                                                        <input type="checkbox" data-toggle="toggle" class="switch" data-onstyle="success" id="toggleBtn{{$samyBotPlan->id}}" data-offstyle="danger" onchange="getToggleValue({{$samyBotPlan->id}})">
                                                                        {{--<button type="button" class="btn switch_off" title="{{trans('myProfile.activate')}}" onclick="autorenewOn('{{$samyBotPlan->id}}',1)">OFF</button>--}}
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                </td>
                                                            @endif
                                                        @else
                                                        <td>
                                                        </td>
                                                        <td>
                                                            <a href="{{url('samybot/RenewBot').'/'.$samyBotPlan->id}}"><button type="button" class="btn renew_btn">{{trans('myProfile.renews')}}</button></a>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                        <div class="col-md-2 col-xs-12 col-sm-2">
                                            <button type="button" class="admin-account-button" data-toggle="modal" data-target="#bot_history">{{trans('myProfile.billing_history')}}</button>
                                            <br><br>
                                            <a href="{{url('samybot/plans')}}"><button type="button" class="admin-account-button">{{trans('myProfile.purchase_more_bots')}}</button></a>
                                        </div>
                                    @else
                                        <div class="col-md-4 col-xs-4 col-sm-4">
                                            <a href="{{url('samybot/plans')}}">
                                                <button type="button" class="admin-account-button">{{trans('myProfile.sign_up')}}</button>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-xs-6 col-sm-6"></div>
                                    @endif
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 account_section2 zeropadding">
                                    <div class="col-md-2 col-xs-2 col-sm-2 zeropadding">
                                        <p class="name_heading">{{trans('myProfile.samy_affiliate')}}</p>
                                    </div>
                                    @if(Auth::user()->samy_affiliate == 1 && !empty($AffiliatePlans) && $company->affiliate_disabled != 1)
                                        <div class="col-md-8 col-xs-12 col-sm-8 name_discrip zeropadding">
                                            <table style="font-size: small;width: 100%">
                                                <tr>
                                                    <td>{{$AffiliatePlans->type}}</td>
                                                    <td>
                                                        @if($planTable->auto_renewal == 1)
                                                            {{trans('myProfile.renews_on')}}
                                                        @else
                                                            {{trans('myProfile.expires_on')}}
                                                        @endif
                                                            {{date('m/d/Y',strtotime($expirydate))}}
                                                        <br>
                                                        {{$AffiliatePlans->commission}}% {{trans('myProfile.commission')}}
                                                    </td>
                                                    <td>
                                                    @if($AffiliatePlans->term == 'month')
                                                       ${{$AffiliatePlans->amount}}/{{trans('myProfile.month')}}
                                                    @else
                                                        ${{$AffiliatePlans->amount}}/{{trans('myProfile.year')}}
                                                    @endif
                                                    </td>
                                                    @if($planTable->status == 1)
                                                        @if(empty($planTable->stripe_subscription_id) || $planTable->stripe_subscription_id == null)
                                                            <td>
                                                                <button type="button" class="btn switch_off">OFF</button>
                                                            </td>
                                                            <td>
                                                               <a href="{{url('stripe')}}"><button type="button" class="btn renew_btn">{{trans('myProfile.renews')}}</button></a>
                                                            </td>
                                                        @else
                                                            @if($planTable->auto_renewal == 1)
                                                                <td>
                                                                    <input type="checkbox" checked data-toggle="toggle" title="{{trans('myProfile.de_Activate')}}" class="switch" data-onstyle="success" id="affiliateToggle{{$planTable->id}}" data-offstyle="danger" onchange="getAffiliateToggle({{$planTable->id}})">
{{--                                                                    <button type="button" class="btn switch_on" title="{{trans('myProfile.de_Activate')}}" onclick ="autorenewAffiliateOff('{{$planTable->id}}',0)">ON</button>--}}
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <input type="checkbox" data-toggle="toggle" title="{{trans('myProfile.activate')}}" class="switch" data-onstyle="success" id="affiliateToggle{{$planTable->id}}" data-offstyle="danger" onchange="getAffiliateToggle({{$planTable->id}})">
                                                                    {{--<button type="button" class="btn switch_off" title="{{trans('myProfile.activate')}}"  onclick = "autorenewAffiliateOn('{{$planTable->id}}',1)">OFF</button>--}}
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <td>
                                                            <a href="{{url('stripe')}}"><button type="button" class="btn renew_btn">{{trans('myProfile.renews')}}</button></a>
                                                        </td>
                                                    @endif
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12">
                                        <button type="button" class="admin-account-button" data-toggle="modal" data-target="#history">{{trans('myProfile.billing_history')}}</button>
                                    </div>
                                @else
                                    <div class="col-md-4 col-xs-4 col-sm-4">
                                        <a href="{{url('plans')}}">
                                            <button type="button" class="admin-account-button">{{trans('myProfile.sign_up')}}</button>
                                        </a>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6"></div>
                                @endif
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12 zeropadding">
                                <div class="col-md-4 col-xs-4 col-sm-4 zeropadding">
                                    <p class="name_heading">{{trans('myProfile.samy_linkedin')}}</p>
                                </div>
                                @if(Auth::user()->samy_linkedIn == 1 || !empty($LinkedInPlans))
                                    <div class="col-md-4 col-xs-4 col-sm-4 name_discrip zeropadding">
                                        <p>{{$LinkedInPlans->type}}</p>
                                        <p>{{$LinkedInPlans->linkedIn_accounts}} accounts {{trans('myProfile.commission')}}</p>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4 name_discrip">
                                        @if($LinkedInPlans->term == "month")
                                            <p>${{$LinkedInPlans->amount}}/{{trans('myProfile.month')}}
                                                <button type="button" title="{{trans('myProfile.activate')}}" class="btn btn-default btn-xs refreshicon" onclick="autorenew('{{$company->id}}',1)"><i class="fa fa-refresh"></i></button>
                                            </p>
                                        @else
                                            <p>${{$LinkedInPlans->amount}}/{{trans('myProfile.year')}}
                                                <button type="button" title="{{trans('myProfile.activate')}}" class="btn btn-default btn-xs refreshicon" onclick="autorenew('{{$company->id}}',1)"><i class="fa fa-refresh"></i></button>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 col-xs-3 col-sm-3">
                                        <button type="button" class="admin-account-button" data-toggle="modal" data-target="#linkedIn_history">{{trans('myProfile.billing_history')}}</button>
                                    </div>
                                @else
                                    <div class="col-md-4 col-xs-4 col-sm-4">
                                        <a href="#">
                                            <button type="button" class="admin-account-button">{{trans('myProfile.sign_up')}}</button>
                                        </a>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6"></div>
                                @endif
                            </div>
                            <div class="col-md-12 col-xs-12 zeropadding">
                                <div class="col-md-4 col-xs-4 col-sm-4 zeropadding">
                                    <p class="name_heading">{{trans('myProfile.samy_myApp')}}</p>
                                </div>
                                <div class="col-md-4 col-xs-4 col-sm-4">
                                    <a href="#">
                                        <button type="button" class="admin-account-button">{{trans('myProfile.sign_up')}}</button>
                                    </a>
                                </div>
                                <div class="col-md-4 col-xs-4 col-sm-4">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xs-12 col-sm-12">

                        </div>
                    @endif
                </div>
            </form>
                <div class="modal fade" id="addCard" role="dialog">
                    <div class="modal-dialog add-level-modal">
                        <div class="modal-content add-level-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <center><h2 class="modal-title">{{trans('myProfile.add_card')}}</h2></center>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="{{url('SavedCards/Store')}}">
                                    <center>
                                        <p class="help-block" id="card-error"></p>
                                    </center>
                                    {{csrf_field()}}
                                    <div class="form-group col-sm-12">
                                        <label>{{trans('card.card_number')}}: </label>
                                        <input type="text" class="form-control" id="cardnum" name="cardnum" placeholder="{{trans('card.card_number')}}">
                                        <input type="hidden" value="{{Auth::user()->company_id}}" name="company_id">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label>{{trans('card.expire_month')}}: </label>
                                        {{--<input type="text" class="form-control" id="ExpireMonth" name="ccExpiryMonth" placeholder="{{trans('card.expire_month')}}">--}}
                                            <select class="form-control" id="ExpireMonth" name="ccExpiryMonth">
                                                <?php
                                                for ($m=1; $m<=12; $m++) {
                                                    $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                                    if($m < 10){
                                                        echo "<option value=\"0$m\">$month</option>";
                                                    }else{
                                                        echo "<option value=\"$m\">$month</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>{{trans('card.expire_year')}}: </label>
                                            {{--<input type="text" class="form-control" id="ExpireYear" name="ccExpiryYear" placeholder="{{trans('card.expire_year')}}">--}}
                                            <select class="form-control" id="ExpireYear" name="ccExpiryYear">
                                                <?php
                                                $date_future = date("Y", strtotime('+12 year'));
                                                $date_year = date("Y");
                                                for($i=$date_year;$i<$date_future;$i++){
                                                    if($date_year == $i){
                                                        echo "<option value=\"$i\" selected=\"selected\">$i</option> \n";
                                                    } else {
                                                        echo "<option value=\"$i\">$i</option> \n";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label>{{trans('card.cvv')}}: </label>
                                            <input type="password" class="form-control" id="cvv" name="cvvNumber" placeholder="{{trans('card.expire_year')}}">
                                        </div>
                                        <!-- Submit Field -->
                                        <div class="form-group col-sm-12">
                                            <center>
                                                <button type="button" class="btn btn-save" id="addCard-btn" onclick="validateCard()">{{trans('myProfile.save')}}</button>
                                            </center>
                                        </div>
                                    </form>

                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>

                        </div>
                    </div>
                <div class="modal fade" id="history" role="dialog">
                    <div class="modal-dialog modal-lg add-level-modal">
                        <div class="modal-content add-level-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <center><h2 class="modal-title">{{trans('myProfile.billing_history')}}</h2></center>
                            </div>
                            <div class="modal-body table-responsive">
                                <center>
                                    @if($bills != '')
                                        <table class="col-md-12">
                                            <tbody>
                                            @foreach($bills as $bill)
                                                @if($bill->type == '1')
                                                    <?php
                                                    $payPlan = \App\Models\plantable::whereId($bill->planid)->first();
                                                    $payed_date = str_replace('/', '-', $bill->date);
                                                    ?>
                                                    <tr>
                                                        <td class="col-md-1">{{$i}}</td>
                                                        <td class="col-md-2">{{$bill->date}}</td>
                                                        <td class="col-md-2">{{$payPlan->type}}</td>
                                                        <td class="col-md-1">&dollar;{{$bill->amount}}</td>
                                                        <td class="col-md-3"><a href="{{url('invoice').'/'.$bill->id}}" class="btn btn-primaryy">{{trans('myProfile.generate_invoice')}}</a></td>
                                                    </tr>
                                                    <tr class="space-tr">
                                                        <td colspan="7"></td>
                                                    </tr>
                                                    <?php
                                                    $i++;
                                                    ?>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <center>
                                            <b><h4>{{trans('myProfile.no_bill')}}</h4></b>
                                        </center>
                                    @endif
                                </center>
                            </div>
                            <div class="modal-footer">

                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal fade" id="bot_history" role="dialog">
                        <div class="modal-dialog add-level-modal">
                            <div class="modal-content add-level-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <center><h2 class="modal-title">{{trans('myProfile.details')}}</h2></center>
                                </div>
                                <div class="modal-body">
                                    <center>
                                        <table style="font-size: small;">
                                            <?php
                                            $trans_ids = DB::table('bot_plans')->where('company_id',$company->id)->distinct()->get(['transaction_id']);
                                            $i = 1;
                                            ?>
                                            @foreach($trans_ids as $trans_id)
                                                <?php
                                                $j=1;
                                                $samy_bots = DB::table('bot_plans')->where('transaction_id',$trans_id->transaction_id)->where('payment_status',1)->get();
                                                ?>
                                                @foreach($samy_bots as $samy_bot)
                                                    <?php
                                                    $bot_plan = App\Models\SamyBotPlans::whereId($samy_bot->plan)->first();
                                                    $old_date = date('d-m-Y', strtotime($samy_bot->date));
                                                    $expiry_date = date('d-m-Y', strtotime($old_date. ' +30 days'));
                                                    ?>
                                                    <tr>
                                                        @if($j ==1)
                                                            <td width="5%">{{$i}}</td>
                                                        @else
                                                            <td width="5%"> </td>
                                                        @endif
                                                        <td width="25%">{{$bot_plan->name}}</td>
                                                        <td width="25%">Expires on {{$expiry_date}}</td>
                                                        <td width="25%">{{$samy_bot->price}}&dollar; / {{$bot_plan->term}}</td>
                                                        @if($j ==1)
                                                            <td width="10%">
                                                                <a href="{{url('samybot/invoice').'/'.$trans_id->transaction_id}}" class="btn btn-primaryy" style="vertical-align: middle;">{{trans('myProfile.generate_invoice')}}</a>
                                                        @else
                                                            <td width="10%"> </td>
                                                        @endif
                                                    </tr>
                                                    <?php $j++; ?>
                                                @endforeach
                                                <?php $i++; ?>
                                            @endforeach
                                        </table>
                                    </center>
                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>

                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#toggle-one').bootstrapToggle();
    })
</script>
<script>
    function getToggleValue(id) {
        var val = $('#toggleBtn'+id).prop('checked');
        if(val == true){
            autorenewOn(id, 1);
        }else{
            autorenewOff(id, 0);
        }
    }
    function getAffiliateToggle(id) {
        var val = $('#affiliateToggle'+id).prop('checked');
        if(val == true){
            autorenewAffiliateOn(id, 1);
        }else{
            autorenewAffiliateOff(id, 0);
        }
    }
</script>
<script>
    // Restricts input for each element in the set of matched elements to the given inputFilter.
    (function($) {
        $.fn.inputFilter = function(inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                }
            });
        };
    }(jQuery));

    $("#cardnum").inputFilter(function(value) {
        return /^-?\d*$/.test(value);
    });
    $("#cvv").inputFilter(function(value) {
        return /^-?\d*$/.test(value);
    });
    $("#cardnum").focusin(function() {
        $("input[id=cardnum]").attr("maxlength", "16");
    });
    $("#cvv").focusin(function() {
        $("input[id=cvv]").attr("maxlength", "4");
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var html = '<br/><img class="profile-table-img" src="' + e.target.result + '">';
                $('#image')
                    .html(html);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function validateCard() {
        if ($('#cardnum').val() == '') {
            $('#addCard-btn').prop('type', 'button');
            $('#card-error').text('{{trans('card.card_number_required')}}');
        }
        else if ($('#ExpireMonth').val() == '') {
            $('#addCard-btn').prop('type', 'button');
            $('#card-error').text('{{trans('card.expire_month_required')}}');
        }
        else if ($('#ExpireYear').val() == '') {
            $('#addCard-btn').prop('type', 'button');
            $('#card-error').text('{{trans('card.expire_year_required')}}');
        }
        else if ($('#cvv').val() == '') {
            $('#addCard-btn').prop('type', 'button');
            $('#card-error').text('{{trans('card.cvv_required')}}');
        }
        else {
            $('#addCard-btn').prop('type', 'submit');
        }
    }

    function activateCard(id) {
        if (confirm('{{trans('myProfile.activate_card')}}')) {
            $.ajax({
                url: "{{url('activateCard')}}" + "/" + id,
                success: function (result) {
                    window.location.reload();
                }
            });
        }
    }

    function autorenewOff(id, val) {
        if (confirm('{{trans('myProfile.auto_renewal_off_bot')}}')) {
            $.ajax({
                url: "{{url('samybot/autorenew')}}" + "/" + id + "/" + val,
                success: function (result) {
                    window.location.reload();
                }
            });
        }else{
            $('#toggleBtn'+id).prop('checked', true);
            $('#toggleBtn'+id).parent().removeClass("btn-danger off");
            $('#toggleBtn'+id).parent().addClass("btn-success on");
        }
    }
    function autorenewOn(id, val) {
        if (confirm('{{trans('myProfile.auto_renewal_on_bot')}}')) {
            $.ajax({
                url: "{{url('samybot/autorenew')}}" + "/" + id + "/" + val,
                success: function (result) {
                    window.location.reload();
                }
            });
        }else{
            $('#toggleBtn'+id).prop('checked', false);
            $('#toggleBtn'+id).parent().removeClass("btn-success");
            $('#toggleBtn'+id).parent().addClass("btn-default off");
        }
    }

    function autorenewAffiliateOn(id,val) {
        if (confirm('{{trans('myProfile.auto_renewal_on_bot')}}')) {
            $.ajax({
                url: "{{url('autorenewMlm')}}" + "/" + id + "/" + val,
                success: function (result) {
                    window.location.reload();
                }
            });
        }else{
            $('#affiliateToggle'+id).prop('checked', false);
            $('#affiliateToggle'+id).parent().removeClass("btn-success");
            $('#affiliateToggle'+id).parent().addClass("btn-default off");
        }
    }
    function autorenewAffiliateOff(id,val) {
        if (confirm('{{trans('myProfile.auto_renewal_off_bot')}}')) {
            $.ajax({
                url: "{{url('autorenewMlm')}}" + "/" + id + "/" + val,
                success: function (result) {
                    window.location.reload();
                }
            });
        }else{
            $('#affiliateToggle'+id).prop('checked', false);
            $('#affiliateToggle'+id).parent().removeClass("btn-success");
            $('#affiliateToggle'+id).parent().addClass("btn-default off");
        }
    }

    function checkDomain(val, id) {
        var value = val.replace(new RegExp('/', 'g'), 'QWERTY');
        $.ajax({
            url: "{{url('checkDomain')}}" + "/" + value + "/" + id,
            success: function (result) {
                if (result == 'fail') {
                    $('#domain-error').text('{{trans('myProfile.domain_taken')}}');
                    $('#btn-save-account').prop('type', 'button');
                }
                else {
                    var domain = $('#domain_name').val();
                    if (val == domain) {
                        $('#domain-error').text('{{trans('myProfile.domain_same')}}');
                        $('#btn-save-account').prop('type', 'button');
                    }
                    else {
                        $('#btn-save-account').prop('type', 'submit');
                        $('#domain-error').text('');
                    }

                }
            }
        });
    }

    function checkDomain1(val, id) {
        var value = val.replace(new RegExp('/', 'g'), 'QWERTY');
        $.ajax({
            url: "{{url('checkDomain')}}" + "/" + value + "/" + id,
            success: function (result) {
                if (result == 'fail') {
                    $('#domain-error').text('{{trans('myProfile.domain_taken')}}');
                    $('#btn-save-account').prop('type', 'button');
                }
                else {
                    $('#btn-save-account').prop('type', 'submit');
                    $('#domain-error').text('');
                }
            }
        });
    }

    function checkcompanyName(val) {
        $.ajax({
            url: "{{url('checkcompanyName')}}" + "/" + val,
            success: function (result) {
                if (result == 'fail') {
                    $('#error-company-name').text('{{trans('myProfile.name_taken')}}');
                    $('#btn-save-account').prop('type', 'button');
                }
                else {
                    $('#btn-save-account').prop('type', 'submit');
                    $('#error-company-name').text('');
                }
            }
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ_zcalLsl2Lrma87qgAs9QtM-0NQLmYs&libraries=places&callback=initAutocomplete"
        async defer></script>
<!--Footer section-->
@include('frontEnd.footer')

