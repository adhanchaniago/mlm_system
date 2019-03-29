<html lang="en">
<head>
<!--    --><?php
    //    if(isset($metaKey) || isset($metaTitle) || isset($metaDescription)){
    //        echo '<meta name="title" content="'.$metaTitle.'">';
    //        echo '<meta name="keywords" content="'.$metaKey.'">';
    //        echo '<meta name="description" content="'.$metaDescription.'">';
    //    }
    //    ?>
    <title>MLM</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{asset('public/css/style2.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">

</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle navbar-toggle11 collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php
             if (isset($company_name))
             {
            ?>
            <a class="navbar-brand" href="#"><b>{{$company_name}}</b></a>
            <?php
             }
             else
             {
            ?>
            <a class="navbar-brand" href="#"><b>COMPANY NAME</b></a>
            <?php
            }
            ?>
        </div>
        @if(Auth::user())
            <div class="pull-right company-redirection">
                <a href="{{url('dashboard')}}" class="btn btn-primary">Dashboard</a>
                <a href="{!! url('/logout') !!}" class="btn btn-warning"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        @endif
    </div>
</nav>