@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Revenuehistory
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($revenuehistory, ['route' => ['revenuehistories.update', $revenuehistory->id], 'method' => 'patch']) !!}

                        @include('revenuehistories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection