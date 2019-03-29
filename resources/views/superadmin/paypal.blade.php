@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Paypal Email
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <form method="post" action="{{url('paypal_email')}}">
                        {{csrf_field()}}
                            <div class="form-group col-sm-12">
                                <label>{{trans('myProfile.paypal_email')}}</label>
                                @if($user->paypal_email != "" || !empty($user->paypal_email))
                                    <input type="text" name="paypal_email" class="form-control" value="{{$user->paypal_email}}">
                                @else
                                    <input type="text" name="paypal_email" class="form-control" placeholder="{{trans('myProfile.paypal_email')}}">
                                @endif
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary">{{trans('myProfile.save')}}</button>
                                <a class="btn btn-default" href="{{url('home')}}">{{trans('home.cancel')}}</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection