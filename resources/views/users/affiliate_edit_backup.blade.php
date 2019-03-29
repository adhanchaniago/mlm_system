
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{$company_name}}
        </h1>
    </section>
    <div class="content">
        @if ($message = Session::get('success'))

            <div class="custom-alerts alert alert-success fade in">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                {!! $message !!}

            </div>

            <?php Session::forget('success');?>

        @endif
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <form method="post" enctype="multipart/form-data" action="{{url('affiliate/details').'/'.$affliate->id}}">
                        {{csrf_field()}}
                        <div class="form-group col-sm-12">
                            <label>Name: </label>
                            <input type="text" name="name" class="form-control" value="{{$affliate->name}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Company Name: </label>
                            <input type="text" readonly class="form-control" value="{{$company_name}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Email: </label>
                            <input type="email" readonly class="form-control" value="{{$affliate->email}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Phone Number: </label>
                            <input type="text" name="phone" class="form-control" id="phone_number" value="{{$affliate->phone}}" onchange="validatePhone()">
                            <p class="alert alert-danger" id="invalidPhone"></p>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Photo: </label>
                            <input type="file" name="photo" class="form-control" onchange="readURL(this)">
                            <div id="image">
                                @if(isset($affliate->photo))
                                    <img src="{{asset('public/avatars').'/'.$affliate->photo}}" class="table-img">
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Paypal Email: </label>
                            <input type="text" name="paypal_email" class="form-control" value="{{$affliate->paypal_email}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <button type="button" class="btn btn-primary">Save</button>
                            <a href="{!! url('dashboard') !!}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var html = '<img class="table-img" src="'+e.target.result+'">';
                    $('#image')
                        .html(html);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        function validatePhone() {
            var phone_number = $('#phone_number').val();
            var phone_number_1 = phone_number.substring(1);
            if(/^\d+$/.test(phone_number_1) && phone_number.length > 10)
            {
                $('#invalidPhone').css('display','none');
                $.ajax({
                    url: "{{url('validatePhone')}}"+'/'+phone_number,
                    success: function(result){
                        if (result == "success")
                        {
                            $('#aff-reg-btn').prop('type','submit');
                            $('#invalidPhone').css('display','none');
                        }
                        else
                        {
                            $('#aff-reg-btn').prop('type','button');
                            $('#invalidPhone').text('This phone Number is already exist');
                            $('#invalidPhone').css('display','block');
                        }
                    }});
            }
            else
            {
                $('#aff-reg-btn').prop('type','button');
                $('#invalidPhone').text('Please Enter the Valid Phone Number');
                $('#invalidPhone').css('display','block');
            }

        }
    </script>
@endsection