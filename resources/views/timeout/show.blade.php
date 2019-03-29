@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Disable Timings
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 form-group">
                        <center>
                            <label>Disable Timings (Days)</label>
                        </center>
                    </div>
                    <div class="col-sm-4"></div>
                </div>
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 form-group">
                        <input type="text" name="days" class="form-control" value="{{$timeout->days}}" readonly>
                    </div>
                    <div class="col-sm-12 form-group"></div>
                    <center><div class="col-sm-12">
                            <a href="{{url('timeout/edit')}}"><button type="button" class="btn btn-primary">Edit</button></a>
                        </div></center>
                </div>
            </div>
        </div>
    </div>
@endsection