@if(Auth::user()->status == '1' || Auth::user()->status == '4')
    @include('frontEnd.admin_header')
@elseif(Auth::user()->status == '2')
    @include('frontEnd.affiliate.header')
@endif
<?php
$userTypeId = Auth::user()->company_id;
$comp = App\Models\company::whereId($userTypeId)->first();
$status = Auth::user()->samy_bot;
$affiliate_status = Auth::user()->samy_affiliate;
?>
<div class="container">
    <div class="row samyhome-row">
        <center>
            <h1 class="samyhome-welcome-text">{{trans('welcome.welcome')}}</h1>
        </center>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3 col-sm-3 samhome-menu-div">
                <div class="main-home-head">
                    <center>
                        <h4 class="head-foot-text">{{trans('header.samy_bot')}}</h4>
                    </center>
                </div>
                <div class="main-home-body">
                    <img src="{{asset('public/pictures/samyJunior.jpg')}}" class="samyhome-image">
                </div>
                <div class="main-home-foot">
                    <center>
                        @if($status == 1)
                            <a href="{{asset('samybot/campaigns')}}"><h4 class="head-foot-text">{{trans('welcome.open')}}</h4></a>
                        @else
                            <a href="{{asset('samybot/plans')}}"><h4 class="head-foot-text">{{trans('welcome.sign_up')}}</h4></a>
                        @endif
                    </center>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 samhome-menu-div">
                <div class="main-home-head">
                    <center>
                        <h4 class="head-foot-text">{{trans('header.samy_affiliate')}}</h4>
                    </center>
                </div>
                <div class="main-home-body">
                    <img src="{{asset('public/pictures/DsIEwBSWkAEZz-H.jpg')}}" class="samyhome-image">
                </div>
                <div class="main-home-foot">
                    <center>
                        @if($affiliate_status == 1)
                            <a href="{{url('home')}}"><h4 class="head-foot-text">{{trans('welcome.open')}}</h4></a>
                        @else
                            <a href="{{url('plans')}}"><h4 class="head-foot-text">{{trans('welcome.sign_up')}}</h4></a>
                        @endif
                    </center>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 samhome-menu-div">
                <div class="main-home-sub">
                    <center>
                        <h4 class="head-foot-text">{{trans('header.samy_linkedIn')}}</h4>
                    </center>
                </div>
                <div class="main-home-body">
                    <img src="{{asset('public/pictures/linkin-new.jpg')}}" class="samyhome-image">
                </div>
                <div class="main-home-sub">
                    <center>
                        <h4 class="head-foot-text">{{trans('welcome.coming_soon')}}</h4>
                    </center>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 samhome-menu-div">
                <div class="main-home-sub">
                    <center>
                        <h4 class="head-foot-text">{{trans('header.samy_MyApp')}}</h4>
                    </center>
                </div>
                <div class="main-home-body">
                    <img src="{{asset('public/pictures/mobile-block.png')}}" class="samyhome-image">
                </div>
                <div class="main-home-sub">
                    <center>
                        <h4 class="head-foot-text">{{trans('welcome.coming_soon')}}</h4>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row samyhome-row-bottom">

</div>
@include('frontEnd.footer')