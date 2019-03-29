<?php

namespace App\DataTables;

use App\Models\companies;
use Form;
use Yajra\Datatables\Services\DataTable;

class companiesDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'companies.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $companies = companies::query();

        return $this->applyScopes($companies);
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
            'name' => ['name' => 'name', 'data' => 'name'],
            'address' => ['name' => 'address', 'data' => 'address'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'phno' => ['name' => 'phno', 'data' => 'phno'],
            'bill_address' => ['name' => 'bill_address', 'data' => 'bill_address'],
            'card_stripe' => ['name' => 'card_stripe', 'data' => 'card_stripe'],
            'logo' => ['name' => 'logo', 'data' => 'logo'],
            'planid' => ['name' => 'planid', 'data' => 'planid'],
            'domain_name' => ['name' => 'domain_name', 'data' => 'domain_name'],
            'folder' => ['name' => 'folder', 'data' => 'folder'],
            'activated' => ['name' => 'activated', 'data' => 'activated'],
            'valid' => ['name' => 'valid', 'data' => 'valid'],
            'status' => ['name' => 'status', 'data' => 'status'],
            'apikey' => ['name' => 'apikey', 'data' => 'apikey'],
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
        return 'companies';
    }
}
