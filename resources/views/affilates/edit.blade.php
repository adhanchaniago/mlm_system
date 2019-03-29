@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Affilates
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($affilates, ['route' => ['affilates.update', $affilates->id], 'method' => 'patch']) !!}

                        @include('affilates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection