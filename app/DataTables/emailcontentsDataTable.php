<?php

namespace App\DataTables;

use App\Models\emailcontents;
use Form;
use Yajra\Datatables\Services\DataTable;

class emailcontentsDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'emailcontents.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $emailcontents = emailcontents::query();

        return $this->applyScopes($emailcontents);
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
            'smtp' => ['name' => 'smtp', 'data' => 'smtp'],
            'smtp_user_id' => ['name' => 'smtp_user_id', 'data' => 'smtp_user_id'],
            'smtp_password' => ['name' => 'smtp_password', 'data' => 'smtp_password'],
            'welcome_text' => ['name' => 'welcome_text', 'data' => 'welcome_text'],
            'new_password_text' => ['name' => 'new_password_text', 'data' => 'new_password_text'],
            'new_affiliate_text' => ['name' => 'new_affiliate_text', 'data' => 'new_affiliate_text'],
            'delete_account_text' => ['name' => 'delete_account_text', 'data' => 'delete_account_text'],
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
        return 'emailcontents';
    }
}
