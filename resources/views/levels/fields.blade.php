<!-- Company Id Field -->
<div class="form-group col-sm-12">
    <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
</div>

<!-- Share To Team Revenue Field -->
<div class="form-group col-sm-12">
    <label>Level: </label>
    <input type="hidden" name="level" value="{{$next_level}}">
    <input type="text" readonly class="form-control" value="{{$next_level}}">
</div>
<div class="form-group col-sm-12">
    {!! Form::label('share_to_team_revenue', 'Share To Team Revenue:') !!}
    {!! Form::text('share_to_team_revenue', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('levels.index') !!}" class="btn btn-default">Cancel</a>
</div>
