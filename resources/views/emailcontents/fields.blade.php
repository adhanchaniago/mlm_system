@if($emailcontent != "")
    <center><h2>{{trans('emailcontent.email_contents')}}</h2></center> <br/><br/>
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.welcome')}}</label>
            <textarea name="welcome_text" class="form-control" placeholder="{{trans('emailcontent.welcome')}}">{{$emailcontent->welcome_text}}</textarea>
            <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
        </div>
    </div>

    <!-- New Password Text Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.new_password')}}</label>
            <textarea name="new_password_text" class="form-control" placeholder="{{trans('emailcontent.new_password')}}">{{$emailcontent->new_password_text}}</textarea>
        </div>
    </div>

    <!-- New Affiliate Text Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.new_affiliate')}}</label>
            <textarea name="new_affiliate_text" class="form-control" placeholder="{{trans('emailcontent.new_affiliate')}}">{{$emailcontent->new_affiliate_text}}</textarea>
        </div>
    </div>

    <!-- Delete Account Text Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.delete_account')}}</label>
            <textarea name="delete_account_text" class="form-control" placeholder="{{trans('emailcontent.delete_account')}}">{{$emailcontent->delete_account_text}}</textarea>
        </div>
    </div>

@else

    <center><h2>{{trans('emailcontent.email_contents')}}</h2></center> <br/><br/>
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.welcome')}}</label>
            <textarea name="welcome_text" class="form-control" placeholder="{{trans('emailcontent.welcome')}}"></textarea>
            <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
        </div>
    </div>

    <!-- New Password Text Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.new_password')}}</label>
            <textarea name="new_password_text" class="form-control" placeholder="{{trans('emailcontent.new_password')}}"></textarea>
        </div>
    </div>

    <!-- New Affiliate Text Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.new_affiliate')}}</label>
            <textarea name="new_affiliate_text" class="form-control" placeholder="{{trans('emailcontent.new_affiliate')}}"></textarea>
        </div>
    </div>

    <!-- Delete Account Text Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.delete_account')}}</label>
            <textarea name="delete_account_text" class="form-control" placeholder="{{trans('emailcontent.delete_account')}}"></textarea>
        </div>
        <br/><br/>
    </div>
@endif
<center><h2>{{trans('emailcontent.smtp_contents')}}</h2></center> <br/><br/>
@if($emailcontent != "")
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.smtp')}}: </label>
            <input type="text" name="smtp" id="smtp" class="form-control" value="{{$emailcontent->smtp}}" placeholder="{{trans('emailcontent.smtp')}}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.user_id')}}: </label>
            <input type="text" name="smtp_user_id" id="uid" class="form-control" value="{{$emailcontent->smtp_user_id}}" placeholder="{{trans('emailcontent.user_id')}}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.password')}}: </label>
            <input type="text" name="smtp_password" id="psw" class="form-control" value="{{$emailcontent->smtp_password}}" placeholder="{{trans('emailcontent.password')}}">
        </div>
    </div>
@else
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.smtp')}}: </label>
            <input type="text" name="smtp" id="smtp" class="form-control" placeholder="{{trans('emailcontent.smtp')}}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.user_id')}}: </label>
            <input type="text" name="smtp_user_id" id="uid" class="form-control" placeholder="{{trans('emailcontent.user_id')}}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <label>{{trans('emailcontent.password')}}: </label>
            <input type="text" name="smtp_password" id="psw" class="form-control" placeholder="{{trans('emailcontent.password')}}">
        </div>
    </div>
@endif
    <!-- Submit Field -->
    <div class="col-sm-12">
        <div class="col-sm-2"></div>
        <div class="form-group col-sm-7">
            <center>{!! Form::submit(trans('home.save'), ['class' => 'btn btn-save']) !!}</center>
        </div>
    </div>





