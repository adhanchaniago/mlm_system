<?php

namespace App\DataTables;

use App\Models\rank;
use Form;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Services\DataTable;

class rankDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'ranks.datatables_actions')
            ->editColumn('image', function($data){
                return "<img class='table-img' src='".asset('public/avatars/').'/'.$data->image."'>";
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
        $ranks = rank::where('company_id',Auth::user()->typeid)->orderby('rank');

        return $this->applyScopes($ranks);
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
            'image' => ['name' => 'image', 'data' => 'image'],
            'revenue_trigger' => ['name' => 'revenue_trigger', 'data' => 'revenue_trigger'],
            'payout_amount' => ['name' => 'payout_amount', 'data' => 'payout_amount']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ranks';
    }
}
