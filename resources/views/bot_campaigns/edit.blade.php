@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bot Campaign
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($botCampaign, ['route' => ['botCampaigns.update', $botCampaign->id], 'method' => 'patch']) !!}

                        @include('bot_campaigns.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection