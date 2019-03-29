@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Timeout
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($timeout, ['route' => ['timeouts.update', $timeout->id], 'method' => 'patch']) !!}

                        @include('timeouts.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection