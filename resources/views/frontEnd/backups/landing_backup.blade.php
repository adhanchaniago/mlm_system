<?php
use App\Models\sliderImages;
use App\Models\frontPage;
use App\Models\plantable;

$frontPage=frontPage::first();
$slider=sliderImages::get();
?>
@include('frontEnd.mainHeader')

{{--**************** Header Ends *****************************--}}

<div class="container-fluid">

    <div class="row">

        <div id="bootstrap-touch-slider" class="carousel bs-slider slide  control-round indicators-line" data-ride="carousel" data-pause="hover" data-interval="5000" >

            <!-- Indicators -->

        {{--<ol class="carousel-indicators">--}}

        {{--<li data-target="#bootstrap-touch-slider" data-slide-to="0" class="active"></li>--}}

        {{--<li data-target="#bootstrap-touch-slider" data-slide-to="1"></li>--}}

        {{--<li data-target="#bootstrap-touch-slider" data-slide-to="2"></li>--}}

        {{--</ol>--}}

        <!-- Wrapper For Slides -->

            <div class="carousel-inner" role="listbox">

                <!-- Third Slide -->
                <?php
                $x=1;
                foreach ($slider as $slides){
                ?>
                @if($x%2 == 0)
                    <div class="item active">

                        <!-- Slide Background -->

                        <img src="{{asset('public/avatars/'.$slides->image)}}" class="slide-image"/>

                        <div class="bs-slider-overlay"></div>

                        <div class="container">

                            <div class="row">

                                <!-- Slide Text Layer -->

                                <div class="slide-text slide_style_left">

                                    <h1 data-animation="animated bounceInLeft">{{$slides->heading}}</h1>

                                    <p data-animation="animated fadeInLeft">{{$slides->text}}</p>

                                    {{--<a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated bounceInLeft">Lorem</a>--}}

                                    {{--<a href="http://bootstrapthemes.co/" target="_blank"  class="btn btn-primary" data-animation="animated bounceInRight">Lorem</a>--}}

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- End of Slide -->

                    <!-- Second Slide -->
                @elseif($x%3 == 0)

                    <div class="item">



                        <!-- Slide Background -->

                        <img src="{{asset('public/avatars/'.$slides->image)}}" class="slide-image"/>

                        <div class="bs-slider-overlay"></div>

                        <!-- Slide Text Layer -->

                        <div class="slide-text slide_style_center">

                            <h1 data-animation="animated rubberBand">{{$slides->heading}}</h1>

                            <p data-animation="animated lightSpeedIn">{{$slides->text}}</p>

                            {{--<a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated bounceInUp">Lorem</a>--}}

                            {{--<a href="http://bootstrapthemes.co/" target="_blank"  class="btn btn-primary" data-animation="animated bounceInDown">Lorem</a>--}}

                        </div>

                    </div>

                    <!-- End of Slide -->
            @else
                <!-- Third Slide -->

                    <div class="item">

                        <!-- Slide Background -->

                        <img src="{{asset('public/avatars/'.$slides->image)}}" class="slide-image"/>

                        <div class="bs-slider-overlay"></div>

                        <!-- Slide Text Layer -->

                        <div class="slide-text slide_style_right">

                            <h1 data-animation="animated zoomInLeft">{{$slides->heading}}</h1>

                            <p data-animation="animated fadeInRight">{{$slides->text}}</p>

                            {{--<a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-default" data-animation="animated fadeInLeft">Lorem</a>--}}

                            {{--<a href="http://bootstrapthemes.co/" target="_blank" class="btn btn-primary" data-animation="animated fadeInRight">Lorem</a>--}}

                        </div>

                    </div>
            @endif
            <?php
            $x++;
            }
            ?>

            <!-- End of Slide -->

            </div>

            <!-- End of Wrapper For Slides -->

            <!-- Left Control -->

            <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev">

                <span class="fa fa-angle-left" aria-hidden="true"></span>

                <span class="sr-only">Previous</span>

            </a>

            <!-- Right Control -->

            <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next">

                <span class="fa fa-angle-right" aria-hidden="true"></span>

                <span class="sr-only">Next</span>

            </a>

        </div>

        <!-- End  bootstrap-touch-slider Slider -->

    </div>

    <div class="col-md-12" id="about">

        <center><p class="landingHeading">About Us</p></center>

        <div class="col-md-6 aboutTxt">
            <?php
            echo $frontPage->aboutUs_main_description;
            ?>
        </div>

        <div class="col-md-6">

            <img src="{{asset('public/avatars/'.$frontPage->aboutUs_image)}}" class="aboutImg">

        </div>

        <div class="col-md-12 aboutTxt">

            <br/>

            <?php
            echo $frontPage->aboutUs_sub_description;
            ?>
        </div>

    </div>

    <div class="col-md-12" id="plans">

        <center><p class="landingHeading">Plans</p></center>

        <br/>

        <br/>

        <div class="carousel slide" data-ride="carousel" data-type="multi" data-interval="9000" id="myCarousel">
            <div class="carousel-inner">
                <?php
                $plans=plantable::get();
                $i=0;
                foreach ($plans as $plan){
                if ($i == 0){
                ?>
                <div class="item active">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="pricingTable">

                            <h3 class="title">{{$plan->name}}</h3>

                            <div class="price-value">$ {{ $plan->amount }} / {{$plan->term}}</div>

                            <ul class="pricing-content">
                                <img src="{{asset('public/avatars/'.$plan->image)}}" class="planImage">

                            </ul>

                            <a href="{{url('register').'/'.$plan->id}}" class="pricingTable-signup">Sign Up</a>

                        </div>
                    </div>
                </div>
                <?php
                }
                else{
                ?>
                <div class="item">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="pricingTable">

                            <h3 class="title">{{$plan->name}}</h3>

                            <div class="price-value">$ {{ $plan->amount }} / {{$plan->term}}</div>

                            <ul class="pricing-content">
                                <img src="{{asset('public/avatars/'.$plan->image)}}" class="planImage">

                            </ul>

                            <a href="{{url('register').'/'.$plan->id}}" class="pricingTable-signup">Sign Up</a>

                        </div>
                    </div>
                </div>
                <?php
                }
                $i++;
                }
                ?>
            </div>
            <a class="left carousel-control" href="#myCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
        </div>

    </div>

    <div class="row" >

        <center><p class="landingHeading">Contact Us</p></center>

        <br/>

        <div class="col-md-12 contactFormDiv" id="contactUs">

            <br/>

            <br/>

            <br/>

            <div class="container-fluid ">

                <div class="col-md-2"></div>

                <div class="col-md-8 ContactBg">

                <span class="mailImg">

                    <img src="{{asset('public/pictures/mailSymbol.png')}}" class="submitMailImg">

                </span>

                    <form method="post" action="{{url('contactUs')}}">
                        {{csrf_field()}}
                        @if(\Illuminate\Support\Facades\Session::has('message_sent'))
                            <p class="alert alert-danger">{{Session::get('message_sent')}}</p>
                        @endif
                        <center>

                            <h2 class="contactMsg">

                                <br/>

                                Drop Us A Message

                            </h2>

                        </center>
                        <div class="col-md-6 submitDiv">

                            <input type="text" name="name" class="form-control submitForm" placeholder="Name">

                        </div>

                        <div class="col-md-6 submitDiv">

                            <input type="email" class="form-control submitForm" placeholder="Email Address">

                        </div>

                        <div class="col-md-12 submitDiv">

                            <textarea rows="4" name="msg" class="form-control " placeholder="Write us a message"></textarea>

                        </div>

                        <div class="col-md-12 submitDiv">

                            <div class="col-md-5 "></div>

                            <div class="col-md-4 ">

                                <button type="submit" class="btn ContactSubmitBtn">Submit</button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <br/>

        </div>

        <br/>



    </div>

</div>
@include('frontEnd.mainFooter')

<script>
    $(document).ready(function(){
        $('.carousel[data-type="multi"] .item').each(function(){
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

            for (var i=0;i<4;i++) {
                next=next.next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }

                next.children(':first-child').clone().appendTo($(this));
            }
        });
    });
</script>
<script src="{{asset('public/js/animate.js')}}"></script>