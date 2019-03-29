@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">SamyBot Plans</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('SamyBotPlans.create') !!}">Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body scrollable">
                    @include('SamyBotPlans.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function() {
            $('#samyBotPlans-table').DataTable( {
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
                    url: "{{url('changeBotPlanStatus')}}/"+""+id+"/"+val,
                    success: function(result){
                    window.location.reload();
                }});
            }
        }
    </script>
@endsection

