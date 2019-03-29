@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1>
        Bot Plan Activate Charge
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
                        <label>Bot Plan Activate Charge</label>
                    </center>
                </div>
                <div class="col-sm-4"></div>
            </div>
            <form method="post" action="{{url('activateCharge/edit')}}">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 form-group">
                        <input type="text" name="amount" class="form-control" value="{{$activate_charge}}">
                    </div>
                    <div class="col-sm-12 form-group"></div>
                    <center><div class="col-sm-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{url('home')}}" class="btn btn-default">Cancel</a>
                        </div></center>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection