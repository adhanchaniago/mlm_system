@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Level
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($level, ['route' => ['levels.update', $level->id], 'method' => 'patch']) !!}

                   <div class="form-group col-sm-12">
                       <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                   </div>

                   <!-- Share To Team Revenue Field -->
                   <div class="form-group col-sm-12">
                       <label>Level: </label>
                       <input type="hidden" name="level" value="{{$level->level}}">
                       <input type="text" readonly class="form-control" value="{{$level->level}}">
                   </div>
                   <div class="form-group col-sm-12">
                       {!! Form::label('share_to_team_revenue', 'Share To Team Revenue:') !!}
                       {!! Form::text('share_to_team_revenue', $level->share_to_team_revenue, ['class' => 'form-control']) !!}
                   </div>

                   <!-- Submit Field -->
                   <div class="form-group col-sm-12">
                       {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                       <a href="{!! route('levels.index') !!}" class="btn btn-default">Cancel</a>
                   </div>

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection