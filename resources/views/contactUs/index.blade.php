@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Messages</h1>
        <h1 class="pull-right">
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('contactUs.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function() {
            $('#contactUs-table').DataTable( {
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
        function deleteMsg(id) {
            if(confirm("Are You Sure?")) {
                $.ajax({
                    url: "{{url('deleteMsg')}}" + '/' + id,
                    success: function (result) {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
@endsection

