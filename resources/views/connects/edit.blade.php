@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Connect
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($connect, ['route' => ['connects.update', $connect->id], 'method' => 'patch']) !!}

                        @include('connects.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection