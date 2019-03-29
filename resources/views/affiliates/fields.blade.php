<!-- Company Id Field -->


<input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">


<!-- Photo Field -->

<div class="form-group col-sm-12">

    {!! Form::label('photo', 'Photo:') !!}

    <input type="file" name="photo" class="form-control" onchange="readURL(this)">
    <div id="image2">
        @if(isset($affiliate->photo))
            <img src="{{asset('public/avatars').'/'.$affiliate->photo}}" class="table-img">
        @endif
    </div>

</div>

<!-- Name Field -->

<div class="form-group col-sm-12">

    {!! Form::label('name', 'Name:') !!}

    {!! Form::text('name', null, ['class' => 'form-control']) !!}

</div>



<!-- Email Field -->

<div class="form-group col-sm-12">

    {!! Form::label('email', 'Email:') !!}

    {!! Form::text('email', null, ['class' => 'form-control']) !!}

</div>



<!-- Phone Field -->

<div class="form-group col-sm-12">

    {!! Form::label('phone', 'Phone:') !!}

    {!! Form::text('phone', null, ['class' => 'form-control']) !!}

</div>



<!-- Paypal Email Field -->

<div class="form-group col-sm-12">

    {!! Form::label('paypal_email', 'Paypal Email:') !!}

    {!! Form::text('paypal_email', null, ['class' => 'form-control']) !!}

</div>


<!-- Submit Field -->

<div class="form-group col-sm-12">

    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}

    <a href="{!! route('affiliates.index') !!}" class="btn btn-default">Cancel</a>

</div>

@section('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var html = '<img class="table-img" src="'+e.target.result+'">';
                    $('#image2')
                        .html(html);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection

