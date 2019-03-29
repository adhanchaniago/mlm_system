<!-- Company Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company Id:') !!}
    {!! Form::text('company_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Share To Team Revenue Field -->
<div class="form-group col-sm-6">
    {!! Form::label('share_to_team_revenue', 'Share To Team Revenue:') !!}
    {!! Form::text('share_to_team_revenue', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('levels.index') !!}" class="btn btn-default">Cancel</a>
</div>
