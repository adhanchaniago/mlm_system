@include('frontEnd.affiliate.header')
<div class="container-fluid">
    <!--Section-1-->
    @include('frontEnd.affiliate.section')
    <!--Section-1 end-->

    <!--Section-2-->
    <div class="row">
        <!--SideBar-->
        @include('frontEnd.affiliate.sidebar')
        <!--SideBar end-->

        <div class="col-md-10 col-xs-12 col-sm-9">
            <div class="col-md-12 col-xs-12 col-sm-12 section2_numbers affiliate-container">
                <div class="col-lg-7 col-md-9 col-xs-12 col-sm-10">
                    <a class="affiliate-stats lifetime" onclick="overallStats('one')">
                        <div class="col-md-7 col-sm-8 col-xs-12 text-center"><h1>{{trans('home.current_month')}}</h1></div>
                    </a>
                    <a class="affiliate-stats" onclick="overallStats('two')">
                        <div class="col-md-4 col-sm-4 col-xs-12 text-center"><h1>{{trans('home.lifetime')}}</h1></div>
                    </a>
                </div>
            </div>
            <div class="col-md-12 section2_numbers" id="section2_numbers">
                <div class="col-md-10">
                    <div class="col-md-3 col-sm-6 col-xs-12 grid_system">
                        <p class="affiliate_Number">{{$affiliate_count}}</p>
                        <a href="{{url('stats')}}" class="affiliate-link-text"><h2>{{trans('home.affiliates')}}</h2></a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 grid_system">
                        <p class="affiliate_Number">{{$sales_count}}</p>
                        <a href="{{url('sales')}}" class="affiliate-link-text"><h2>{{trans('home.sales')}}</h2></a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12  grid_system">
                        <p class="affiliate_revenue">${{$revenue_total}}</p><h2>{{trans('home.revenue')}}</h2>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12 grid_system">
                        <p class="affiliate_revenue">${{$payout_total}}</p><h2>{{trans('home.payouts')}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Section-2 end-->
</div>
<script>
    $(function () {
        var $h3s = $('.affiliate-stats').click(function () {
            $h3s.removeClass('lifetime');
            $(this).addClass('lifetime');
        });
    });

    function overallStats(val) {
        $.ajax({
            url: "{{url('overallStats')}}" + "/" + val,
            success: function (result) {

                $('#section2_numbers').html(result);
            }
        });
    }
</script>
@include('frontEnd.mainFooter')

