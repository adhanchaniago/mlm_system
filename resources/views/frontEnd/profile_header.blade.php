<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    {{--<link rel="stylesheet" type="text/css" href="{{asset('public/css/affiliate_style.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{asset('public/css/style2.css')}}">--}}

    <!--navigation bar-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--navigation bar end-->
</head>
<body>
<!--resopnsive navigation bar-->
<div class="container-fluid header-container-fluid">
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
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::user()->samy_bot == 1)
                        <li class=""><a href="{{url('samybot/campaigns')}}">{{trans('header.samy_bot')}}</a></li>
                    @else
                        <li class=""><a href="{{url('samybot/plans')}}">{{trans('header.samy_bot')}}</a></li>
                    @endif
                    @if(Auth::user()->samy_affiliate ==1)
                        <li class=""><a href="{{url('home')}}">{{trans('header.samy_affiliate')}}</a></li>
                    @else
                        <li class=""><a href="{{url('plans')}}">{{trans('header.samy_affiliate')}}</a></li>
                    @endif
                    {{--<li><a href="#">{{trans('header.samy_linkedIn')}}</a></li>--}}
                    {{--<li><a href="#">{{trans('header.samy_MyApp')}}</a></li>--}}
                    <li><a class="active" href="#">{{trans('header.samy_MyAccount')}}</a></li>
                    <li class="dropdown <?php if (Request::is('emailcontents') || Request::is('paypalCredentials')) { echo " active"; } ?>"><a class="dropdown-toggle" data-toggle="dropdown" href="#">{{trans('home.setting')}}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('emailcontents.index')}}">{{trans('home.smtp_email')}}</a></li>
                            <li><a href="{{url('mailchimp/index')}}">MailChimp Settings</a></li>
                        </ul>
                    </li>
                    @if(Auth::user())
                        <li class="" ><a href="{{url('/logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{trans('header.logout')}}</a></li>
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
                </ul>
            </div>
        </div>
    </div>
</div>
<!--resopnsive navigation bar end-->