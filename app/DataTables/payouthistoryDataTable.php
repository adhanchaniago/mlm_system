<?php

namespace App\DataTables;

use App\Models\payouthistory;
use Form;
use Yajra\Datatables\Services\DataTable;

class payouthistoryDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'payouthistories.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $payouthistories = payouthistory::query();

        return $this->applyScopes($payouthistories);
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
            'affiliate_id' => ['name' => 'affiliate_id', 'data' => 'affiliate_id'],
            'month' => ['name' => 'month', 'data' => 'month'],
            'year' => ['name' => 'year', 'data' => 'year'],
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
        return 'payouthistories';
    }
}
