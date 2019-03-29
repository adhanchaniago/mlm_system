<?php
$affiliate = \App\Models\affiliate::whereId(Auth::user()->affiliate_id)->first();
$company = \App\Models\company::whereId($affiliate->company_id)->first();
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    {{--<link rel="stylesheet" type="text/css" href="{{asset('public/css/affiliate_style.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('public/css/style2.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}">

    <!--navigation bar-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--navigation bar end-->
    <style>
        .navbar-align {
            float: right!important;
            margin-right: -25px !important;
        }
        @media only screen and (max-width: 765px) {
            .navbar-align {
                float:left!important;
                margin-right:0px !important;
            }
        }
    </style>
</head>
<body>
<!--resopnsive navigation bar-->
<div class="container-fluid navbar-container">
    <div class="row nav-row">
        <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <div class="navbar-left">
                    @if(isset($company->name))
                        <h4 class="leftheader">{{strtoupper($company->name)}}</h4>
                    @else
                        <h4 class="leftheader">WATCHSQUAD</h4>
                    @endif
                </div>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="container">
                <div class="navbar-collapse collapse">
                    <ul class="navbar-align nav navbar-nav">
                        @if(Request::is('home') || Request::is('/'))
                            <li><a class="type-none-home active" href="#">{{trans('header.home')}}</a></li>
                            {{--<li><a class="type-none-home" href="{{url('marketing/help')}}">{{trans('home.marketing_help')}}</a></li>--}}
                            <li><a class="type-none-home" href="{{url('sales')}}">{{trans('home.my_sales')}}</a></li>
                            <li><a class="type-none-home" href="{{url('stats')}}">{{trans('home.my_affiliates')}}</a></li>
                            <li><a class="type-none-home" href="{{url('myProfile')}}">{{trans('header.samy_MyAccount')}}</a></li>
                            <li><a class="type-none-home" href="{{url('/logout')}}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('header.logout')}}</a>
                            </li>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{ Config::get('languages')[App::getLocale()] }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach (Config::get('languages') as $lang => $language)
                                        @if ($lang != App::getLocale())
                                            <li>
                                                <a href="{{url('language/').'/'.$lang}}">{{$language}}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @elseif(Request::is('confirmEmail'))
                            <li><a class="type-none-home" href="#">{{trans('header.home')}}</a></li>
                            {{--<li><a class="type-none-home" href="#">{{trans('home.marketing_help')}}</a></li>--}}
                            <li><a class="type-none-home" href="#">{{trans('home.my_sales')}}</a></li>
                            <li><a class="type-none-home" href="#">{{trans('home.my_affiliates')}}</a></li>
                            <li><a class="type-none-home" href="#">{{trans('header.samy_MyAccount')}}</a></li>
                            <li><a class="type-none-home" href="{{url('/logout')}}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('header.logout')}}</a>
                            </li>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{ Config::get('languages')[App::getLocale()] }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach (Config::get('languages') as $lang => $language)
                                        @if ($lang != App::getLocale())
                                            <li>
                                                <a href="{{url('language/').'/'.$lang}}">{{$language}}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li><a class="type-none-home" href="{{url('/')}}">{{trans('header.home')}}</a></li>

                            <li><a class="type-none-home <?php if (Request::is('sales')) {
                                    echo "active";
                                } ?>" href="{{url('sales')}}">{{trans('home.my_sales')}}</a></li>
                            <li><a class="type-none-home <?php if (Request::is('stats')) {
                                    echo "active";
                                } ?>" href="{{url('stats')}}">{{trans('home.my_affiliates')}}</a></li>
                            <li><a class="type-none-home <?php if (Request::is('myProfile')) {
                                    echo "active";
                                } ?>" href="{{url('myProfile')}}">{{trans('header.samy_MyAccount')}}</a></li>
                            <li><a class="type-none-home" href="{{url('/logout')}}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('header.logout')}}</a>
                            </li>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                  style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{ Config::get('languages')[App::getLocale()] }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach (Config::get('languages') as $lang => $language)
                                        @if ($lang != App::getLocale())
                                            <li>
                                                <a href="{{url('language/').'/'.$lang}}">{{$language}}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif


                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!--resopnsive navigation bar end-->