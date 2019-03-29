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

        <div class="col-md-10 col-xs-12 col-sm-10 section1_logo">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="col-md-6 col-xs-12 col-sm-6">
                    <h3 class="marketing_heading">{{trans('home.explain_watchsquad')}}</h3>
                    <h3>{{trans('home.3mins_video')}}</h3>
                </div>
                <div class="col-md-6 col-xs-12 col-sm-6">
                    <iframe class="marketing_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12 marketing_section">
                <div class="col-md-6 col-xs-12 col-sm-6">
                    <h3 class="marketing_heading">{{trans('home.explain_watchsquad')}}</h3>
                    <h3>{{trans('home.3mins_video')}}</h3>
                </div>
                <div class="col-md-6 col-xs-12 col-sm-6">
                    <iframe class="marketing_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!--Section-2 end-->
</div>
<!--Footer section-->
@include('frontEnd.mainFooter')
