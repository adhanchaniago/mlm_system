<!-- Company Id Field -->

    <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">


<!-- Title Field -->
<div class="form-group col-sm-12">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-12">
    {!! Form::label('content', 'Content:') !!}
    {!! Form::text('content', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-12">
    {!! Form::label('type', 'Type:') !!}
    <select class="form-control" name="type" onchange="changeMedia(this.value)" required>
        <option @if(isset($salescontent) && $salescontent->type == 'Image') selected @endif value="Image">Image</option>
        <option @if(isset($salescontent) && $salescontent->type == 'Video') selected @endif value="Video">Video</option>
    </select>
</div>
@if(isset($salescontent) && $salescontent->type == 'Image')
    <!-- Image Field -->
    <div class="form-group col-sm-12" id="imageInput">
        {!! Form::label('image', 'Image:') !!}
        <input type="file" name="image" class="form-control">
        <img src="{{asset('public/salesContents')."/".$salescontent->image}}" class="imageSales">
    </div>
@else
    <!-- Image Field -->
    <div class="form-group col-sm-12" id="imageInput">
        {!! Form::label('image', 'Image:') !!}
        <input type="file" name="image" class="form-control">
    </div>
@endif

@if(isset($salescontent) && $salescontent->type == 'Video')
    <!-- Image Field -->
    <div class="form-group col-sm-12" id="videoInput">
        {!! Form::label('image', 'Video Link:') !!}
        <input type="text" name="video" class="form-control" value="{{$salescontent->video}}">
    </div>
@else
    <!-- Image Field -->
    <div class="form-group col-sm-12" id="videoInput" style="display: none;">
        {!! Form::label('image', 'Video Link:') !!}
        <input type="text" name="video" class="form-control">
    </div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('salescontents.index') !!}" class="btn btn-default">Cancel</a>
</div>
@section('scripts')
    <script>
        function changeMedia(type) {
            if(type == "Video"){
                $('#videoInput').show();
                $('#imageInput').hide();
            }
            else{
                $('#videoInput').hide();
                $('#imageInput').show();
            }
        }
    </script>
@endsection