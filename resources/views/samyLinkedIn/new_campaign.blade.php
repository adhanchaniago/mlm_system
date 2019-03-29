@include('samyLinkedIn.linkedIn_nav')
<style>
    .LinkedIn textarea {
        border:1px solid darkgrey;
    }

    .LinkedIn select {
        border: 1px solid darkgrey;
    }


</style>
<div class="container samy_campians">
    <br>
    @include('flash::message')
    <div class="row">
        <form method="post" action="{{url('samylinkedIn/create_campaign')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1 LinkedIn">
                <div class="col-md-12">
                    <div class="col-md-7 samy_zero_padding">
                        <div class="col-md-5 samy_zero_padding">
                            <img src="{{asset('public/500.png')}}" class="linkedIn_img">
                        </div>
                        <div class="col-md-7">
                            <span class="linkedIn_header">Select Account</span><br>
                            <span class="linkedIn_text">MikeBeck</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <span class="linkedIn_header">Synchronise with Mailchimp</span>
                        <select class="form-control"></select>
                        <div class="col-md-12 samy_zero_padding ExportRow" style="top: 100px;position: absolute">
                            <br><br>
                            <div class="col-md-6 samy_zero_padding">
                                <span class="pull-left linkedIn_header">0 contacts</span>
                            </div>
                            <div class="col-md-6 samy_zero_padding">
                                <button type="button" class="samy_btn">Export List</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-7 samy_zero_padding">
                        <label class="linkedIn_header">Keywords</label>
                        <textarea rows="5" class="form-control"></textarea>
                    </div>
                    <div class="col-md-5">
                        <label class="linkedIn_header">Audience</label><br>
                        <span class="samy_audience_number">2,724</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="linkedIn_header">Invitation Message</label>
                    <textarea rows="5" class="form-control"></textarea>
                </div>
                <div class="col-md-12">
                    <label class="linkedIn_header">Thank You Message</label>
                    <textarea rows="5" class="form-control"></textarea>
                </div>
                <div class="col-md-12">
                    <h4><span class="linkedIn_header">1 day</span> after previous message</h4>
                </div>
                <div class="col-md-12 samy_center_align">
                    <div class="col-md-3 text-center">
                        <i class="samy_plus_icon fa fa-plus"></i>
                        <button class="samy_linkdeIn_button">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>