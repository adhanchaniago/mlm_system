@include('frontEnd.main_div')
<div class="container-fluid affiliate-container">
    @include('flash::message')
    @include('adminlte-templates::common.errors')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-2 col-sm-2 col-xs-12 affiliatedivision">
                @if(($level <= $max_levels && $current_affiliates < $max_affiliates || $current_affiliates=='unlimited') || Auth::user()->special_user == 1)
                &ensp;<button type="button" class="btn btn-primaryy btn-new-affiliate" data-toggle="modal"
                              data-target="#addNewAff">{{trans('affiliate.invite_new')}}</button>
                @endif
                <div class="modal fade" id="addNewAff" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content add-level-content">
                            <div class="modal-header">
                                <center>
                                    <h4>{{trans('affiliate.invite_affiliate')}}</h4>
                                </center>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data"
                                      action="{{url('invite-link')}}">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-2"></div>
                                        <div class="form-group col-sm-8">
                                            <label>{{trans('auth.name')}} : </label>
                                            <input type="text" name="name" class="form-control"
                                                   placeholder="{{trans('auth.name')}}">
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2"></div>
                                        <div class="form-group col-sm-8">
                                            <label>{{trans('auth.email')}} : </label>
                                            <input type="email" name="email" class="form-control"
                                                   placeholder="{{trans('auth.email')}}">
                                            <input type="hidden" readonly name="invitee" value="{{Auth::user()->id}}">
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2"></div>
                                        <div class="form-group col-sm-8">
                                            <center>
                                                <button type="submit"
                                                        class="btn btn-primaryy">{{trans('affiliate.send_link')}}</button>
                                                <a data-dismiss="modal"
                                                   class="btn btn-default">{{trans('home.cancel')}}</a></center>
                                        </div>
                                        <div class="col-sm-2"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>

                    </div>
                </div>

                <input type="text" class="form-control" id="affiliate-search" placeholder="{{trans('home.search')}}" onkeyup="searchbarResult()">


                <div class="affiliate-revenue-sort"><br/>
                    <h6>&ensp;&ensp;{{trans('affiliate.sort_by')}}: </h6>
                    <div id="arrow-up">
                        <div class="col-md-4 col-sm-4"></div>
                        <button type="button" class="btn btn-default" onclick="descendingSort()"><i
                                    class="fa fa-angle-up"></i></button>&ensp;{{trans('home.revenue')}}
                    </div>
                    <div id="arrow-down">
                        <div class="col-md-4 col-sm-4"></div>
                        <button type="button" class="btn btn-default" onclick="ascendingSort()"><i
                                    class="fa fa-angle-down"></i></button>&ensp;{{trans('home.revenue')}}
                    </div>
                </div>

                <div class="affiliate-rank-filter"><br/>
                    <h6>&ensp;&ensp;{{trans('affiliate.filter')}}</h6>
                    <div class="filter-checkbox">
                        <div class="col-md-3 col-sm-2"></div>
                        <div class="col-md-9 col-sm-10">
                            @foreach($ranks as $rank)
                                <input type="checkbox" id="{{$rank->rank}}" value="{{$rank->name}}"
                                       class="filterCheckbox"
                                       onchange="checkboxStatus(this.id,'{{Auth::user()->company_id}}')">
                                &ensp;{{$rank->name}}
                                <br/>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-10 col-xs-12 col-sm-10 table-responsive no-border" id="affiliate-table-container">
                <table>
                    <tbody id="affiliate-details-container">
                    @if(isset($affiliates))
                        @foreach($affiliates as $affiliate)
                            <?php
                            $affUser = \App\User::where('affiliate_id',$affiliate->id)->first();
                            if($affUser->status == '4')
                            {
                                if(\Illuminate\Support\Facades\DB::table('bot_plans')->where('company_id',$affUser->company_id)->where('payment_status',1)->exists() == 0)
                                {
                                    continue;
                                }
                            }
                            if ($affiliate->current_revenue == '')
                            {
                                $affiliate->current_revenue = 0;
                            }
                            $joined = strtotime($affiliate->created_at);
                            $rankid = calculateRank($affiliate->id);
                            if (\App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $rankid)->exists())
                            {
                                $current_rank = \App\Models\rank::where('company_id', $affiliate->company_id)->where('rank', $rankid)->first();
                            }
                            else
                            {
                                $current_rank = "";
                            }
                            if (\Illuminate\Support\Facades\DB::table('purchase_history')->where('affiliate_id', $affiliate->id)->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->exists())
                            {
                                $sales = \Illuminate\Support\Facades\DB::table('purchase_history')->where('affiliate_id', $affiliate->id)->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->get();
                            }
                            else
                            {
                                $sales = "";
                            }

                            $current_user = \App\User::where('affiliate_id', $affiliate->id)->first();
                            $parent_user = \App\User::whereId($affiliate->invitee)->first();
                            if ($parent_user->status == '1') {
                                $parent = \App\Models\company::whereId($parent_user->company_id)->first();
                            }
                            else {
                                $parent = \App\Models\affiliate::whereId($parent_user->affiliate_id)->first();
                            }
                            if(!empty($current_user))
                            {
                                if (\App\Models\affiliate::where('invitee',$current_user->id)->exists())
                                {
                                    $childrens = \App\Models\affiliate::where('invitee', $current_user->id)->get();
                                }
                                else
                                {
                                    $childrens = "";
                                }
                            }
                            else
                            {
                                $childrens = "";
                            }

                            ?>

                            <tr>
                                <td class="col-md-1">
                                    @if(isset($affiliate->photo))
                                        <img class="img img-circle img-affiliate"
                                             src="{{asset('public/avatars').'/'.$affiliate->photo}}">
                                    @else
                                        <img class="img img-circle img-affiliate"
                                             src="{{asset('public/pictures/default.jpg')}}">
                                    @endif
                                </td>
                                <td class="col-md-4">
                                    <span class="affiliate-table-name">{{$affiliate->name}} </span> <br/>
                                    <span class="affiliate-other-details">{{$affiliate->email}}</span> <br/>
                                    <span class="affiliate-other-details">{{$affiliate->phone}}</span> <br/>
                                    <span class="affiliate-other-details">{{trans('affiliate.joined')}} {{date('m/d/Y',$joined)}}</span>
                                    <br/>
                                </td>
                                <td class="col-md-2">
                                    @if($current_rank != "")
                                        <h4 class="affiliate-table-h4"><b>{{strtoupper($current_rank->name)}}</b></h4>
                                    @else
                                        <h4 class="affiliate-table-h4"><b>-</b></h4>
                                    @endif
                                    <h4 class="affiliate-table-h4">
                                        <b>&dollar;{{number_format($affiliate->current_revenue)}}</b></h4>
                                    <h6 class="affiliate-table-h6"><b>{{trans('home.revenue')}}</b></h6>
                                </td>
                                <td class="col-md-2">
                                    <h4 class="affiliate-table-h4">{{trans('home.payouts')}}</h4>
                                    @if($current_rank != "")
                                        <h4 class="affiliate-table-h4">
                                            &dollar;{{number_format($current_rank->payout_amount)}}</h4>
                                    @else
                                        <h4 class="affiliate-table-h4">-</h4>
                                    @endif
                                    <h6 class="affiliate-table-h6">{{trans('home.payouts')}}</h6>

                                </td>
                                <td class="col-md-3">
                                    <button type="button" class="btn btn-primaryy affiliate-table-btn"
                                            data-toggle="modal"
                                            data-target="#treeModal{{$affiliate->id}}">{{trans('affiliate.show_tree')}}</button>
                                    <button type="button" class="btn btn-primaryy affiliate-table-btn"
                                            data-toggle="modal"
                                            data-target="#salesModal{{$affiliate->id}}">{{trans('affiliate.see_sales')}}</button>
                                    <div class="modal fade" id="salesModal{{$affiliate->id}}" role="dialog">
                                        <div class="modal-dialog add-level-modal">

                                            <div class="modal-content add-level-content sales-modal">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                    <center><b>
                                                            <h2 class="modal-title">{{trans('affiliate.direct_sales')}}</h2>
                                                        </b></center>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <center>
                                                            <a href="{{url('exportSales').'/'.$affiliate->id}}"
                                                               class="btn btn-primaryy">{{trans('home.export_csv')}}</a>
                                                            <a href="{{url('exportSalesPdf').'/'.$affiliate->id}}"
                                                               class="btn btn-primaryy">{{trans('home.export_pdf')}}</a>
                                                        </center>
                                                    </div>
                                                    @if($sales != "")
                                                        <div class="row">
                                                            @foreach($sales as $sale)
                                                                <div class="col-md-12 col-sm-12 col-xs-12 affiliate-sales">
                                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                                        <h5>
                                                                            <b>{{date('m/d/Y',strtotime($sale->created_at))}}</b>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                                        <h5><b>{{$sale->name}}</b></h5>
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                                                        <h5>
                                                                            <b>&dollar;{{number_format($sale->amount)}}</b>
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 col-xs-12 affiliate-sales">
                                                                <h4><b>{{trans('affiliate.no_sale')}}</b></h4>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal fade" id="treeModal{{$affiliate->id}}" role="dialog">
                                        <div class="modal-dialog">

                                            <div class="modal-content add-level-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <section class="management-hierarchy">
                                                        <div class="hv-container">
                                                            <div class="hv-wrapper">

                                                                <!-- Key component -->
                                                                <div class="hv-item">

                                                                    <div class="hv-item-parent">
                                                                        <div class="person">
                                                                            @if($parent_user->status == '1')
                                                                                @if(isset($parent->logo))
                                                                                    <img src="{{asset('public/avatars').'/'.$parent->logo}}"
                                                                                         alt="">
                                                                                @else
                                                                                    <img src="{{asset('public/pictures/default.jpg')}}"
                                                                                         alt="">
                                                                                @endif
                                                                            @else
                                                                                @if(isset($parent->photo))
                                                                                    <img src="{{asset('public/avatars').'/'.$parent->photo}}"
                                                                                         alt="">
                                                                                @else
                                                                                    <img src="{{asset('public/pictures/default.jpg')}}"
                                                                                         alt="">
                                                                                @endif
                                                                            @endif
                                                                            <p class="name">
                                                                                {{$parent->name}}
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="hv-item-children">
                                                                        <div class="hv-item-child">
                                                                            <!-- Key component -->
                                                                            <div class="hv-item">

                                                                                <div class="hv-item-parent">
                                                                                    <div class="person">
                                                                                        @if(isset($affiliate->photo))
                                                                                            <img src="{{asset('public/avatars').'/'.$affiliate->photo}}"
                                                                                                 alt="">
                                                                                        @else
                                                                                            <img src="{{asset('public/pictures/default.jpg')}}"
                                                                                                 alt="">
                                                                                        @endif
                                                                                        <p class="name">
                                                                                            {{$affiliate->name}}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="hv-item-children">
                                                                                    @if($childrens != "")
                                                                                        @foreach($childrens as $children)
                                                                                            @if($children != "" && $children != null)
                                                                                                <?php
                                                                                                $child_user = \App\User::where('affiliate_id', $children->id)->first();
                                                                                                $affUser = \App\User::where('affiliate_id',$affiliate->id)->first();
                                                                                                if($child_user->status == '4')
                                                                                                {
                                                                                                    if(\Illuminate\Support\Facades\DB::table('bot_plans')->where('company_id',$child_user->company_id)->where('payment_status',1)->exists() == 0)
                                                                                                    {
                                                                                                        continue;
                                                                                                    }
                                                                                                }
                                                                                                $child_count = \App\Models\affiliate::where('invitee', $child_user->id)->count();
                                                                                                ?>
                                                                                                <div class="hv-item-child">
                                                                                                    <div class="person">
                                                                                                        @if(isset($children->photo))
                                                                                                            <img src="{{asset('public/avatars').'/'.$children->photo}}"
                                                                                                                 alt="">
                                                                                                        @else
                                                                                                            <img src="{{asset('public/pictures/default.jpg')}}"
                                                                                                                 alt="">
                                                                                                        @endif
                                                                                                        <p class="name">
                                                                                                            {{$children->name}}
                                                                                                        </p>
                                                                                                        {{$child_count}} {{trans('home.affiliates')}}
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @else
                                                                                        <p class="name">
                                                                                            0 {{trans('home.affiliates')}}
                                                                                        </p>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td style='width: 75%'></td>
                            <td style='width: 40%' class='no-affiliate-data'><img src="{{asset('public/pictures/not found.svg')}}" class="section1_logo">
                                <h4>{{trans('affiliate.no_data')}}</h4></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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


@include('frontEnd.footer')
<script>
    function searchbarResult() {
        var val = $('#affiliate-search').val();
        var array = new Array();
        if (val == '') {
            val = 'nullempty';
        }
        if (document.getElementsByClassName('filterCheckbox').checked) {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    array.push(sThisVal);
                }
            });
        }
        else {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    array.push(sThisVal);
                }
            });
        }
        if (array == '') {
            array = 'nullempty';
        }
        $.ajax({
            url: "{{url('SearchByName')}}" + '/' + val + '/' + array,
            success: function (results) {
                if (results != '') {
                    $('#affiliate-details-container').html(results);
                }
                else {
                    var html = "<tr><td style='width: 70%'></td><td style='width: 75%' class='no-affiliate-data'> <img src='{{asset("public/pictures/not found.svg")}}'> <h4>{{trans('affiliate.no_data')}}</h4></td></tr>";
                    $('#affiliate-details-container').html(html);
                }
            }
        });
    }

    function descendingSort() {
        var searchVal = $('#affiliate-search').val();
        var array = new Array();
        if (searchVal == '') {
            searchVal = 'nullempty';
        }
        if (document.getElementsByClassName('filterCheckbox').checked) {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    array.push(sThisVal);
                }
            });
        }
        else {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    array.push(sThisVal);
                }
            });
        }
        if (array == '') {
            array = 'nullempty';
        }
        $('#arrow-up').hide();
        $('#arrow-down').show();
        $.ajax({
            url: "{{url('descendingSort')}}" + '/' + searchVal + '/' + array,
            success: function (results) {
                if (results != '') {
                    $('#affiliate-details-container').html(results);
                }
                else {
                    var html = "<tr><td style='width: 70%'></td><td style='width: 75%' class='no-affiliate-data'> <img src='{{asset("public/pictures/not found.svg")}}'> <h4>{{trans('affiliate.no_data')}}</h4></td></tr>";
                    $('#affiliate-details-container').html(html);
                }
            }
        });
    }

    function ascendingSort() {
        var searchVal = $('#affiliate-search').val();
        var array = new Array();
        if (searchVal == '') {
            searchVal = 'nullempty';
        }
        if (document.getElementsByClassName('filterCheckbox').checked) {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    array.push(sThisVal);
                }
            });
        }
        else {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    array.push(sThisVal);
                }
            });
        }
        if (array == '') {
            array = 'nullempty';
        }
        $('#arrow-down').hide();
        $('#arrow-up').show();
        $.ajax({
            url: "{{url('ascendingSort')}}" + '/' + searchVal + '/' + array,
            success: function (results) {
                if (results != '') {
                    $('#affiliate-details-container').html(results);
                }
                else {
                    var html = "<tr><td style='width: 70%'></td><td style='width: 75%' class='no-affiliate-data'> <img src='{{asset("public/pictures/not found.svg")}}'> <h4>{{trans('affiliate.no_data')}}</h4></td></tr>";
                    $('#affiliate-details-container').html(html);
                }
            }
        });
    }

    function checkboxStatus(val) {
        var array='';
        var searchVal = $('#affiliate-search').val();
        if (searchVal == '') {
            searchVal = 'nullempty';
        }
        if (document.getElementById('' + val + '').checked) {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked)
                {
                    if(array == '')
                    {
                        array = sThisVal;
                    }
                    else
                    {
                        array = array+','+sThisVal;
                    }
                }
            });
        }
        else {
            $('input:checkbox.filterCheckbox').each(function () {
                var sThisVal = (this.checked ? $(this).attr('id') : "");
                if (this.checked) {
                    if(array == '')
                    {
                        array = sThisVal;
                    }
                    else
                    {
                        array = array+','+sThisVal;
                    }
                }
            });
        }
        if (array == '') {
            array='nullempty';
        }
        console.log(array);
        $.ajax({
            url: "{{url('filterbyRank')}}" + '/' + array + '/' + searchVal,
            success: function (results) {
                if (results != '') {
                    $('#affiliate-details-container').html(results);
                }
                else {
                    var html = "<tr><td style='width: 50%'></td><td style='width: 50%' class='no-affiliate-data'><img src='{{asset("public/pictures/not found.svg")}}'> <h4>{{trans('affiliate.no_rank_data')}}</h4></td></tr>";
                    $('#affiliate-details-container').html(html);
                }
            }
        });
    }
</script>