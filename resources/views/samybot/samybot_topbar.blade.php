@if(Auth::user()->status == '1'  || Auth::user()->status == '4')
    @include('frontEnd.admin_header')
@elseif(Auth::user()->status == '2')
    @include('frontEnd.affiliate.header')
@endif
<link rel="stylesheet" href="{{asset('public/css/samybot_style.css')}}">
<style>
    .home-page-menu {
        background: url({{asset('public/image/home.jpg')}});
        background-position: center;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        padding: 5px 0px 50px 15px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 home-page-menu">
            <div class="col-md-4 col-sm-6 home-navigation-link">
                <a href="{{url('samybot/campaigns')}}" class="type-none @if(Request::is('samybot/campaigns')) active @endif">
                    <li><h1>{{trans('samybot/samybot_nav.my_campaign')}}</h1></li>
                </a>
                <a href="{{url('samybot/samy_bots')}}" class="type-none @if(Request::is('samybot/samy_bots')) active @endif">
                    <li><h1>{{trans('samybot/samybot_nav.my_samy_bots')}}</h1></li>
                </a>
                <a href="{{url('samybot/new_campaign')}}" class="type-none @if(Request::is('samybot/new_campaign')) active @endif">
                    <li><h1>{{trans('samybot/samybot_nav.new_campaign')}}</h1></li>
                </a>
                <a href="{{url('samybot/my_prospects')}}" class="type-none @if(Request::is('samybot/my_prospects')) active @endif">
                    <li><h1>{{trans('samybot/samybot_nav.my_prospects')}}</h1></li>
                </a>
                <a href="{{url('samybot/favorite_users')}}" class="type-none @if(Request::is('samybot/favorite_users')) active @endif">
                    <li><h1>My Favorites</h1></li>
                </a>
            </div>
        </div>
    </div>
</div>