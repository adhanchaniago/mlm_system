<?php

namespace App\DataTables;

use App\Models\salescontent;
use Form;
use Yajra\Datatables\Services\DataTable;

class salescontentDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'salescontents.datatables_actions')
	        ->editColumn('image',function ($data){
	        	if($data->image != ""){
			        return "<img src='".asset('public/salesContents')."/".$data->image."' class='image-responsive imageSales'>";
		        }
		        else{
	        		return "";
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
        $salescontents = salescontent::query();

        return $this->applyScopes($salescontents);
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
            'id' => ['name' => 'id', 'data' => 'id'],
	        'title' => ['name' => 'title', 'data' => 'title'],
	        'content' => ['name' => 'content', 'data' => 'content'],
	        'type' => ['name' => 'type', 'data' => 'type'],
            'image' => ['name' => 'image', 'data' => 'image'],
            'video' => ['name' => 'video', 'data' => 'video']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'salescontents';
    }
}
