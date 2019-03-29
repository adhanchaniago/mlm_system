<!-- Company Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company Id:') !!}
    {!! Form::text('company_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::text('image', null, ['class' => 'form-control']) !!}
</div>

<!-- Revenue Trigger Field -->
<div class="form-group col-sm-6">
    {!! Form::label('revenue_trigger', 'Revenue Trigger:') !!}
    {!! Form::text('revenue_trigger', null, ['class' => 'form-control']) !!}
</div>

<!-- Payout Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payout_amount', 'Payout Amount:') !!}
    {!! Form::text('payout_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('ranks.index') !!}" class="btn btn-default">Cancel</a>
</div>
