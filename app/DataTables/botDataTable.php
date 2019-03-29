<?php

namespace App\DataTables;

use App\Models\bot;
use Form;
use Yajra\Datatables\Services\DataTable;

class botDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'bots.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $bots = bot::query();

        return $this->applyScopes($bots);
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
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
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
            'bot_id' => ['name' => 'bot_id', 'data' => 'bot_id'],
            'company_id' => ['name' => 'company_id', 'data' => 'company_id'],
            'beacon_UUID' => ['name' => 'beacon_UUID', 'data' => 'beacon_UUID'],
            'bot_name' => ['name' => 'bot_name', 'data' => 'bot_name'],
            'bot_type' => ['name' => 'bot_type', 'data' => 'bot_type']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'bots';
    }
}
