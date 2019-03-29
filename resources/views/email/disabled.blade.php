@if(isset($array))
    <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
        <center>
            <div style="padding-top: 5%;padding-bottom: 5%;">
                <h3>{{trans('mail.hello')}} {{$array['name']}}</h3>
                <p>{{trans('mail.mail_to_plan_expired')}}{{$array['planEnd']}} <br/> {{trans('mail.renew_plan')}} <br/></p>
                <a style="color: black" href="{{url('stripe')}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.renew_now')}}</button></a>
            </div>
        </center>
    </body>
@endif
@if(isset($data))
    <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
        <center>
            <div style="padding-top: 5%;padding-bottom: 5%;">
                <h3>{{trans('mail.hello')}} {{$data['name']}}</h3>
                <p>{{trans('mail.mail_to_commission_failed1')}}{{$data['total']}}{{trans('mail.mail_to_commission_failed2')}} <br/> {{trans('mail.contact_admin')}}</p>
            </div>
        </center>
    </body>
@endif