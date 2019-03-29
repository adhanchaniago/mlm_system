@include('frontEnd.admin_header')
<div class="container">
    <div class="row">
        @include('adminlte-templates::common.errors')
        <form method="post" action="{{url('paypalCredentials')}}">
            {{csrf_field()}}
            <center><h2>{{trans('home.paypalCredentials')}}</h2></center> <br/><br/>
            @if($paypal == "")
                <div class="col-sm-12">
                    <div class="col-sm-2"></div>
                    <div class="form-group col-sm-7">
                        <label>{{trans('home.client_id')}}</label>
                        <input type="text" name="client_id" class="form-control" placeholder="{{trans('home.client_id')}}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2"></div>
                    <div class="form-group col-sm-7">
                        <label>{{trans('home.secrete_id')}}</label>
                        <input type="text" name="client_secrete" class="form-control" placeholder="{{trans('home.secrete_id')}}">
                        <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                    </div>
                </div>
            @else
                <div class="col-sm-12">
                    <div class="col-sm-2"></div>
                    <div class="form-group col-sm-7">
                        <label>{{trans('home.client_id')}}</label>
                        <input type="text" name="client_id" class="form-control" value="{{$paypal->client_id}}" placeholder="{{trans('home.client_id')}}">
                        <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-2"></div>
                    <div class="form-group col-sm-7">
                        <label>{{trans('home.secrete_id')}}</label>
                        <input type="text" name="client_secrete" value="{{$paypal->client_secrete}}" class="form-control" placeholder="{{trans('home.secrete_id')}}">
                    </div>
                </div>
            @endif
            <div class="col-ms-12">
                <div class="col-md-2"></div>
                <div class="col-md-7">
                    <center><button type="submit" class="btn btn-save">Save</button></center>
                </div>
            </div>
        </form>
    </div>
</div>
@include('frontEnd.footer')