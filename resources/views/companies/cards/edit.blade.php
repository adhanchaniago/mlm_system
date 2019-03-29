@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Saved Cards
        </h1>
    </section>

    <div class="content">
        @if ($message = Session::get('success'))
            <div class="custom-alerts alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {!! $message !!}
            </div>
            <?php Session::forget('success');?>
        @endif
        @if ($message_verification = Session::get('activated'))
            <div class="custom-alerts alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {!! $message_verification !!}
            </div>
            <?php Session::forget('activated');?>
        @endif
        @if ($message = Session::get('error'))
            <div class="custom-alerts alert alert-danger fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {!! $message !!}
            </div>
            <?php Session::forget('error');?>
        @endif
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <form method="post" action="{{url('savedCards/edit').'/'.$card->id}}">
                        {{csrf_field()}}
                        <div class="form-group col-sm-12">
                            <label>Card Number: </label>
                            <input type="text" class="form-control" value="{{$card_number}}" name="card_no" placeholder="Card Number">
                            <input type="hidden" value="{{Auth::user()->company_id}}" name="company_id">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Expiry Month: </label>
                            <input type="text" class="form-control" name="ccExpiryMonth" value="{{$card_month}}" placeholder="Expiry Month">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Expiry year: </label>
                            <input type="text" class="form-control" name="ccExpiryYear" value="{{$card_year}}" placeholder="Expiry Year">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>CVV: </label>
                            <input type="password" class="form-control" value="{{$card_cvv}}" name="cvvNumber" placeholder="CVV">
                        </div>
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('companies.index') !!}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection