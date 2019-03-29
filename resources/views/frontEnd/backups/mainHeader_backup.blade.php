<html lang="en">
<head><title>Samy Affiliate | The best affiliate marketing tool</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          media="all">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/style2.css')}}">
</head>
<body>
<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
    <div class="container-fluid">
        <div class="navbar-header col-md-2 col-sm-2 removrPadding">
            <button class="navbar-toggle navbarToggle" type="button" data-toggle="collapse"
                    data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{url('home')}}" class="navbar-brand">SAMY Affiliates</a>
        </div>
        <div class="col-md-offset-1 col-md-8 col-sm-offset-1 col-sm-8 removrPadding">
            <nav class="col-md-12 collapse navbar-collapse bs-navbar-collapse" role="navigation">
                <ul class="nav navbar-nav navbar-left">
                    <li class="col-md-3 col-sm-3 HeaderLi"><a href="#" class="HeaderTxt">Home</a>
                    </li>
                    <li class="col-md-3 col-sm-3 HeaderLi"><a href="#about" class="HeaderTxt">About Us</a></li>
                    <li class="col-md-3 col-sm-3 HeaderLi"><a href="#plans" class="HeaderTxt">Plans</a></li>
                    <li class="col-md-3 col-sm-3 HeaderLi"><a href="#contactUs" class="HeaderTxt">Contact</a></li>
                </ul>
            </nav>
        </div> @if(Auth::user())
            <div class="col-md-1 col-sm-1 removrPadding pull-right">
                <a href="{!! url('/logout') !!}" class="btn LoginBtn pull-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        @else
            <div class="col-md-1 col-sm-1 pull-right">
                <a class="btn LoginBtn pull-right" href="{{url('login')}}">Login</a>
            </div>
        @endif
    </div>
</header>