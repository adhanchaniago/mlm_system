<?php

namespace App\DataTables;

use App\Models\campaigns;
use Form;
use Yajra\Datatables\Services\DataTable;

class campaignsDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'campaigns.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $campaigns = campaigns::query();

        return $this->applyScopes($campaigns);
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
            'campaign_id' => ['name' => 'campaign_id', 'data' => 'campaign_id'],
            'company_id' => ['name' => 'company_id', 'data' => 'company_id'],
            'campaign_name' => ['name' => 'campaign_name', 'data' => 'campaign_name'],
            'campaign_title' => ['name' => 'campaign_title', 'data' => 'campaign_title'],
            'campaign_image' => ['name' => 'campaign_image', 'data' => 'campaign_image'],
            'campaing_link' => ['name' => 'campaing_link', 'data' => 'campaing_link'],
            'campaigns_views' => ['name' => 'campaigns_views', 'data' => 'campaigns_views'],
            'campaign_clicks' => ['name' => 'campaign_clicks', 'data' => 'campaign_clicks']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'campaigns';
    }
}
