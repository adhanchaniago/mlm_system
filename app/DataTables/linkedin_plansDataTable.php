<?php

namespace App\DataTables;

use App\Models\linkedin_plans;
use Form;
use Yajra\Datatables\Services\DataTable;

class linkedin_plansDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'linkedin_plans.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $linkedinPlans = linkedin_plans::query();

        return $this->applyScopes($linkedinPlans);
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
                'scrollX' => false,
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
            'Name' => ['name' => 'name', 'data' => 'name'],
            'Amount' => ['name' => 'amount', 'data' => 'amount'],
            'Type' => ['name' => 'type', 'data' => 'type'],
            'Term' => ['name' => 'term', 'data' => 'term'],
            'campaigns' => ['name' => 'campaigns', 'data' => 'campaigns'],
            'contacts' => ['name' => 'contacts', 'data' => 'contacts'],
            'linkedIn_accounts' => ['name' => 'linkedIn_accounts', 'data' => 'linkedIn_accounts'],
            'Automated Message' => ['name' => 'automated_msg', 'data' => 'automated_msg'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'linkedinPlans';
    }
}
