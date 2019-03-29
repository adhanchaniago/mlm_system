<?php

namespace App\DataTables;

use App\Models\level;
use Form;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Services\DataTable;

class levelDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'levels.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $levels = level::where('company_id',Auth::user()->typeid)->orderby('id');

        return $this->applyScopes($levels);
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
            'level' => ['name' => 'Level', 'data' => 'level'],
            'share_to_team_revenue' => ['name' => 'share_to_team_revenue', 'data' => 'share_to_team_revenue']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'levels';
    }
}
