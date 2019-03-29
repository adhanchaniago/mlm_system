<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-12">
    {!! Form::label('term', 'Term:') !!}
    <select name="term" class="form-control">
        <option value="" selected disabled>Select the Term</option>
        <option value="month">Monthly</option>
        <option value="year">Yearly</option>
    </select>
</div>
<div class="form-group col-sm-12">
    <label>Image: </label>
    <input type="file" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" name="image" onchange="readURL(this)">
    <div id="image">

    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('amount_1', 'Amount per 1 Unit:') !!}
    {!! Form::text('amount_1', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('plan_id_1', 'Stripe Plan For 1 Unit:') !!}
    {!! Form::text('plan_id_1', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('amount_5', 'Amount per 5 Device:') !!}
    {!! Form::text('amount_5', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('plan_id_5', 'Stripe Plan For 5 Devices:') !!}
    {!! Form::text('plan_id_5', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('amount_10', 'Amount per 10 Device:') !!}
    {!! Form::text('amount_10', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('plan_id_10', 'Stripe Plan For 10 Devices:') !!}
    {!! Form::text('plan_id_10', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('amount_20', 'Amount per 20 Device:') !!}
    {!! Form::text('amount_20', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('plan_id_20', 'Stripe Plan For 20 Devices:') !!}
    {!! Form::text('plan_id_20', null, ['class' => 'form-control']) !!}
</div>

<!-- Range Field -->
<div class="form-group col-sm-12">
    {!! Form::label('ad_feat', 'Range:') !!}
    {!! Form::text('ad_feat', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('SamyBotPlans.index') !!}" class="btn btn-default">Cancel</a>
</div>
