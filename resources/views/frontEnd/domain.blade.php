@include('frontEnd.mainHeader')
<div class="container">
    <div class="row verification-row">

    </div>
    <div class="row">
        <div class="col-md-12 verify-email-content">
            <div class="col-md-6">
                <img src="{{asset('public/pictures/uncheck.svg')}}" class="verify-email-image">
                <h4>{{trans('home.domain')}}</h4>
            </div>
        </div>
        <div class="row">

        </div>
    </div>
    <div class="row verification-row">

    </div>
</div>
@include('frontEnd.footer')