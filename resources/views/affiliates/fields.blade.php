<!-- Company Id Field -->

<div class="form-group col-sm-12">

    {!! Form::label('company_id', 'Company Id:') !!}

    {!! Form::text('company_id', null, ['class' => 'form-control']) !!}

</div>



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



<!-- Rankid Field -->

<div class="form-group col-sm-12">

    {!! Form::label('rankid', 'Rankid:') !!}

    {!! Form::text('rankid', null, ['class' => 'form-control']) !!}

</div>



<!-- Current Revenue Field -->

<div class="form-group col-sm-12">

    {!! Form::label('current_revenue', 'Current Revenue:') !!}

    {!! Form::text('current_revenue', null, ['class' => 'form-control']) !!}

</div>



<!-- Past Revid Field -->

<div class="form-group col-sm-12">

    {!! Form::label('past_revid', 'Past Revid:') !!}

    {!! Form::text('past_revid', null, ['class' => 'form-control']) !!}

</div>



<!-- Level P1 Affiliateid Field -->

<div class="form-group col-sm-12">

    {!! Form::label('level_p1_affiliateid', 'Level P1 Affiliateid:') !!}

    {!! Form::text('level_p1_affiliateid', null, ['class' => 'form-control']) !!}

</div>



<!-- Level M1 Affiliateid Field -->

<div class="form-group col-sm-12">

    {!! Form::label('level_m1_affiliateid', 'Level M1 Affiliateid:') !!}

    {!! Form::text('level_m1_affiliateid', null, ['class' => 'form-control']) !!}

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

