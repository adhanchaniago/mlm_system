<?php
    $plan = \App\Models\plantable::whereId($array['planid'])->first();
?>
@if($array['purpose'] == 'warn')
    <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
    <center>
        <div style="padding-top: 5%;padding-bottom: 5%;">
            <h2>Hello {{$array['name']}}</h2><br/>
            <h3>Your {{$plan->type}} Plan with Amount ${{$plan->amount}} has been expired.<br> Please Renew by paying the certain amount in order avoide the Account Supension</h3><br/>
            <h5>Thnak You</h5>
            <h5>Team Samy Affiliate</h5>
        </div>
    </center>
    </body>
@else
    <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
    <center>
        <div style="padding-top: 5%;padding-bottom: 5%;">
            <h2>Hello {{$array['name']}}</h2><br/>
            <h3>We successfully auto-renewed your current {{$plan->type}} Plan.<br> Enjoy all Samy affiliate Benifits like before!</h3><br/>
            <h5>Thnak You</h5>
            <h5>Team Samy Affiliate</h5>
        </div>
    </center>
    </body>
@endif
