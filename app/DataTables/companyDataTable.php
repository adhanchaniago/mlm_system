<?php

namespace App\DataTables;

use App\Models\affiliate;
use App\Models\company;
use App\Models\plantable;
use Form;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;
use datatables;

class companyDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'companies.datatables_actions')
            ->addColumn('golablrevenue', function ($data)
            {
                $revenue=DB::table('purchase_history')->where('company_id',$data->id)->SUM('amount');
                return $revenue;
            })
            ->addColumn('affiliates', function ($data)
            {
                $affiliates=affiliate::where('company_id',$data->id)->count();
                return $affiliates;
            })
            ->addColumn('commission', function ($data)
            {
                if($data->samy_mlm_active == 1) {
                    $plan = plantable::whereId($data->planid)->first();
                    return $plan->commission . '%';
                }
                else
                {
                    return 'NA';
                }
            })
            ->addColumn('plan', function ($data)
            {
                if($data->samy_mlm_active == 1) {
                    $plan = plantable::whereId($data->planid)->first();
                    return $plan->type;
                }
                else
                {
                    return 'NA';
                }
            })
            ->editColumn('logo', function($data){
                if ($data->logo != '' || !empty($data->logo))
                {
                    return "<img class='table-img' src='" . asset('public/avatars/') . '/' . $data->logo . "'>";
                }
                else
                {
                    return "<img class='table-img' src='" . asset('public/pictures/default.jpg') . "'>";
                }

            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
//        $companies = company::query();
        $query = company::query();
        return $query;

        return $this->applyScopes($query);

//        return datatables($query)->toJson();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '120px'])
            ->ajax('')
            ->parameters([
                'dom' => 'frtip',
                'scrollX' => false
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'name' => ['name' => 'name', 'data' => 'name'],
//            'address' => ['name' => 'address', 'data' => 'address'],
            'email' => ['name' => 'email', 'data' => 'email'],
//            'phno' => ['name' => 'phno', 'data' => 'phno'],
//            'bill_address' => ['name' => 'bill_address', 'data' => 'bill_address'],
//            'card_stripe' => ['name' => 'card_stripe', 'data' => 'card_stripe'],
//            'logo' => ['name' => 'logo', 'data' => 'logo'],
//            'planid' => ['name' => 'planid', 'data' => 'planid'],
//            'domain_name' => ['name' => 'domain_name', 'data' => 'domain_name'],
            'global_revenue' => ['name' => 'golablrevenue', 'data' => 'golablrevenue','searchable' => 'false'],
            'Plan' => ['name' => 'plan', 'data' => 'plan','searchable' => 'false'],
            'Commission' => ['name' => 'commission', 'data' => 'commission','searchable' => 'false'],
            'plan_start' => ['name' => 'plan_start', 'data' => 'plan_start'],
            'plan_end' => ['name' => 'plan_expire', 'data' => 'plan_expire'],
            'Affiliates' => ['name' => 'affiliates', 'data' => 'affiliates', 'searchable' => 'false'],
//            'folder' => ['name' => 'folder', 'data' => 'folder'],
//            'activated' => ['name' => 'activated', 'data' => 'activated'],
//            'valid' => ['name' => 'valid', 'data' => 'valid'],
//            'status' => ['name' => 'status', 'data' => 'status'],
//            'apikey' => ['name' => 'apikey', 'data' => 'apikey']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'companies';
    }
}
