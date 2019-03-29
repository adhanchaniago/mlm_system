<?php

namespace App\DataTables;

use App\Models\weeklyfees;
use Form;
use Yajra\Datatables\Services\DataTable;

class weeklyfeesDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'weeklyfees.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $weeklyfees = weeklyfees::query();

        return $this->applyScopes($weeklyfees);
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
//            'company_id' => ['name' => 'company_id', 'data' => 'company_id'],
            'begining_date' => ['name' => 'begining_date', 'data' => 'begining_date'],
            'end_date' => ['name' => 'end_date', 'data' => 'end_date'],
            'amount' => ['name' => 'amount', 'data' => 'amount']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'weeklyfees';
    }
}
