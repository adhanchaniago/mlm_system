@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1 class="pull-left">Saved Cards</h1>
    <h1 class="pull-right">
        <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! url('savedCards/add') !!}">Add New</a>
    </h1>
</section>
<div class="content">
    <div class="clearfix"></div>
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
    @include('flash::message')

    <div class="clearfix"></div>
    <div class="box box-primary">
        <div class="box-body scrollable">
            @include('companies/cards.table')
        </div>
    </div>
    <div class="text-center">

    </div>
</div>
@endsection
@section('scripts')

<script>
    $(document).ready(function() {
        $('#cards-table').DataTable( {
            dom: 'frtip',
        } );
    } );
    // $(document).ready(function() {
    //     $('#paypalCredentials-table').DataTable();
    // } );
    function activateCard(id) {
        if (confirm("Are You Sure You want to Activate This Card?"))
        {
            $.ajax({
                url: "{{url('activateCard')}}"+"/"+id,
                success: function(result){
                    window.location.reload();
                }});
        }
    }
</script>
@endsection