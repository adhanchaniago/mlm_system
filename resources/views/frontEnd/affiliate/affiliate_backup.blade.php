@include('frontEnd.affiliate.header')
<style>
    .affiliate-invite {
        margin-top: 5%;
    }
</style>
<div class="container-fluid">

    <!--Section-1-->
@include('frontEnd.affiliate.section')
<!--Section-1 end-->

    <!--Section-2-->
    <div class="row">
        <!--SideBar-->
    @include('frontEnd.affiliate.sidebar')
    <!--SideBar end-->
        <div class="col-md-10 col-xs-12 col-sm-12 affiliate-invite">
            @include('flash::message')
            @include('adminlte-templates::common.errors')
        </div>
        @if($affiliates != "")
            <div class="col-md-10 col-xs-12 col-sm-10 section2_numbers">
                <div class="col-md-8 col-xs-12 col-sm-8 table-responsive">
                    <table class="col-md-12">
                        <tbody>
                        @foreach($affiliates as $affiliate)
							<?php
							$affiliate_user = \App\User::where('typeid', $affiliate->id)->first();
							if(!empty($affiliate_user)){
							$affiliate_count = \App\Models\affiliate::where('invitee', $affiliate_user->id)->count();
							$rankid = calculateRank($affiliate->id);
							if (\App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $rankid)->exists()) {
								$rank = \App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $rankid)->first();
							} else {
								$rank = "";
							}
							?>
                            <tr>
                                <td class="col-md-2">
                                    @if(isset($affiliate->photo))
                                        <img class="img img-circle image-affiliate"
                                             src="{{asset('public/avatars').'/'.$affiliate->photo}}">
                                    @else
                                        <img class="img img-circle image-affiliate"
                                             src="{{asset('public/pictures/default.jpg')}}">
                                    @endif
                                </td>
                                <td class="col-md-4">
                                    <h4>{{$affiliate->name}}</h4>
                                    <p>{{$affiliate->email}}</p>
                                    <p>{{$affiliate->phone}}</p>
                                </td>
                                <td class="col-md-2 text-center ">
                                    @if($rank!="")
                                        <h4 class="marketing_heading">{{strtoupper($rank->name)}}</h4>
                                    @else
                                        <h4 class="marketing_heading">-</h4>
                                    @endif
                                    <h4 class="marketing_heading">${{$affiliate->current_revenue}}</h4>
                                    <p class="marketing_heading">{{trans('home.revenue')}}</p>
                                </td>
                                <td class="col-md-2 text-center">
                                    @if($rank!="")
                                        <h4>{{trans('home.payouts')}}</h4>
                                        <h4>${{$rank->payout_amount}}</h4>
                                        <p>{{trans('home.payouts')}}</p>
                                    @else
                                        <h4>{{trans('home.payouts')}}</h4>
                                        <h4>-</h4>
                                        <p>{{trans('home.payouts')}}</p>
                                    @endif
                                </td>
                                <td class="col-md-2 text-center">
                                    <h4>{{trans('home.affiliates')}}</h4>
                                    <h4>{{$affiliate_count}}</h4>
                                </td>
                            </tr>
							<?php
							}
							?>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="col-md-10">
                <center>
                    <img src="{{asset('public/pictures/not found.svg')}}" class="section1_logo">
                    <h4><b>{{trans('home.no_data')}}</b></h4>
                </center>
            </div>
        @endif
    </div>
    <!--Section-2 end-->
	<?php
	function calculateRank($id)
	{
		$affiliate = \App\Models\affiliate::whereId($id)->first();
		$ranks = \App\Models\rank::where('company_id', $affiliate->company_id)->orderby('id')->get();
		foreach ($ranks as $rank) {
			$current_revenue = $affiliate->current_revenue;
			$current_rank = $rank->rank;
			$rank_next = $current_rank + 1;
			if (\App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $rank_next)->exists()) {
				$next_rank = \App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $rank_next)->first();
			} else {
				$next_rank = "";
			}
			if ($next_rank != "") {
				if ($current_revenue >= $rank->revenue_trigger && $current_revenue < $next_rank->revenue_trigger) {
					$affiliate_rank = $rank->rank;
					return $affiliate_rank;
				}
			} else {
				if ($current_revenue >= $rank->revenue_trigger) {
					$affiliate_rank = $rank->rank;
				} else {
					$affiliate_rank = 0;
				}
			}
		}
		return $affiliate_rank;
	}
	?>

</div>
<!--Footer section-->
@include('frontEnd.mainFooter')
