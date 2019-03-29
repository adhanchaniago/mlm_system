@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Shipping Charge
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <form method="post" action="{{url('shipping/edit')}}">
                        {{csrf_field()}}
                        <div class="form-group col-sm-12">
                            <label>Shipping Charge in USA: </label>
                            <input type="text" class="form-control" name="usa" value="{{$usa}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Shipping Charge outside of USA: </label>
                            <input type="text" class="form-control" name="other" value="{{$other}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{url('home')}}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection