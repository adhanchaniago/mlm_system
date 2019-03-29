<?php
    $id = Auth::user()->id;
?>
@include('frontEnd.header')
    <div class="container">
        <div class="row verification-row">

        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-7">
                @if(Session::has('success'))
                    <p class="alert alert-success">{{ Session::get('success') }}</p>
                    <?php
                    //                    Session::forget('success');
                    ?>
                @endif
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-12">
                <div class="col-md-3"></div>
                <div class="col-md-7 email-verification">
                       <p>Email Verification link has been sent to <b>{{Auth::user()->email}}</b>.<br> Please Verify You email by Clicking the link given in the Mail <br> Didn't Receive an Email? <a href="{{url('resendMail').'/'.$id}}">Click Here</a></p>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
        <div class="row verification-row"></div>
    </div>
@include('frontEnd.footer')