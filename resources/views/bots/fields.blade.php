<!-- Bot Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bot_id', 'Bot Id:') !!}
    {!! Form::text('bot_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Company Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company Id:') !!}
    {!! Form::text('company_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Beacon Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('beacon_UUID', 'Beacon Uuid:') !!}
    {!! Form::text('beacon_UUID', null, ['class' => 'form-control']) !!}
</div>

<!-- Bot Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bot_name', 'Bot Name:') !!}
    {!! Form::text('bot_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Bot Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bot_type', 'Bot Type:') !!}
    {!! Form::text('bot_type', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('bots.index') !!}" class="btn btn-default">Cancel</a>
</div>
