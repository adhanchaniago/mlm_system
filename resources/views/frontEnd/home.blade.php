<?php
if (Auth::user()->status == '1')
{
    $company_id = Auth::user()->typeid;
    $company = App\Models\company::whereId($company_id)->first();
    $company_name = $company->name;
}
elseif (Auth::user()->status == '2')
{
    $affliate = \App\Models\affiliate::whereId(Auth::user()->typeid)->first();
    $company = \App\Models\company::whereId($affliate->company_id)->first();
    $company_name = $company->name;
    $all_affiliates = \App\Models\affiliate::where('invitee',Auth::user()->id)->get();
}
?>
@include('frontEnd.header')
<style></style>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="col-md-4 col-sm-4">
                <div class="row">
                    <div class="col-md-3 col-sm-3 logo-center"></div>
                    <center>                        {{--Rank Display--}}
                        <div class="progressbar" data-animate="false">
                            <div class="circle" data-percent="10">
                                <div><img class="img img-responsive rank-img"
                                          src="{{asset('public/pictures/diamond.png')}}"></div>
                                <p>Diamond</p></div>
                        </div>
                    </center>
                </div> {{--comapony Details--}}
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="col-md-8 col-sm-8"><p class="home-texts"><b>&dollar;2,300</b></p>
                            <p class="home-text-no-color">{{trans('home.team_revenue')}}</p>
                            <p class="home-texts"><b>&dollar;360</b></p>
                            <p class="home-text-no-color">{{trans('home.payout')}}</p>
                            <p class="home-texts"><b>&dollar;750</b></p>
                            <p class="home-text-no-color">{{trans('home.payout_next')}} NEXT_LEVEL</p></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-2 col-sm-2"></div>
                            <div class="col-md-7 col-sm-7">
                                <center>
                                    @if(Auth::user()->status == '1')
                                        <h2>{{trans('home.welcome')}} <span class="company-name">{{$company_name}}</span></h2>
                                    @elseif(Auth::user()->status == '2')
                                        <h2>{{trans('home.welcome')}} <span class="company-name">{{$affliate->name}}</span></h2>
                                    @endif
                                </center>
                            </div>
                            <div class="col-md-3 col-sm-3"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2 home-affliates-div">
                                <div class="home-affliates">
                                    <center><p>95</p></center>
                                </div>
                                <div class="affliate-text">
                                    <p>{{trans('home.total_affiliates')}}</p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2 home-affliates-div">
                                <div class="home-affliates">
                                    <p>7</p>
                                </div>
                                <div class="affliate-text">
                                    <p>{{trans('home.new_affiliates')}}</p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2 home-affliates-div">
                                <div class="home-affliates">
                                    <p>19</p>
                                </div>
                                <div class="affliate-text">
                                    <p>{{trans('home.direct_affiliates')}}</p>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-2 home-affliates-div">
                                <div class="home-affliates"></div>
                                <div class="affliate-text"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/> <br/> <br/>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6"><h3
                                        class="affliate-title">{{trans('home.your_affiliates')}}</h3></div>
                            <div class="col-md-3 col-sm-3 col-xs-3"><h3
                                        class="affliate-title">{{trans('home.all_affiliates')}}</h3></div>
                            <div class="col-md-3 col-sm-3 col-xs-3"><h3
                                        class="affliate-title">{{trans('home.affiliates_revenue')}}</h3></div>
                        </div>
                    </div>
                </div>
                @foreach($all_affiliates as $all_affiliate)
                    <div class="row affliate-row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        @if($all_affiliate->photo != '' || !empty($all_affiliate->photo))
                                            <img src="{{asset('public/avatars').'/'.$all_affiliate->photo}}" class=" affliate-image">
                                        @else
                                            <img src="{{asset('public/pictures/man.jpg')}}" class=" affliate-image">
                                        @endif
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-12 affliate-details">
                                        <h4>{{$all_affiliate->name}}</h4>
                                        <a class="affliate-link" href="mailto: {{$all_affiliate->email}}" target="_blank"><h6>{{$all_affiliate->email}}</h6></a> <a class="affliate-link" href="tel: {{$all_affiliate->phone}}"><h6>{{$all_affiliate->phone}}</h6></a></div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <h3 class="affliate-title">
                                        21
                                    </h3>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <h3 class="affliate-title">
                                        &dollar;230
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="home-row"></div>
</div>@include('frontEnd.footer')