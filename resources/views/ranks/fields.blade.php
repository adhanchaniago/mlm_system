
<!-- Company Id Field -->
    <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">

<div class="form-group col-sm-12">
    <label>Rank: </label>
    @if(isset($rank))
        <input type="text" readonly name="rank" class="form-control" value="{{$rank->rank}}">
    @else
        <input type="text" readonly name="rank" class="form-control" value="{{$next_rank}}">
    @endif
</div>
<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-12">
    {!! Form::label('image', 'Image:') !!}
    <input type="file" name="image" class="form-control" id="rankImg">
    @if(isset($rank->image))
        <img src="{{asset('public/avatars').'/'.$rank->image}}" class="table-img">
    @endif
</div>

<!-- Revenue Trigger Field -->
<div class="form-group col-sm-12">
    {!! Form::label('revenue_trigger', 'Revenue Trigger:') !!}
    {!! Form::text('revenue_trigger', null, ['class' => 'form-control']) !!}
</div>

<!-- Payout Amount Field -->
<div class="form-group col-sm-12">
    {!! Form::label('payout_amount', 'Payout Amount:') !!}
    {!! Form::text('payout_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    @if(isset($rank->image))
        <button type="submit" class="btn btn-primary">Save</button>
    @else
        <button id="rank-save" type="button" class="btn btn-primary" onclick="rankValidation()">Save</button>
    @endif
    <a href="{!! route('ranks.index') !!}" class="btn btn-default">Cancel</a>
</div>
