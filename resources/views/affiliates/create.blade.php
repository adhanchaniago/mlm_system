@extends('layouts.app')
@section('content')
    <section class="content-header">
        <h1> Affiliate </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <form method="post" enctype="multipart/form-data" action="{{url('send/activation-link')}}">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="form-group col-sm-8">
                            <label>Name : </label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Affliate Name">
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="form-group col-sm-8">
                            <label>Email : </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Affliate Email">
                            <input type="hidden" readonly name="invitee" value="{{Auth::user()->id}}"></div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="form-group col-sm-8">
                            <center>
                                <button type="submit" class="btn btn-primary">Send Link</button>
                                <a href="{!! route('affiliates.index') !!}" class="btn btn-default">Cancel</a></center>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection