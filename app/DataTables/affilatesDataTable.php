<?php

namespace App\DataTables;

use App\Models\affilates;
use Form;
use Yajra\Datatables\Services\DataTable;

class affilatesDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'affilates.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $affilates = affilates::query();

        return $this->applyScopes($affilates);
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
            'company_id' => ['name' => 'company_id', 'data' => 'company_id'],
            'photo' => ['name' => 'photo', 'data' => 'photo'],
            'activated' => ['name' => 'activated', 'data' => 'activated'],
            'name' => ['name' => 'name', 'data' => 'name'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'phone' => ['name' => 'phone', 'data' => 'phone'],
            'invitee' => ['name' => 'invitee', 'data' => 'invitee'],
            'paypal_email' => ['name' => 'paypal_email', 'data' => 'paypal_email'],
            'rankid' => ['name' => 'rankid', 'data' => 'rankid'],
            'current_revenue' => ['name' => 'current_revenue', 'data' => 'current_revenue'],
            'past_revid' => ['name' => 'past_revid', 'data' => 'past_revid'],
            'level_p1_affiliateid' => ['name' => 'level_p1_affiliateid', 'data' => 'level_p1_affiliateid'],
            'level_m1_affiliateid' => ['name' => 'level_m1_affiliateid', 'data' => 'level_m1_affiliateid'],
            'created_at' => ['name' => 'created_at', 'data' => 'created_at'],
            'updated_at' => ['name' => 'updated_at', 'data' => 'updated_at']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'affilates';
    }
}
