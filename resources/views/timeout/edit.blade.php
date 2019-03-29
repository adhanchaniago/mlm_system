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
            <form method="post" action="{{url('timeout/save')}}">
                {{csrf_field()}}
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
                        <input type="text" name="days" class="form-control" value="{{$timeout->days}}">
                    </div>
                    <div class="col-sm-12 form-group"></div>
                    <center><div class="col-sm-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{url('timeout')}}"><button type="button" class="btn btn-default">Cancel</button></a>
                        </div></center>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection