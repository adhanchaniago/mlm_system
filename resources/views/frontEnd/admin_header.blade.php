<?php
$company = \App\Models\company::whereId(Auth::user()->company_id)->first();
?>
<html>
<head>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <style>
        .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            width: 100%;
        }
        .navbar-align {
            float: right!important;
            margin-right: -25px !important;
        }
        @media only screen and (max-width: 765px) {
            .navbar-align {
                float: left!important;
                margin-right:0 !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/style2.css')}}">
    {{--<link rel="stylesheet" href="{{asset('public/css/main.css')}}">--}}
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    {{--<link rel="stylesheet" href="{{asset('public/css/style_tree.css')}}">--}}
    {{--<link rel="stylesheet" href="{{asset('public/css/responsive.css')}}">--}}
    <link rel="stylesheet" href="{{asset('public/css/style_admin.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/responsive_admin.css')}}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>
<!--resopnsive navigation bar-->
<div class="container-fluid navbar-container">
    <div class="row nav-row">
        <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
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
                        @if(Request::is('home') || Request::is('/') || Request::is('dashboard') || Request::is('levels') || Request::is('ranks') || Request::is('payouthistories') || Request::is('affiliates'))
                            @if(Auth::user()->samy_bot == 1)
                                <li class=""><a href="{{url('samybot/campaigns')}}">{{trans('header.samy_bot')}}</a></li>
                            @else
                                <li class=""><a href="{{url('samybot/plans')}}">{{trans('header.samy_bot')}}</a></li>
                            @endif
                            <li class="active"><a href="#">{{trans('header.samy_affiliate')}}</a></li>
                            {{--                            <li class=""><a href="#">{{trans('header.samy_linkedIn')}}</a></li>--}}
                            {{--                            <li class=""><a href="#">{{trans('header.samy_MyApp')}}</a></li>--}}
                            <li class=""><a href="{{url('myProfile')}}">{{trans('header.samy_MyAccount')}}</a></li>
                            <li class="dropdown <?php if (Request::is('emailcontents') || Request::is('paypalCredentials')) { echo " active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">{{trans('home.setting')}}<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('emailcontents.index')}}">{{trans('home.smtp_email')}}</a></li>
                                    <li><a href="{{url('mailchimp/index')}}">MailChimp Settings</a></li>
                                </ul>
                            </li>

                            <li class=""><a href="{{url('/logout')}}"
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
                        @elseif(Request::is('samybot/campaigns') || Request::is('samybot/samy_bots') || Request::is('samybot/new_campaign'))
                            <li class="active"><a href="#">{{trans('header.samy_bot')}}</a></li>
                            @if(Auth::user()->samy_affiliate == 1)
                                <li class=""><a href="{{url('home')}}">{{trans('header.samy_affiliate')}}</a></li>
                            @else
                                <li class=""><a href="{{url('plans')}}">{{trans('header.samy_affiliate')}}</a></li>
                            @endif
                            {{--                            <li class=""><a href="#">{{trans('header.samy_linkedIn')}}</a></li>--}}
                            {{--                            <li class=""><a href="#">{{trans('header.samy_MyApp')}}</a></li>--}}
                            <li class=""><a href="{{url('myProfile')}}">{{trans('header.samy_MyAccount')}}</a></li>
                            <li class="dropdown <?php if (Request::is('emailcontents') || Request::is('paypalCredentials')) { echo " active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">{{trans('home.setting')}}<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('emailcontents.index')}}">{{trans('home.smtp_email')}}</a></li>
                                    <li><a href="{{url('mailchimp/index')}}">MailChimp Settings</a></li>
                                </ul>
                            </li>
                            <li class=""><a href="{{url('/logout')}}"
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
                        @elseif(Request::is('confirmEmail') || Request::is('stripe') || Auth::user()->disabled == 1)
                            @if(Auth::user()->samy_bot == 1)
                                <li class=""><a href="{{url('samybot/campaigns')}}">{{trans('header.samy_bot')}}</a></li>
                            @else
                                <li class=""><a href="{{url('samybot/plans')}}">{{trans('header.samy_bot')}}</a></li>
                            @endif
                            @if(Auth::user()->samy_affiliate == 1)
                                <li class=""><a href="{{url('home')}}">{{trans('header.samy_affiliate')}}</a></li>
                            @else
                                <li class=""><a href="{{url('plans')}}">{{trans('header.samy_affiliate')}}</a></li>
                            @endif
                            {{--<li class=""><a href="#">{{trans('header.samy_linkedIn')}}</a></li>--}}
                            {{--<li class=""><a href="#">{{trans('header.samy_MyApp')}}</a></li>--}}
                            <li class=""><a href="{{url('myProfile')}}">{{trans('header.samy_MyAccount')}}</a></li>
                            <li class="dropdown <?php if (Request::is('emailcontents') || Request::is('paypalCredentials')) { echo " active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">{{trans('home.setting')}}<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">{{trans('home.smtp_email')}}</a></li>
                                    <li><a href="{{url('mailchimp/index')}}">MailChimp Settings</a></li>
                                </ul>
                            </li>
                            <li class=""><a href="#"
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
                            @if(Auth::user()->samy_bot == 1)
                                <li class=""><a href="{{url('samybot/campaigns')}}">{{trans('header.samy_bot')}}</a></li>
                            @else
                                <li class=""><a href="{{url('samybot/plans')}}">{{trans('header.samy_bot')}}</a></li>
                            @endif
                            @if(Auth::user()->samy_affiliate == 1)
                                <li class=""><a href="{{url('home')}}">{{trans('header.samy_affiliate')}}</a></li>
                            @else
                                <li class=""><a href="{{url('plans')}}">{{trans('header.samy_affiliate')}}</a></li>
                            @endif
                            {{--<li class=""><a href="#">{{trans('header.samy_linkedIn')}}</a></li>--}}
                            {{--<li class=""><a href="#">{{trans('header.samy_MyApp')}}</a></li>--}}
                            <li class="<?php if (Request::is('myProfile')) {
                                echo "active";
                            } ?>"><a href="{{url('myProfile')}}">{{trans('header.samy_MyAccount')}}</a></li>
                            <li class="dropdown <?php if (Request::is('emailcontents') || Request::is('paypalCredentials')) { echo " active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">{{trans('home.setting')}}<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('emailcontents.index')}}">{{trans('home.smtp_email')}}</a></li>
                                    <li><a href="{{url('mailchimp/index')}}">MailChimp Settings</a></li>
                                </ul>
                            </li>
                            @if(Auth::user())
                                <li class=""><a href="{{url('/logout')}}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('header.logout')}}</a>
                                </li>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            @else
                                <li class=""><a href="{{url('login')}}">{{trans('header.login')}}</a></li>
                            @endif
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