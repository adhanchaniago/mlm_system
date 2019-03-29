@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Weeklyfees
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($weeklyfees, ['route' => ['weeklyfees.update', $weeklyfees->id], 'method' => 'patch']) !!}

                        @include('weeklyfees.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection