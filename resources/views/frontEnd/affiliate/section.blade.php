<?php
    $affiliate = \App\Models\affiliate::whereId(Auth::user()->affiliate_id)->first();
    $company = \App\Models\company::whereId($affiliate->company_id)->first();
?>
<style>
    .affiliate_home_section1 {
        background-image: url('{{url('public/pictures/02_about-us-1024x304.jpg')}}');
        background-repeat: no-repeat;
        height: 300px;
        width: 100%;
        background-size: 100% 100%;
        color: white;
    }
</style>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12 affiliate_home_section1">
        <div class="col-md-12 col-xs-12 col-sm-12 section1_logo">
            <div class="col-md-2 col-xs-12 col-sm-4 section2_numbers">
                @if(isset($company->logo))
                    <img class="section-image" src="{{asset('public/avatars').'/'.$company->logo}}">
                @else
                    <img class="section-image" src="{{asset('public/pictures/logo.PNG')}}">
                @endif
            </div>
            <div class="col-md-8 col-xs-12 col-sm-8">
                <h1 class="text-center home_heading">{{strtoupper($pageHeader)}}</h1>
            </div>
        </div>
    </div>
</div>