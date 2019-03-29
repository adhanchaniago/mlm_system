@extends('layouts.app')
@section('content')
    <section class="content-header">
        <h1> Plantable </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($plantable, ['route' => ['plantables.update', $plantable->id], 'method' => 'patch','files' => true]) !!}
                    <div class="form-group col-sm-12">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', $plantable->name, ['class' => 'form-control','placeholder' => 'Plan Name']) !!}
                    </div>
                    <!-- Amount Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('amount', 'Amount:') !!}
                        {!! Form::text('amount', $plantable->amount, ['class' => 'form-control','Placeholder' => 'Amount']) !!}
                    </div>
                    <!-- Term Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('term', 'Term:') !!}
                        <select class="form-control" name="term">
                            <option value="" selected disabled>Select a Term</option>
                            <option value="month" <?php if ($plantable->term == 'month') { echo "selected";} ?>>Month
                            </option>
                            <option value="year" <?php if ($plantable->term == 'month') { echo "once"; } ?>>Year</option>
                        </select>
                    </div>
                    <!-- Sharing Amount Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('sharing_amount', 'Sharing Amount:') !!}
                        {!! Form::text('sharing_amount', $plantable->sharing_amount, ['class' => 'form-control','placeholder' => 'Sharing Amount']) !!}                   </div>
                    <!-- Image Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('image', 'Image:') !!}
                        <input type="file" name="image" class="form-control">
                        @if(isset($plantable->image) || $plantable->image != '')
                            <br/>
                            <div><img src="{{asset('public/avatars').'/'.$plantable->image}}" class="editImg">
                            </div>
                        @endif
                    </div>
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        <a href="{!! route('plantables.index') !!}" class="btn btn-default">Cancel</a>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>@endsection