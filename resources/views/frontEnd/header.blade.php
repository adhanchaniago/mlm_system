<html>
<head>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <style>
        .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            width: 100%;
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
    {{--<link rel="stylesheet" href="{{asset('public/css/responsive.css')}}">--}}
    <link rel="stylesheet" href="{{asset('public/css/style_admin.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/responsive_admin.css')}}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>
<?php
$domain = request()->getHost();
?>
<!--resopnsive navigation bar-->

<div class="container-fluid header-container-fluid">
    <div class="row nav-row">
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="navbar-collapse collapse">

                    <ul class="nav navbar-nav navbar-right">
                        @if($domain == env('APP_DOMAIN'))
                            @if(Auth::user())
                                <li><a class="home-navigation"
                                       href="{{url('home')}}">{{trans('header.home')}}</a></li>
                                <li><a class="home-navigation"
                                       href="@if(Auth::user()->samy_bot == 1) {{url('samybot/campaigns')}} @else{{url('home#section1')}} @endif">{{trans('header.samy_bot')}}</a>
                                </li>
                                <li><a class="home-navigation"
                                       href="@if(Auth::user()->samy_affiliate == 1) {{url('home')}} @else {{url('home#section2')}} @endif">{{trans('header.samy_affiliate')}}</a></li>
                                <li><a class="home-navigation"
                                       href="{{url('home#section3')}}">{{trans('header.contact_us')}}</a></li>

                                    <li><a class="home-navigation" href="{{url('/logout')}}"
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
                                <li><a class="home-navigation"
                                       href="{{url('home')}}">{{trans('header.home')}}</a></li>
                                <li><a class="home-navigation"
                                       href="{{url('home#section1')}}">{{trans('header.samy_bot')}}</a>
                                </li>
                                <li><a class="home-navigation"
                                       href="{{url('home#section2')}}">{{trans('header.samy_affiliate')}}</a></li>
                                <li><a class="home-navigation"
                                       href="{{url('home#section3')}}">{{trans('header.contact_us')}}</a></li>

                                    <li><a class="home-navigation"
                                           href="@if(Request::is('login') || isset($login)) # @else {{url('login')}} @endif">{{trans('header.login')}}</a>
                                    </li>

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

                        @else
                            @if(Auth::user())
                                <li><a class="home-navigation" href="{{url('/logout')}}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('header.logout')}}</a>
                                </li>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            @else
                                <li><a class="home-navigation"
                                       href="@if(Request::is('login') || isset($login)) # @else {{url('login')}} @endif">{{trans('header.login')}}</a>
                                </li>
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
<script>
    function scrollNav() {
        $('.nav a').click(function () {
            //Toggle Class
            $(".active").removeClass("active");
            $(this).closest('li').addClass("active");
            var theClass = $(this).attr("class");
            //Animate
            $('html, body').stop().animate({
                scrollTop: $($(this).attr('href')).offset().top - 160
            }, 400);
            return false;
        });
        $('.scrollTop a').scrollTop();
    }

    scrollNav();
</script>
<!--resopnsive navigation bar end-->