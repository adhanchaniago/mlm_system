<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Phno Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phno', 'Phno:') !!}
    {!! Form::text('phno', null, ['class' => 'form-control']) !!}
</div>

<!-- Bill Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bill_address', 'Bill Address:') !!}
    {!! Form::text('bill_address', null, ['class' => 'form-control']) !!}
</div>

<!-- Card Stripe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('card_stripe', 'Card Stripe:') !!}
    {!! Form::text('card_stripe', null, ['class' => 'form-control']) !!}
</div>

<!-- Logo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logo', 'Logo:') !!}
    {!! Form::text('logo', null, ['class' => 'form-control']) !!}
</div>

<!-- Planid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('planid', 'Planid:') !!}
    {!! Form::text('planid', null, ['class' => 'form-control']) !!}
</div>

<!-- Domain Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('domain_name', 'Domain Name:') !!}
    {!! Form::text('domain_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Folder Field -->
<div class="form-group col-sm-6">
    {!! Form::label('folder', 'Folder:') !!}
    {!! Form::text('folder', null, ['class' => 'form-control']) !!}
</div>

<!-- Activated Field -->
<div class="form-group col-sm-6">
    {!! Form::label('activated', 'Activated:') !!}
    {!! Form::text('activated', null, ['class' => 'form-control']) !!}
</div>

<!-- Valid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valid', 'Valid:') !!}
    {!! Form::text('valid', null, ['class' => 'form-control']) !!}
</div>

<!-- Apikey Field -->
<div class="form-group col-sm-6">
    {!! Form::label('apikey', 'Apikey:') !!}
    {!! Form::text('apikey', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.index') !!}" class="btn btn-default">Cancel</a>
</div>
