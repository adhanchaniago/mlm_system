@include('frontEnd.main_div')
<style>
    .breakdowntable {
        height: 210px;
    }
</style>
<div class="container Payouts">
    <div class="row">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="col-md-12 col-xs-12 col-sm-12">
            <div class="col-md-6 col-xs-12 col-sm-12">
                <div class="col-md-12">
                    <div class="col-lg-7 col-md-6 col-xs-12 col-sm-7">
                        <p>{{trans('payout.paypal_branintree')}}</p>
                    </div>
                    <div class="col-lg-4 col-md-5 col-xs-12 col-sm-4">
                        <button type="button" data-toggle="modal" data-target="#editModal" data-direction='left' class="btn btn-primaryy btn-edit-payment">{{trans('payout.edit')}}</button>
                        <!-- Modal -->
                        <div class="modal fade left in" id="editModal" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content editmodel">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body editmodelbody">
                                        {{--<form method="post" action="{{url('savePayoutMethod')}}">--}}
                                        <form method="post" action="{{url('paypalCredentials')}}">
                                            {{csrf_field()}}
                                            <div class="checkbox">
                                                <label><input type="radio" name="payout" value="man" <?php if (isset($payout_type) && $payout_type->man == 1) { echo "checked";} ?>> {{trans('payout.manual_payout')}}</label>
                                                <p>{{trans('payout.manual_payout_description')}}</p>

                                            </div>
                                            <div class="checkbox">
                                                <label><input type="radio" name="payout" value="paypal" <?php if (isset($payout_type) && $payout_type->paypal == 1) { echo "checked";} ?>>
                                                    {{trans('payout.paypal_branintree')}}</label>
                                                <p>{{trans('payout.paypal_branintree_description')}}</p> <br/>
                                                <center><h2>{{trans('home.paypalCredentials')}}</h2></center> <br/>
                                                @if($paypal == "")

                                                            <label>{{trans('home.client_id')}}</label>
                                                            <textarea name="client_id" cols="50" rows="4" class="form-control form-group" placeholder="{{trans('home.client_id')}}"></textarea>


                                                            <label>{{trans('home.secrete_id')}}</label>
                                                            <textarea name="client_secrete" cols="50" rows="4" class="form-control form-group" placeholder="{{trans('home.secrete_id')}}"></textarea>
                                                            <input type="hidden"  name="company_id" value="{{$companyId}}">

                                                @else

                                                            <label>{{trans('home.client_id')}}</label>
                                                            <textarea name="client_id" cols="50" rows="4" class="form-control form-group" placeholder="{{trans('home.client_id')}}">{{$paypal->client_id}}</textarea>
                                                            <input type="hidden" name="company_id" value="{{$companyId}}">


                                                            <label>{{trans('home.secrete_id')}}</label>
                                                            <textarea name="client_secrete" cols="50" rows="4" class="form-control form-group" placeholder="{{trans('home.secrete_id')}}">{{$paypal->client_secrete}}</textarea>

                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="modelbtn">{{trans('myProfile.save')}}</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xs-12 col-sm-12 totalpayouts">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p>{{trans('payout.last_month_amount')}}:</p>
                    </div>
                    <div class="col-lg-7 col-md-6 col-xs-12 col-sm-7 mediapadding">
                        <p><span>${{$prev_total}}</span> {{trans('home.to')}} <span>{{$prev_count}} {{strtolower(trans('home.affiliates'))}}</span></p>
                    </div>
                    <div class="col-lg-4 col-md-5 col-xs-12 col-sm-4 mediapadding">
                        <button type="button" data-toggle="modal" data-target="#BreakdownModal" class="btn btn-primaryy btn-edit-payment">{{trans('payout.breakdown')}}</button>
                        <!-- Modal -->
                        <div class="modal fade" id="BreakdownModal" role="dialog">
                            <div class="modal-dialog modal-lg">

                                <div class="modal-content add-level-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <center><b><h2 class="modal-title">{{trans('payout.payout_breakdown')}}</h2></b></center>
                                    </div>
                                    <div class="modal-body payout-breakdown-body">
                                        <div class="row">
                                            <center>
                                                <a href="{{url('monthlyBreakdownCsv').'/'.$prev_month.'/'.$prev_year.'/'.$month_name}}"
                                                   class="btn btn-primaryy">{{trans('home.export_csv')}}</a>
                                                <a href="{{url('monthlyBreakdownPdf').'/'.$prev_month.'/'.$prev_year.'/'.$month_name}}"
                                                   class="btn btn-primaryy">{{trans('home.export_pdf')}}</a>
                                            </center>
                                        </div>
                                        <table class="table table-responsive affiliate-sales" width="100%">
                                            @if(count($prev_month_payouts) > 0)
                                            <thead>
                                                <tr>
                                                    <th width="25%">Affiliate Name</th>
                                                    <th width="25%">Affiliate Email</th>
                                                    <th width="25%">Affiliate Rank</th>
                                                    <th width="25%">Payout Amount</th>
                                                </tr>
                                            </thead>
                                            @else
                                                <center><h3>{{trans('payout.no_data')}}</h3></center>
                                            @endif
                                            @foreach($prev_month_payouts as $prev_month_payout)
                                                <?php

                                                $affiliate_details = \App\Models\affiliate::whereId($prev_month_payout->affiliate_id)->first();
                                                if (\App\Models\rank::where('company_id', $affiliate_details->company_id)->where('rank', $prev_month_payout->rankid)->exists()) {
                                                    $rank_details = \App\Models\rank::where('company_id', $affiliate_details->company_id)->where('rank', $prev_month_payout->rankid)->first();
                                                } else {
                                                    $rank_details = "";
                                                }

                                                ?>
                                                <tbody>
                                                <tr>
                                                    <td width="25%" class="affiliate_name">{{$affiliate_details->name}}</td>
                                                    <td width="25%" class="affiliate_email">{{$affiliate_details->email}}</td>
                                                    <td width="25%" class="affiliate_rank">
                                                        @if($rank_details != "")
                                                            {{$rank_details->name}}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td width="25%" class="affiliate_price">
                                                        &dollar;{{number_format($prev_month_payout->amount)}}</td>
                                                </tr>
                                                </tbody>
                                            @endforeach
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="col-lg-7 col-md-6 col-sm-7"></div>
                    <div class="col-lg-4 col-md-5 col-xs-12 col-sm-4">
                        <button class="payoutbtn" data-toggle="modal" data-target="#initiate_payout">{{trans('payout.initiate_payout')}}</button>
                    </div>
                    <div class="modal fade" id="initiate_payout" role="dialog">
                        <div class="modal-dialog">

                            <div class="modal-content add-level-content">
                                <div class="modal-header">
                                    <center><h2 class="modal-title">{{trans('payout.affiliates_payout')}}</h2></center>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <table width="100%" class="table-payout no-border">
                                        <tbody>
                                            @if($affiliates!="")
                                                @foreach($affiliates as $affiliate)
                                                <?php
                                                   $rank = \App\Models\rank::where('company_id',$companyId)->where('rank',$affiliate->rankid)->first();
                                                ?>
                                                <tr>
                                                    <td>{{$affiliate->first_name.' '.$affiliate->last_name}}</td>
                                                    <td>{{$affiliate->current_revenue}}</td>
                                                    <td>{{$rank->name}}</td>
                                                    <td>{{$rank->payout_amount}}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" width="30%"></td>
                                                    <td width="70%"><h4><b>{{trans('payout.no_data')}}</b></h4></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <center>
                                        @if (isset($payout_type) && $payout_type->man == 1)
                                            <a href="{{url('manualPayout')}}" class="btn proceedBtn">{{trans('payout.initiate_payout')}}</a>
                                        @else
                                            <a href="{{url('paypalPayout')}}" class="btn proceedBtn">{{trans('payout.initiate_payout')}}</a>
                                        @endif
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-5 col-xs-12 payout_history">
                <h4><b>{{trans('payout.payout_history')}}</b></h4>
                <table>
                    <tbody class="col-md-12 breakdowntable">
                    <?php
                    $i = $this_month - 1;
                    $year = $this_year;
                    if ($i == 0) {
                        $i = 12;
                        $year = $year - 1;
                    }
                    $i = $i + 1 - 1;
                    $count = 1;
                    ?>
                    @while ($count <= 12)

                        @if (strlen($i) == 1)
                            <?php $m = '0' . $i; ?>
                        @else
                            <?php $m = $i; ?>
                        @endif
                        <?php
                        $payouts = \App\Models\payouthistory::where('company_id', $companyId)->where('month', $m)->where('year', $year)->get();
                        $payouts_count = \App\Models\payouthistory::where('company_id', $companyId)->where('month', $m)->where('year', $year)->distinct()->count('affiliate_id');
                        $total = 0;
                        ?>
                        @foreach ($payouts as $payout)
                            <?php
                            $total += $payout->amount;

                            ?>

                        @endforeach
                        <?php $total = number_format($total); ?>
                        <tr>
                            <td class="col-md-4"><span>{{$months[$i]}} {{$year}}</span></td>
                            <td class="col-md-5"><span>${{$total}}</span> {{trans('home.to')}} <span>{{$payouts_count}} {{strtolower(trans('home.affiliates'))}}</span>
                            </td>
                            <td class="col-md-4">
                                <button class="btn btn-primaryy viewtablebtn" data-toggle="modal"
                                        data-target="#monthlyBreakdown{{$count}}">{{trans('payout.breakdown')}}
                                </button>
                                <div class="modal fade" id="monthlyBreakdown{{$count}}" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <div class="modal-content add-level-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <center><b><h2 class="modal-title">{{trans('payout.payout_breakdown')}}</h2></b></center>
                                            </div>
                                            <div class="modal-body payout-breakdown-body">
                                                <div class="row">
                                                    <center>
                                                        <a href="{{url('monthlyBreakdownCsv').'/'.$m.'/'.$year.'/'.$months[$i]}}"
                                                           class="btn btn-primaryy">{{trans('home.export_csv')}}</a>
                                                        <a class="btn btn-primaryy"
                                                           href="{{url('monthlyBreakdownPdf').'/'.$m.'/'.$year.'/'.$months[$i]}}">{{trans('home.export_pdf')}}</a>
                                                    </center>
                                                </div>
                                                @if(count($payouts) > 0)
                                                    <table class="table table-responsive affiliate-sales">
                                                        <thead>
                                                        <tr>
                                                            <th width="25%">Affiliate Name</th>
                                                            <th width="25%">Affiliate Email</th>
                                                            <th width="25%">Affiliate Rank</th>
                                                            <th width="25%">Payout Amount</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($payouts as $payout)
                                                            <?php

                                                            $affiliate_details = \App\Models\affiliate::whereId($payout->affiliate_id)->first();
                                                            if (\App\Models\rank::where('company_id', $affiliate_details->company_id)->where('rank', $payout->rankid)->exists()) {
                                                                $rank_details = \App\Models\rank::where('company_id', $affiliate_details->company_id)->where('rank', $payout->rankid)->first();
                                                            } else {
                                                                $rank_details = "";
                                                            }

                                                            ?>
                                                            <tr>
                                                                <td width="25%">{{$affiliate_details->name}}</td>
                                                                <td width="25%">{{$affiliate_details->email}}</td>
                                                                <td width="25%">
                                                                    @if($rank_details != "")
                                                                        {{$rank_details->name}}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td width="25%">&dollar;{{number_format($payout->amount)}}</td>
                                                            </tr>

                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <center><h3>{{trans('payout.no_data')}}</h3></center>
                                                @endif

                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>


                        @if ($i == 1)
                            <?php
                            $i = 12;
                            $count++;
                            $year--;
                            ?>
                        @else
                            <?php
                            $i--;
                            $count++;
                            ?>
                        @endif
                    @endwhile

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontEnd.footer')
