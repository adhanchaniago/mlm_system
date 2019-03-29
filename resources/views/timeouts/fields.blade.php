<!-- Days Field -->
<div class="form-group col-sm-6">
    {!! Form::label('days', 'Days:') !!}
    {!! Form::text('days', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('timeouts.index') !!}" class="btn btn-default">Cancel</a>
</div>
