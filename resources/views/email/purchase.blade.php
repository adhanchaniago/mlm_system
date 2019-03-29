@if($array['type'] == 1)
    <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
    <center>
        <div style="padding-top: 5%;padding-bottom: 5%;">
            <h3>{{trans('mail.hello')}} User</h3>
            <p>Please click the Below Link to Purchase With extra-ordinary Offers</p>
            <a style="color: black" href="{{$array['link']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">Purchase</button></a>
        </div>
    </center>
    </body>
@else
    <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
    <center>
        <div style="padding-top: 5%;padding-bottom: 5%;">
            <h3>{{trans('mail.hello')}} User</h3>
            <p>Please click the Below Link to Purchase SamyBot from Our site</p>
            <a style="color: black" href="{{$array['link']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">Purchase</button></a>
        </div>
    </center>
    </body>
@endif