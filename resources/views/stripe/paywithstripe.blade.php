<?php
$id = Auth::user()->typeid;
$user = App\Models\company::whereId($id)->first();
$plan = App\Models\plantable::whereId($user->planid)->first();
$amount = (int)$plan->amount;
?>
@include('frontEnd.header')
<style>
    body
    {
        background-color: #ecf0f5;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ_zcalLsl2Lrma87qgAs9QtM-0NQLmYs&libraries=places&callback=initAutocomplete" async defer></script>

<div class="container">

    <div class="row">

        <div class="col-md-8 col-md-offset-2">
            <center>
                <h1>Pay With Stripe</h1>
            </center>

            <div class="panel panel-default">

                @if ($message = Session::get('success'))

                    <div class="custom-alerts alert alert-success fade in">

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                        {!! $message !!}

                    </div>

                    <?php Session::forget('success');?>

                @endif

                @if ($message_verification = Session::get('activated'))

                    <div class="custom-alerts alert alert-success fade in">

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                        {!! $message_verification !!}

                    </div>

                    <?php Session::forget('success');?>

                @endif

                @if ($message = Session::get('error'))

                    <div class="custom-alerts alert alert-danger fade in">

                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                        {!! $message !!}

                    </div>

                    <?php Session::forget('error');?>

                @endif

                {{--<div class="panel-heading">Paywith Stripe</div>--}}

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL('stripe') !!}" >

                        {{ csrf_field() }}
                        <center>
                            <h3>Company Details</h3>
                        </center>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name: </label>
                            <div class="col-md-6">

                                <input id="name" type="text" class="form-control" name="name" value="{{$user->name}}" autofocus>

                                @if ($errors->has('name'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('name') }}</strong>

                                    </span>

                                @endif

                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email: </label>
                            <div class="col-md-6">

                                <input id="email" type="email" class="form-control" name="email" value="{{$user->email}}" autofocus>

                                @if ($errors->has('email'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('email') }}</strong>

                                    </span>

                                @endif

                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone: </label>
                            <div class="col-md-6">

                                <input id="phone" type="text" class="form-control" name="phone" value="{{$user->phno}}" autofocus>

                                @if ($errors->has('phone'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('phone') }}</strong>

                                    </span>

                                @endif

                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Address: </label>
                            <div class="col-md-6">

                                <input id="autocomplete" type="text" class="form-control" name="address" value="{{$user->address}}" autofocus onfocus="geolocate()">

                                @if ($errors->has('address'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('address') }}</strong>

                                    </span>

                                @endif

                            </div>
                        </div>
                        <center>
                            <h3>Card Details</h3>
                        </center>
                        <div class="form-group{{ $errors->has('card_no') ? ' has-error' : '' }}">

                            <label for="card_no" class="col-md-4 control-label">Card No</label>

                            <div class="col-md-6">

                                <input id="card_no" type="text" class="form-control" name="card_no" value="{{ old('card_no') }}" autofocus>

                                @if ($errors->has('card_no'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('card_no') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('ccExpiryMonth') ? ' has-error' : '' }}">

                            <label for="ccExpiryMonth" class="col-md-4 control-label">Expiry Month</label>

                            <div class="col-md-6">

                                <input id="ccExpiryMonth" type="text" class="form-control" name="ccExpiryMonth" value="{{ old('ccExpiryMonth') }}" autofocus>

                                @if ($errors->has('ccExpiryMonth'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('ccExpiryMonth') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('ccExpiryYear') ? ' has-error' : '' }}">

                            <label for="ccExpiryYear" class="col-md-4 control-label">Expiry Year</label>

                            <div class="col-md-6">

                                <input id="ccExpiryYear" type="text" class="form-control" name="ccExpiryYear" value="{{ old('ccExpiryYear') }}" autofocus>

                                @if ($errors->has('ccExpiryYear'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('ccExpiryYear') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('cvvNumber') ? ' has-error' : '' }}">

                            <label for="cvvNumber" class="col-md-4 control-label">CVV No.</label>

                            <div class="col-md-6">

                                <input id="cvvNumber" type="text" class="form-control" name="cvvNumber" value="{{ old('cvvNumber') }}" autofocus>

                                @if ($errors->has('cvvNumber'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('cvvNumber') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">

                            <label for="amount" class="col-md-4 control-label">Amount</label>

                            <div class="col-md-6">

                                <input id="amount" readonly type="text" class="form-control" name="amount" value="&dollar;{{ $amount }}" autofocus>

                                @if ($errors->has('amount'))

                                    <span class="help-block">

                                        <strong>{{ $errors->first('amount') }}</strong>

                                    </span>

                                @endif

                            </div>

                        </div>



                        <div class="form-group">

                            <div class="col-md-6 col-md-offset-4">

                                <button type="submit" class="btn btn-primary pull-right">

                                    Paywith Stripe

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
@include('frontEnd.footer')
<script>
    // This example displays an address form, using the autocomplete feature
    // of the Google Places API to help users fill in the information.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    var placeSearch, autocomplete;
//    var componentForm = {
//        street_number: 'short_name',
//        route: 'long_name',
//        locality: 'long_name',
//        administrative_area_level_1: 'short_name',
//        country: 'long_name',
//        postal_code: 'short_name'
//    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

//        for (var component in componentForm) {
//            document.getElementById(component).value = '';
//            document.getElementById(component).disabled = false;
//        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
//            if (componentForm[addressType]) {
//                var val = place.address_components[i][componentForm[addressType]];
//                document.getElementById(addressType).value = val;
//            }
        }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }
</script>