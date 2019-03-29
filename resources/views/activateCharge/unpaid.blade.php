@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Companies</h1>
        <h1 class="pull-right">
            {{--<a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('companies.create') !!}">Add New</a>--}}
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body scrollable">
                <table class="table table-responsive" id="unpaid-table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Golbal Revenue</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1;
                    ?>
                        @foreach($users as $user)
                            <?php
                                if (($user->samy_linkedIn == 1 && $user->linkedIn_payment ==0) || ($user->samy_affiliate == 1 && $user->payment == 0) || ($user->samy_bot == 1 && $user->bot_payment == 0))
                                    {
                                $company = \App\Models\company::whereId($user->typeid)->first();
                                if (\Illuminate\Support\Facades\DB::table('purchase_history')->where('company_id',$company->id)->exists())
                                {
                                    $revenue = \Illuminate\Support\Facades\DB::table('purchase_history')->where('company_id',$company->id)->sum('amount');
                                }
                                else
                                {
                                    $revenue=0;
                                }
                            ?>
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$company->name}}</td>
                                <td>{{$company->email}}</td>
                                <td>{{$revenue}}</td>
                                <td>
                                    <div class='btn-group'>
                                        <center>
                                            <a href="{{ url('companies/show'.'/'.$company->id) }}" class='btn btn-default btn-xs'>
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                            @if($user->disabled == 0)
                                                <button type="button" class="btn btn-danger btn-xs" onclick="disableCompany('{{$company->id}}',1)" title="Suspend User"><i class="fa fa-ban"></i></button>
                                            @else
                                                <button type="button" class="btn btn-success btn-xs" onclick="disableCompany('{{$company->id}}',0)" title="Enable User"><i class="fa fa-check"></i></button>
                                            @endif
                                        </center>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $i++;
                            }
                            ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function() {
            $('#unpaid-table').DataTable( {
                dom: 'frtip',
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
        function disableCompany(id,val) {
            if (confirm("Are You Sure?"))
            {
                $.ajax({
                    url: "{{url('disableCompany')}}/"+""+id+"/"+val,
                    success: function(result){
                        window.location.reload();
                    }});
            }
        }
    </script>
@endsection

