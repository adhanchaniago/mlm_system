@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Levels</h1>
        <h1 class="pull-right">
            @if($level_count < 12)
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('levels.create') !!}">Add New</a>
            @endif
            @if($level_count >= 1)
                <a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px" onclick="return confirm('Are you sure?')" href='{{url('deleteLevel')}}'>Delete</a>
            @endif
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <span class="alert alert-success" id="successmsg"></span>
        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body" id="tablebody">
                @include('levels.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

