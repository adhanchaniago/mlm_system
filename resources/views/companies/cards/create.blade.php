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
                    <form method="post" action="{{url('SavedCards/Store')}}">
                        {{csrf_field()}}
                        @include('companies/cards.field')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
