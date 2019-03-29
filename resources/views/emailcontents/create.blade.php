@include('frontEnd.admin_header')
    <div class="container">
        <div class="row">
            @include('adminlte-templates::common.errors')
            {!! Form::open(['route' => 'emailcontents.store']) !!}

            @include('emailcontents.fields')

            {!! Form::close() !!}
        </div>
    </div>

@include('frontEnd.footer')

