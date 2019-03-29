@extends('layouts.app')
@section('content')
    <section class="content-header">
        <h1> Plantable </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($plantable, ['route' => ['plantables.update', $plantable->id], 'method' => 'patch','files' => true]) !!}
                    <div class="form-group col-sm-12">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', $plantable->name, ['class' => 'form-control','placeholder' => 'Plan Name']) !!}
                    </div>
                    <div class="form-group col-sm-12">
                        <label>Type</label>
                        <select class="form-control" name="type">
                            <option value="" selected disabled>Select Type</option>
                            <option value="starter" <?php if ($plantable->type == 'starter') {echo "selected";} ?>>Starter</option>
                            <option value="business" <?php if ($plantable->type == 'business') {echo "selected";} ?>>Business</option>
                            <option value="growth" <?php if ($plantable->type == 'growth') {echo "selected";} ?>>Growth</option>
                        </select>
                    </div>
                    <!-- Amount Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('amount', 'Amount:') !!}
                        {!! Form::text('amount', $plantable->amount, ['class' => 'form-control','Placeholder' => 'Amount']) !!}
                    </div>
                    <!-- Stripe Plan Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('stripe_plan_id', 'Stripe Plan id:') !!}
                        {!! Form::text('stripe_plan_id', $plantable->stripe_plan_id, ['class' => 'form-control','readonly' => 'true']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('levels', 'Maximum Levels:') !!}
                        {!! Form::text('levels', $plantable->levels, ['class' => 'form-control','Placeholder' => 'Maximum Levels']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('affiliates', 'Maximum Affiliates:') !!} <button type="button" class="btn btn-success" onclick="unlimitedAffiliates()">Unlimited</button>
                        <br/><br/>
                        {!! Form::text('affiliates', $plantable->affiliates, ['class' => 'form-control','Placeholder' => 'Maximum Affiliates','id' => 'unlmt']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('commission', 'Commission:') !!} <button type="button" class="btn btn-success" onclick="nocommission()">Zero Commission</button>
                        <br/><br/>
                        {!! Form::text('commission', $plantable->commission, ['class' => 'form-control','Placeholder' => 'Commission','id' => 'cmsn']) !!}
                    </div>

                    <!-- Term Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('term', 'Term:') !!}
                        <select class="form-control" name="term">
                            <option value="" selected disabled>Select a Term</option>
                            <option value="month" <?php if ($plantable->term == 'month') { echo "selected";} ?>>Month</option>
                            <option value="year" <?php if ($plantable->term == 'year') { echo "selected"; } ?>>Year</option>
                        </select>
                    </div>
                    <!-- Sharing Amount Field -->

                    <!-- Image Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('image', 'Image:') !!}
                        <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" class="form-control">
                        @if(isset($plantable->image) || $plantable->image != '')
                            <br/>
                            <div><img src="{{asset('public/avatars').'/'.$plantable->image}}" class="editImg">
                            </div>
                        @endif
                    </div>
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('plantables.index') !!}" class="btn btn-default">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function unlimitedAffiliates() {
        var val = 'unlimited';
        $('#unlmt').val(val);
    }
    function nocommission() {
        var val = 0;
        $('#cmsn').val(val);
    }
</script>