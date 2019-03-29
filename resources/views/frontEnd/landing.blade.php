@include('frontEnd.header')
<div class="container-fluid">
    <div class="row" id="home">
        <div class="col-md-12 col-xs-12 col-sm-12 section-1">
            <div class="container">
                <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-1 main_text_section">
                    <p class="main_heading">{{trans('landing.intro_1')}}</p>
                    <h3 class="main_texts">{{trans('landing.intro_2')}}</h3>
                </div>
            </div>
        </div>
    </div>
    <!--SECTION-1 END-->

    <!--SECTION-2-->
    <div class="row" id="section1">
        <div class="col-md-12 col-xs-12 col-sm-12 section-2">
            <div class="container">
                <h1 class="text-center">{{trans('header.samy_bot')}}</h1>
                <p class="text-center">{{trans('landing.local_revolution')}}</p>
                <div class="col-md-12 col-xs-12 col-sm-12 home-page-division">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <h3 class="heading_division">{{trans('landing.local_marketing')}}</h3>
                        <ol class="textUl">
                            <li>{{trans('landing.samybot_details_line_1')}}</li>
                            <br>
                            <li>{{trans('landing.samybot_details_line_2')}}</li>
                            <br>
                            <li>{{trans('landing.samybot_details_line_3')}}</li>
                            <br>
                            {{trans('landing.bot_start_price')}}
                        </ol>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5 col-xs-12 col-sm-5">
                        <iframe class="home_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>
                        <a href="{{url('samybot/plan')}}"><button class="video_btn">{{trans('landing.sign_up_now')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--SECTION-2 END-->

    <!--SECTION-3-->
    <div class="row" id="section2">
        <div class="col-md-12 section-3">
            <div class="container">

                <h1 class="text-center">{{trans('header.samy_affiliate')}}</h1>
                <p class="text-center">{{trans('landing.exponential')}}</p>
                <div class="col-md-12 home-page-division">
                    <div class="col-md-6">
                        <h3 class="heading_division">{{trans('landing.affiliate_subline_2')}}</h3>
                        <ol class="textUl">
                            <li>{{trans('landing.affiliate_line_1')}}</li>
                            <br>
                            <li>{{trans('landing.affiliate_line_2')}}</li>
                            <br>
                            <li>{{trans('landing.affiliate_line_3')}}</li>
                            <br>
                            {{trans('landing.affiliate_line_4')}}
                        </ol>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <iframe class="home_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>
                        <a href="{{url('plans')}}">
                            <button class="video_btn">{{trans('landing.sign_up_now')}}</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--SECTION-3 END-->

    <!--CONTACT SECTION-->
    <div class="row" id="section3">
        <div class="col-md-12 contact_section">
            <h1 class="text-center">{{trans('header.contact_us')}}</h1><br><br>
            <form method="post" action="{{url('contactUs')}}">
                {{csrf_field()}}
                <div class="pull-left col-md-3 col-md-offset-1">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control contact_input" id="usr" placeholder="{{trans('landing.name')}}">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control contact_input" id="email" placeholder="{{trans('landing.email')}}">
                    </div>
                </div>
                <div class="col-md-5 clearfix">
                    <div class="form-group">
                        <textarea name="msg" class="form-control contact_input" rows="7" id="comment" placeholder="{{trans('landing.msg')}}"></textarea>
                    </div>
                </div>
                <div class="pull-left col-md-3 col-md-offset-1">
                    <button type="submit">{{trans('landing.send')}}</button>
                </div>
            </form>
        </div>
    </div>
    <!--CONTACT SECTION END-->
</div>
<!--Footer section-->
@include('frontEnd.footer')
