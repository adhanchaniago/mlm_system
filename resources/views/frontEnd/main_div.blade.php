@include('frontEnd.admin_header')
<style>
    .home-page-menu {
        background: url({{asset('public/image/home.jpg')}});
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 home-page-menu">
            @if(Request::is('home') || Request::is('/'))
                <div class="col-md-4 col-sm-6 col-xs-12 home-navigation-link">
                    <a href="#" class="active type-none">
                        <li><h1>{{trans('home.my_stats')}}</h1></li>
                    </a>
                    <a href="{{url('levels')}}" class="type-none">
                        <li><h1>{{trans('home.my_levels')}}</h1></li>
                    </a>
                    <a href="{{url('ranks')}}" class="type-none">
                        <li><h1>{{trans('home.my_ranks')}}</h1></li>
                    </a>
                    <a href="{{url('payouthistories')}}" class="type-none">
                        <li><h1>{{trans('home.my_payouts')}}</h1></li>
                    </a>
                    <a href="{{url('affiliates')}}" class="type-none">
                        <li><h1>{{trans('home.my_affiliates')}}</h1></li>
                    </a>
                </div>
            @else
                <div class="col-md-4 col-sm-6 col-xs-12 home-navigation-link">
                    <a href="{{url('home')}}" class="type-none">
                        <li><h1>{{trans('home.my_stats')}}</h1></li>
                    </a>
                    <a href="{{url('levels')}}" class="<?php if (Request::is('levels')) {echo "active ";} ?>type-none">
                        <li><h1>{{trans('home.my_levels')}}</h1></li>
                    </a>
                    <a href="{{url('ranks')}}" class="<?php if (Request::is('ranks')) {
                        echo "active ";
                    } ?>type-none">
                        <li><h1>{{trans('home.my_ranks')}}</h1></li>
                    </a>
                    <a href="{{url('payouthistories')}}" class="<?php if (Request::is('payouthistories')) {
                        echo "active ";
                    } ?>type-none">
                        <li><h1>{{trans('home.my_payouts')}}</h1></li>
                    </a>
                    <a href="{{url('affiliates')}}" class="<?php if (Request::is('affiliates')) {echo "active ";} ?>type-none">
                        <li><h1>{{trans('home.my_affiliates')}}</h1></li>
                    </a>
                </div>
            @endif
            @if(Request::is('home') || Request::is('/'))
                <div class="col-md-4 col-sm-6">

                </div>
            @else
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <iframe class="levelsvideo home_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>
                </div>
            @endif

        </div>
    </div>
</div>