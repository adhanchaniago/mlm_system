<!-- Affiliate Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('affiliate_id', 'Affiliate Id:') !!}
    {!! Form::text('affiliate_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Month Field -->
<div class="form-group col-sm-12">
    {!! Form::label('month', 'Month:') !!}
    {!! Form::text('month', null, ['class' => 'form-control']) !!}
</div>

<!-- Year Field -->
<div class="form-group col-sm-12">
    {!! Form::label('year', 'Year:') !!}
    {!! Form::text('year', null, ['class' => 'form-control']) !!}
</div>

<!-- Amount Field -->
<div class="form-group col-sm-12">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::text('amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('payouthistories.index') !!}" class="btn btn-default">Cancel</a>
</div>
