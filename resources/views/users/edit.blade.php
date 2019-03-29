<?php
use App\Models\plantable;
if (Auth::user()->status == '1')
{
    $plan = plantable::whereId($company->planid)->first();
}
?>
@extends('layouts.app')

@section('content')
<section class="content-header">

    <h1 class="pull-left company-name">{{$company->name}}</h1>

</section>
<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="box box-primary">
        <div class="box-body">
            @if(Auth::user()->status == '1')
                <form method="post" enctype="multipart/form-data" action="{{url('company/details').'/'.$company->id}}">
                {{csrf_field()}}
                <div class="form-group col-sm-12">
                    <label>Name: </label>
                    <input type="text" name="name" class="form-control" value="{{$company->name}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Email: </label>
                    <input type="email" readonly class="form-control" value="{{$company->email}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Phone Number: </label>
                    <input type="text" name="phno" class="form-control" value="{{$company->phno}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Logo: </label>
                    <input type="file" name="logo" class="form-control" onchange="readURL(this)">
                    <div id="image">
                        @if(isset($company->logo))
                            <img src="{{asset('public/avatars').'/'.$company->logo}}" class="table-img">
                        @endif
                    </div>
                </div>
                <div class="form-group col-sm-12">
                    <label>Plan: </label>
                    <input type="text" readonly class="form-control" value="{{$plan->name}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Address: </label>
                    <input type="text" name="address" class="form-control" value="{{$company->address}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Bill Address: </label>
                    <input type="text" name="bill_address" class="form-control" value="{{$company->bill_address}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Card Stripe: </label>
                    <input type="text" name="card_stripe" class="form-control" value="{{$company->card_stripe}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Domain Name: </label>
                    <input type="text" name="domain_name" class="form-control" value="{{$company->domain_name}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>Folder: </label>
                    <input type="text" name="folder" class="form-control" value="{{$company->folder}}">
                </div>
                <div class="form-group col-sm-12">
                    <label>API Key: </label>
                    <input type="text" name="apikey" class="form-control" value="{{$company->apikey}}">
                </div>
                <div class="form-group col-sm-12">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{!! url('dashboard') !!}" class="btn btn-default">Cancel</a>
                </div>
            </form>
            @elseif(Auth::user()->status == '2')
                <?php
                    $company_name = \App\Models\company::whereId($company->company_id)->first();
                ?>
                <form method="post" enctype="multipart/form-data" action="{{url('affiliate/details').'/'.$company->id}}">
                    {{csrf_field()}}
                    <div class="form-group col-sm-12">
                        <label>Name: </label>
                        <input type="text" name="name" class="form-control" value="{{$company->name}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Company Name: </label>
                        <input type="text" readonly class="form-control" value="{{$company_name->name}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Email: </label>
                        <input type="email" readonly class="form-control" value="{{$company->email}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Phone Number: </label>
                        <input type="text" name="phone" class="form-control" value="{{$company->phone}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Photo: </label>
                        <input type="file" name="photo" class="form-control" onchange="readURL2(this)">
                        <div id="image2">
                            @if(isset($company->photo))
                                <img src="{{asset('public/avatars').'/'.$company->photo}}" class="table-img">
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Paypal Email: </label>
                        <input type="text" name="paypal_email" class="form-control" value="{{$company->paypal_email}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Current Revenue: </label>
                        <input type="text" name="current_revenue" class="form-control" value="{{$company->current_revenue}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Level p1 Affiliateid: </label>
                        <input type="text" name="level_p1_affiliateid" class="form-control" value="{{$company->level_p1_affiliateid}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Level m1 Affiliateid: </label>
                        <input type="text" name="level_m1_affiliateid" class="form-control" value="{{$company->level_m1_affiliateid}}">
                    </div>
                    <div class="form-group col-sm-12">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{!! url('dashboard') !!}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <div class="text-center">

    </div>
</div>
@endsection
@section('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var html = '<img class="table-img" src="'+e.target.result+'">';
                    $('#image2')
                        .html(html);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var html = '<img class="table-img" src="'+e.target.result+'">';
                    $('#image')
                        .html(html);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection

