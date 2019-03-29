<!-- Campaign Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_id', 'Campaign Id:') !!}
    {!! Form::text('campaign_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Company Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company Id:') !!}
    {!! Form::text('company_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaign Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_name', 'Campaign Name:') !!}
    {!! Form::text('campaign_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaign Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_title', 'Campaign Title:') !!}
    {!! Form::text('campaign_title', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaign Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_image', 'Campaign Image:') !!}
    {!! Form::text('campaign_image', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaing Link Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaing_link', 'Campaing Link:') !!}
    {!! Form::text('campaing_link', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaigns Views Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaigns_views', 'Campaigns Views:') !!}
    {!! Form::text('campaigns_views', null, ['class' => 'form-control']) !!}
</div>

<!-- Campaign Clicks Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_clicks', 'Campaign Clicks:') !!}
    {!! Form::text('campaign_clicks', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('campaigns.index') !!}" class="btn btn-default">Cancel</a>
</div>
