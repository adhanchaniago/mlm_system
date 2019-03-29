@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sales Content
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($salescontent, ['route' => ['salescontents.update', $salescontent->id], 'method' => 'patch','files'=>true]) !!}

                        @include('salescontents.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection