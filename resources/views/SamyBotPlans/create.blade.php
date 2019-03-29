@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            SamyBot Plans
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            @include('flash::message')
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'SamyBotPlans.store','files'=>true]) !!}

                    @include('SamyBotPlans.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

    <script>
    function readURL(input)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function (e) {
            var html = '<img class="edit-image" src="' + e.target.result + '">';
            $('#image').html(html);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>

