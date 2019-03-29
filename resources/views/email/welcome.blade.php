
    @if(isset($array['welcome_text']))
        <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
        <center>
            <div style="padding-top: 5%;padding-bottom: 5%;">
                <h3>{{trans('mail.hello')}} {{$array['name']}}</h3>
                <p>{{$array['welcome_text']}}</p><a style="color: black" href="{{url('confirm/email').'/'.$array['hash']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.verify_now')}}</button></a>
            </div>
        </center>
        </body>
    @else
        <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
        <center>
            <div style="padding-top: 5%;padding-bottom: 5%;">
                <h3>{{trans('mail.hello')}} {{$array['name']}}</h3>
                <p>{{trans('mail.please_verify')}}</p><a style="color: black" href="{{url('confirm/email').'/'.$array['hash']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.verify_now')}}</button></a>
            </div>
        </center>
        </body>
    @endif
