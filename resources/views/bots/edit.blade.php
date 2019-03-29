@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bot
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($bot, ['route' => ['bots.update', $bot->id], 'method' => 'patch']) !!}

                        @include('bots.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection