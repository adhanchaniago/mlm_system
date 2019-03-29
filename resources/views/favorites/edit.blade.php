@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Favorite
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($favorite, ['route' => ['favorites.update', $favorite->id], 'method' => 'patch']) !!}

                        @include('favorites.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection