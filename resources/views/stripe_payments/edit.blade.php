@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Stripe Payment
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($stripePayment, ['route' => ['stripePayments.update', $stripePayment->id], 'method' => 'patch']) !!}

                        @include('stripe_payments.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection