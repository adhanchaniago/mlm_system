@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1 class="pull-left">Users</h1>

</section>
<div class="content">
    <div class="clearfix"></div>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="box box-primary">
        <div class="box-body">
            @include('users.table')
        </div>
    </div>
    <div class="text-center">

    </div>
</div>
@endsection
@section('scripts')

<script>
    $(document).ready(function() {
        $('#users-table').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, ':visible' ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        } );
    } );
    // $(document).ready(function() {
    //     $('#paypalCredentials-table').DataTable();
    // } );
</script>
@endsection
