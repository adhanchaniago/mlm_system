<?php
$company = \App\Models\company::whereId($user->typeid)->first();
if (\Illuminate\Support\Facades\DB::table('purchase_history')->where('company_id',$company->id)->exists())
{
    $revenue = \Illuminate\Support\Facades\DB::table('purchase_history')->where('company_id',$company->id)->sum('amount');
}
else
{
    $revenue=0;
}
$plans = \Illuminate\Support\Facades\DB::select("select * from bot_plans where company_id = $company->id");
?>
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Company
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">

                    <div class="row">
                        @if(isset($company->logo))
                            <div class="col-sm-4 form-group">
                                {!! Form::label('logo', 'Logo:') !!}
                                <p><img class="edit-image img-circle" src="{{asset('public/avatars').'/'.$company->logo}}"></p>
                            </div>
                        @else
                            <div class="col-sm-4 form-group">
                                {!! Form::label('logo', 'Logo:') !!}
                                <p><img class="edit-image img-circle" src="{{asset('public/pictures/default.jpg')}}"></p>
                            </div>
                    @endif

                    <!-- Name Field -->
                        <div class="col-sm-4 form-group">
                            {!! Form::label('name', 'Name:') !!}
                            <p>{!! $company->name !!}</p>
                        </div>
                        <!-- Name Field -->
                        <div class="col-sm-4 form-group">
                            {!! Form::label('name', 'Revenue:') !!}
                            <p>{!! $revenue !!}</p>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-sm-4 form-group">
                            {!! Form::label('name', 'Total Affiliates:') !!}
                            <p>{!! $affiliates !!}</p>
                        </div>

                        <!-- Address Field -->
                        <div class="col-sm-4 form-group">
                            {!! Form::label('address', 'Address:') !!}
                            <p>{!! $company->address !!}</p>
                        </div>

                        <!-- Email Field -->
                        <div class="col-sm-4 form-group">
                            {!! Form::label('email', 'Email:') !!}
                            <p>{!! $company->email !!}</p>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <!-- Planid Field -->
                        <div class="col-sm-4 form-group">
                            @foreach($plans as $plan)
                                <?php
                                    $planId = $plan->plan;
                                    $planDetail=App\Models\SamyBotPlans::whereId($planId)->first();
                                ?>
                                    <label>Plan Name: {{$planDetail->name}}</label> <br/>
                                    <label>Plan Start: {{date('m/d/Y',strtotime($plan->date))}}</label> <br/>
                                @if($planDetail->term == 'month')
                                   <label>Plan End: {{date('m/d/Y',strtotime($plan->date."+30 days"))}}</label> <br/>
                                @else
                                   <label>Plan End: {{date('m/d/Y',strtotime($plan->date."+365 days"))}}</label> <br/>
                                @endif
                            @endforeach
                        </div>
                    </div>


                    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
