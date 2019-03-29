<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $timeout->id !!}</p>
</div>

<!-- Days Field -->
<div class="form-group">
    {!! Form::label('days', 'Days:') !!}
    <p>{!! $timeout->days !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $timeout->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $timeout->updated_at !!}</p>
</div>

