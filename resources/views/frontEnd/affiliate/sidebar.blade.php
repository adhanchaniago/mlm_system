<?php
use Illuminate\Support\Facades\DB;
    if(DB::table('purchase_history')->where('affiliate_id',Auth::user()->affiliate_id)->exists())
    {
        $Affiliaterevenues = DB::table('purchase_history')->where('affiliate_id',Auth::user()->affiliate_id)->get();
        $totalAffiliateRevenue = 0;
        foreach ($Affiliaterevenues as $Affiliaterevenue)
        {
            $totalAffiliateRevenue += (float)$Affiliaterevenue->amount;
        }
    }
    else
    {
        $totalAffiliateRevenue = 0;
    }
    if ($affiliate->current_revenue == '' || empty($affiliate->current_revenue))
    {
        $affiliate->current_revenue = 0;
    }

?>
<div class="col-md-2 col-sm-3 col-xs-12 affiliate_sidebar">
    @if($rank != "")
        <div class="col-md-12">
            @if(isset($rank->image))
                <img src="{{asset('public/avatars').'/'.$rank->image}}">
            @else
                <img src="{{asset('public/pictures/rookie logo.jpg')}}">
            @endif
            <h4>{{$rank->name}}</h4>
        </div>
        <div class="col-md-12">
            <h4>{{trans('home.current_revenue')}}<br><span>${{number_format($affiliate->current_revenue)}}</span></h4>
            <h4>{{trans('home.next_payout')}}<br><span>${{number_format($rank->payout_amount)}}</span></h4>
        </div>
    @else
        <div class="col-md-12">
                <img src="{{asset('public/pictures/rookie logo.jpg')}}">

            <h4>-</h4>
        </div>
        <div class="col-md-12">
            <h4>{{trans('home.current_revenue')}}<br><span>${{number_format($affiliate->current_revenue)}}</span></h4>
            <h4>{{trans('home.next_payout')}}<br><span>-</span></h4>
        </div>
    @endif
</div>

