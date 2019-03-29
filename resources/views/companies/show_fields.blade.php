<!-- Id Field -->
<div class="row">
    <div class="col-sm-4 form-group">
        {!! Form::label('id', 'Id:') !!}
        <p>{!! $company->id !!}</p>
    </div>

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
    <!-- Phno Field -->
    <div class="col-sm-4 form-group">
        {!! Form::label('phno', 'Phno:') !!}
        <p>{!! $company->phno !!}</p>
    </div>

    <!-- Bill Address Field -->
    <div class="col-sm-4 form-group">
        {!! Form::label('bill_address', 'Bill Address:') !!}
        <p>{!! $company->bill_address !!}</p>
    </div>


    <!-- Logo Field -->
    @if(isset($company->logo))
        <div class="col-sm-4 form-group">
            {!! Form::label('logo', 'Logo:') !!}
            <p><img class="edit-image img-circle" src="{{asset('public/avatars').'/'.$company->logo}}"></p>
        </div>
    @endif
</div>
<hr/>
<div class="row">
    @if($user->samy_affiliate)
        <?php
        $plan = \App\Models\plantable::whereId($planTable->planid)->first();
        ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>Plan Name:</th>
                <th>Plan Start:</th>
                <th>Plan Expiry:</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Samy Affiliate:</td>
                <td>{{$plan->type}}</td>
                <td>{{$planTable->plan_start}}</td>
                <td>{{$planTable->plan_end}}</td>
            </tr>
            </tbody>
        </table>
    @endif
</div>
<div class="row">
    @if($user->samy_bot)
        <?php
        $plans = \Illuminate\Support\Facades\DB::table('bot_plans')->where('company_id', $company->id)->get();
        ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th> </th>
                    <th>Plan Name:</th>
                    <th>Plan Start:</th>
                    <th>Plan Expiry:</th>
                </tr>
                </thead>
                <tbody>
        @foreach($plans as $plan)
            <?php
            $plan_details = App\Models\SamyBotPlans::whereId($plan->plan)->first();
            ?>
                <tr>

                    <td>SamyBot:</td>
                    <td>{{$plan_details->name}}</td>
                    <td>{{date('m/d/Y',strtotime($plan->date))}}</td>
                    <td>@if($plan_details->term == 'month')
                            <h4>Plan Expiry: {{date('m/d/Y',strtotime($plan->date."+30 days"))}}</h4>
                        @else
                            <h4>Plan Expiry: {{date('m/d/Y',strtotime($plan->date."+365 days"))}}</h4>
                        @endif
                    </td>
                </tr>

        @endforeach
                </tbody>
            </table>
    @endif
</div>
<div class="row">
    @if($user->samy_linkedin)
        <?php
        $plan = \Illuminate\Support\Facades\DB::table('linkedin_plans')->whereId($company->linkedIn_plan)->first();
        ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th> </th>
                <th>Plan Name:</th>
                <th>Plan Start:</th>
                <th>Plan Expiry:</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Samy LinkedIn:</td>
                <td>{{$plan->type}}</td>
                <td>{{$company->link_plan_start}}</td>
                <td>{{$company->link_plan_expire}}</td>
            </tr>
            </tbody>
        </table>
    @endif
</div>

<hr/>
<div class="row">
    <!-- Domain Name Field -->
    <div class="col-sm-4 form-group">
        {!! Form::label('domain_name', 'Domain Name:') !!}
        <p>{!! $company->domain_name !!}</p>
    </div>

    <!-- Folder Field -->

    <!-- Apikey Field -->
    <div class="col-sm-4 form-group">
        {!! Form::label('apikey', 'Apikey:') !!}
        <p>{!! $company->apikey !!}</p>
    </div>
</div>

