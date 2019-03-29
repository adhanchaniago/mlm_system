@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payouthistory
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($payouthistory, ['route' => ['payouthistories.update', $payouthistory->id], 'method' => 'patch']) !!}

                        @include('payouthistories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection