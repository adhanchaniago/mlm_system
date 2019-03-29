@include('frontEnd.main_div')
<div class="container-fluid admin-head">
    <div class="row">
        <div class="col-md-12 stats_section1">
            <div class="col-lg-6 col-md-12 col-xs-12 col-sm-12">
                <center>
                    <a class="type-none-admin-home active" onclick="overallStats('two')">
                        <div class="col-md-6 col-xs-6 col-sm-6 text-right"><p>{{trans('home.current_month')}}</p></div>
                    </a>
                    <a class="type-none-admin-home" onclick="overallStats('three')">
                        <div class="col-md-5 col-xs-6 col-sm-4 text-left"><p>{{trans('home.lifetime')}}</p></div>
                    </a>
                </center>
            </div>
        </div>
    </div>
    <div class="container admin-head" id="companyStats">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="col-md-3 col-xs-12 col-sm-6">
                    <p class="stats_section2">{{$total_affiliates}}</p>
                    <a class="section2_headings stats_section_headings" href="{{url('affiliates')}}">
                        <p>{{trans('home.affiliates')}}</p></a>
                </div>
                <div class="col-md-3 col-xs-12  col-sm-6">
                    <p class="stats_section2">{{$sales_count}}</p>
                    <p class="section2_headings">{{trans('home.sales')}}</p>
                </div>
                <div class="col-md-3 col-xs-12  col-sm-6">
                    <p class="section2_payment">${{$total_revenue}}</p>
                    <p class="section2_headings">{{trans('home.revenue')}}</p>
                </div>
                <div class="col-md-3 col-xs-12  col-sm-6">
                    <p class="section2_payment">${{$total_payout}}</p>
                    <a class="section2_headings stats_section_headings" href="{{url('payouthistories')}}">
                        <p>{{trans('home.payouts')}}</p></a>
                </div>
            </div>
        </div>
    </div>
    <div class="container admin-head">
        <div class="row stats_section1">
            <div class="col-md-4 stats_section1 videodivision">
                <iframe class="home_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>
            </div>
        </div>
    </div>
</div>
@include('frontEnd.footer')
<script>
    $(function () {
        var $h3s = $('.type-none-admin-home').click(function () {
            $h3s.removeClass('active');
            $(this).addClass('active');
        });
    });

    function overallStats(val) {
        $.ajax({
            url: "{{url('todayStats')}}" + "/" + val,
            success: function (result) {

                $('#companyStats').html(result);
            }
        });
    }
</script>