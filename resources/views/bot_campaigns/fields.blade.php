<!-- Bot Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('bot_id', 'Bot Id:') !!}
    {!! Form::text('bot_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaign Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_id', 'Campaign Id:') !!}
    {!! Form::text('campaign_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('botCampaigns.index') !!}" class="btn btn-default">Cancel</a>
</div>
