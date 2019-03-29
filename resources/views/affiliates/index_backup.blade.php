
@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Affiliates</h1>
        <h1 class="pull-right">
            @if($level >0 && $level < 12)
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('affiliates.create') !!}">Invite New</a>
            @endif
            @if(Auth::user()->status == '2')
                <button class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" data-toggle="modal" data-target="#purchaseModal">Purchase Link</button>
            @endif
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body scrollable">
                @include('affiliates.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
    <div class="modal fade" id="purchaseModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <center>
                        <h4 class="modal-title">Purchase Link</h4>
                    </center>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('purchase/link')}}">
                        {{csrf_field()}}
                        <div class="form-group col-sm-12">
                            <label>Price: </label>
                            <input type="text" class="form-control" name="price" Placeholder="price">
                            <input type="hidden" name="affiliate_id" value="{{Auth::user()->typeid}}">
                        </div>
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-primary">Send</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>

        </div>
    </div>
@endsection

