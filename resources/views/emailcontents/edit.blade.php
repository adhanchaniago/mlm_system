@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Emailcontent
        </h1>
   </section>
   <div class="content">
       @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($emailcontent, ['route' => ['emailcontents.update', $emailcontent->id], 'method' => 'patch']) !!}

                        @include('emailcontents.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection