@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Rank
        </h1>
    </section>
    <div class="content">
        <p class="alert alert-danger rank-image-alert">Rank Image is Required</p>
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'ranks.store','files'=>true]) !!}

                        @include('ranks.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function rankValidation()
        {
            var image = $('#rankImg').val();
            if (image == '')
            {
                $('.rank-image-alert').css('display','block');
            }
            else
            {
                $('.rank-image-alert').css('display','none');
                $('#rank-save').prop('type','submit');
            }
        }
    </script>
@endsection
