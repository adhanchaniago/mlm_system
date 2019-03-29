<?php

namespace App\DataTables;

use App\Models\frontPage;
use Form;
use Yajra\Datatables\Services\DataTable;

class frontPageDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'front_pages.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $frontPages = frontPage::query();

        return $this->applyScopes($frontPages);
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
//            'slider_image' => ['name' => 'slider_image', 'data' => 'slider_image'],
//            'slider_text' => ['name' => 'slider_text', 'data' => 'slider_text'],
            'aboutUs_main_description' => ['name' => 'aboutUs_main_description', 'data' => 'aboutUs_main_description'],
            'aboutUs_sub_description' => ['name' => 'aboutUs_sub_description', 'data' => 'aboutUs_sub_description'],
            'aboutUs_image' => ['name' => 'aboutUs_image', 'data' => 'aboutUs_image']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'frontPages';
    }
}
