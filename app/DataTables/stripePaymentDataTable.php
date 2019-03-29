<?php

namespace App\DataTables;

use App\Models\stripePayment;
use Form;
use Yajra\Datatables\Services\DataTable;

class stripePaymentDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'stripe_payments.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $stripePayments = stripePayment::query();

        return $this->applyScopes($stripePayments);
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
            'payment_id' => ['name' => 'payment_id', 'data' => 'payment_id'],
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'card_number' => ['name' => 'card_number', 'data' => 'card_number'],
            'amount' => ['name' => 'amount', 'data' => 'amount'],
            'date' => ['name' => 'date', 'data' => 'date'],
            'name' => ['name' => 'name', 'data' => 'name'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'phone' => ['name' => 'phone', 'data' => 'phone'],
            'address' => ['name' => 'address', 'data' => 'address'],
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
        return 'stripePayments';
    }
}
