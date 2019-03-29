@if(Auth::user()->status == '1')
    @include('frontEnd.admin_header')
@else
    @include('frontEnd.affiliate.header')
@endif
<div class="container">
    <div class="row verification-row">

    </div>
    <div class="row">
        <div class="col-md-12 verify-email-content">
            <div class="col-md-6">
                <img src="{{asset('public/pictures/contact.svg')}}" class="verify-email-image">
                <h2>{{trans('home.suspend')}}</h2> <br/>
                <h4>{{trans('home.contact_admin')}}</h4>
            </div>
        </div>
    </div>
    <div class="row verification-row">

    </div>
</div>

@include('frontEnd.footer')