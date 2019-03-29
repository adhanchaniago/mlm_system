@include('frontEnd.admin_header')
<div class="container">
    <div class="row">
        {!! Form::open(['url' => 'mailchimp/store']) !!}
        <center><h2>MailChimp Settings</h2></center> <br/><br/>
        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>LIST NAME</label>
                <input type="text" name="list_name" class="form-control" placeholder="LIST NAME">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>FROM NAME</label>
                <input type="text" name="from_name" class="form-control" placeholder="FROM NAME">
                <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
            </div>
        </div>

        <!-- New Password Text Field -->
        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>FROM EMAIL</label>
                <input type="text" name="from_email" class="form-control" placeholder="FROM EMAIL">
            </div>
        </div>

        <!-- New Affiliate Text Field -->
        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>SUBJECT</label>
                <input type="text" name="subject" class="form-control" placeholder="SUBJECT">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>NOTIFY ON SUBSCRIBE</label>
                <input type="email" name="notify_on_subscribe" class="form-control" placeholder="ENTER EMAIL TO NOTIFY ON SUBSCRIBE">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>NOTIFY ON UNSUBSCRIBE</label>
                <input type="email" name="notify_on_unsubscribe"  class="form-control" placeholder="ENTER EMAIL TO NOTIFY ON UNSUBSCRIBE">
            </div>
        </div>
        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>VISIBILITY</label>
                <select name="visibility" class="form-control">
                    <option value="pub">Public</option>
                    <option value="pri">Private</option>
                </select>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>PERMISSION REMINDER</label>
                <input type="radio" name="permission_reminder" value="yes"> Yes
                <input type="radio" name="permission_reminder" value="no"> No
            </div>
        </div>

        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <label>EMAIL TYPE OPTION</label>
                <input type="radio" name="email_type_option" value="1"> Yes
                <input type="radio" name="email_type_option" value="0"> No
            </div>
        </div>
        <!-- Submit Field -->
        <div class="col-sm-12">
            <div class="col-sm-2"></div>
            <div class="form-group col-sm-7">
                <center>{!! Form::submit(trans('home.save'), ['class' => 'btn btn-save']) !!}</center>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@include('frontEnd.footer')

