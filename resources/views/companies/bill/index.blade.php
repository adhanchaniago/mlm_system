@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Billing Details</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body scrollable">
                @include('companies/bill.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function() {
            $('#billing-table').DataTable( {
                dom: 'frtip',
            } );
        } );
        // $(document).ready(function() {
        //     $('#paypalCredentials-table').DataTable();
        // } );
        function changeStatus(id,val) {
            if (confirm("Are You Sure?"))
            {
                $.ajax({
                    url: "{{url('changeStatus')}}/"+""+id+"/"+val,
                    success: function(result){
                        window.location.reload();
                    }});
            }
        }
    </script>
@endsection