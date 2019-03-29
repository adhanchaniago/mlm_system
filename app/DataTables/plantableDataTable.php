<?php

namespace App\DataTables;


use App\Models\plantable;
use Form;
use Yajra\Datatables\Services\DataTable;

class plantableDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'plantables.datatables_actions')
            ->editColumn('image', function($data){
                if ($data->image != '' || !empty($data->image))
                {
                    return "<img class='table-img' src='" . asset('public/avatars/') . '/' . $data->image . "'>";
                }
                else
                {
                    return "<img class='table-img' src='" . asset('public/pictures/default.jpg') . "'>";
                }

            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $plantables = plantable::query();

        return $this->applyScopes($plantables);
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
            'name' => ['name' => 'name', 'data' => 'name'],
            'amount' => ['name' => 'amount', 'data' => 'amount'],
            'term' => ['name' => 'term', 'data' => 'term'],
            'sharing_amount' => ['name' => 'sharing_amount', 'data' => 'sharing_amount'],
            'image' => ['name' => 'image', 'data' => 'image']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'plantables';
    }
}
