<!-- Company Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company Id:') !!}
    {!! Form::text('company_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Smtp Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp', 'Smtp:') !!}
    {!! Form::text('smtp', null, ['class' => 'form-control']) !!}
</div>

<!-- Smtp User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp_user_id', 'Smtp User Id:') !!}
    {!! Form::text('smtp_user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Smtp Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('smtp_password', 'Smtp Password:') !!}
    {!! Form::text('smtp_password', null, ['class' => 'form-control']) !!}
</div>

<!-- Welcome Text Field -->
<div class="form-group col-sm-6">
    {!! Form::label('welcome_text', 'Welcome Text:') !!}
    {!! Form::text('welcome_text', null, ['class' => 'form-control']) !!}
</div>

<!-- New Password Text Field -->
<div class="form-group col-sm-6">
    {!! Form::label('new_password_text', 'New Password Text:') !!}
    {!! Form::text('new_password_text', null, ['class' => 'form-control']) !!}
</div>

<!-- New Affiliate Text Field -->
<div class="form-group col-sm-6">
    {!! Form::label('new_affiliate_text', 'New Affiliate Text:') !!}
    {!! Form::text('new_affiliate_text', null, ['class' => 'form-control']) !!}
</div>

<!-- Delete Account Text Field -->
<div class="form-group col-sm-6">
    {!! Form::label('delete_account_text', 'Delete Account Text:') !!}
    {!! Form::text('delete_account_text', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('emailcontents.index') !!}" class="btn btn-default">Cancel</a>
</div>
