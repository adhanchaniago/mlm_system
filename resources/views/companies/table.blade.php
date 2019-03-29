<table class="table table-responsive" id="companies-table">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Total Affiliates</th>
        <th>Global Revenue</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <?php
            $user = \App\User::where('company_id',$company->id)->first();
            $total_affiliates = \App\Models\affiliate::where('company_id',$company->id)->count();
            if (\Illuminate\Support\Facades\DB::table('purchase_history')->where('company_id',$company->id)->exists())
            {
                $total_revenue = 0;
                $revenues = \Illuminate\Support\Facades\DB::table('purchase_history')->where('company_id',$company->id)->get();
                foreach ($revenues as $revenue)
                {
                    $total_revenue +=(float)$revenue->amount;
                }
            }
        ?>
        <tr>
            <td>{!! $company->name !!}</td>
            <td>{!! $company->email !!}</td>
            <td>{!! $total_affiliates !!}</td>
            <td>{!! $total_revenue !!}</td>
            <td>
                {!! Form::open(['route' => ['companies.destroy', $company->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <center>
                        <a href="{{ route('companies.show', $company->id) }}" class='btn btn-default btn-xs'>
                            <i class="glyphicon glyphicon-eye-open"></i>
                        </a>
                    </center>
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>