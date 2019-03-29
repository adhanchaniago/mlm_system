<?php
$id = Auth::user()->id;
?>
@if(Auth::user()->status == '1')
    @include('frontEnd.admin_header')
@else
    @include('frontEnd.affiliate.header')
@endif

<div class="container">
    <div class="row verification-row">

    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            @if ($message = Session::get('success'))
                <div class="custom-alerts alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    {!! $message !!}
                </div>
                <?php Session::forget('success');?>
            @endif
                @include('flash::message')
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-12 verify-email-content">
            <div class="col-md-6">
                <img src="{{asset('public/pictures/email.svg')}}" class="verify-email-image"><br>
                <p>{{trans('home.email_verification_sent')}}<b>{{Auth::user()->email}}</b>.<br>{{trans('home.please_verify')}}<br><b><a
                            href="{{url('resendMail').'/'.$id}}">{{trans('home.click_here')}}</a></b> {{trans('home.resend_mail')}} </p>

            </div>
        </div>
    </div>
    <div class="row verification-row"></div>
</div>
@include('frontEnd.footer')