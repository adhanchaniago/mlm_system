<?php

namespace App\DataTables;

use App\Models\affiliate;
use Form;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Services\DataTable;
use App\Models\rank;
class affilateDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
//            ->addColumn('action', 'affiliates.datatables_actions')
            ->editColumn('photo', function($data){
                if ($data->photo != '' || !empty($data->photo))
                {
                    return "<center><img class='table-img' src='" . asset('public/avatars/') . '/' . $data->photo . "'></center>";
                }
                else
                {
                    return "<center><img class='table-img' src='" . asset('public/pictures/default.jpg') . "'></center>";
                }

            })
	        ->editColumn('name',function($data){
	        	return "<b>Name : </b>".$data->name."<br><b>Email : </b>".$data->email."<br>"."<b>Phone : </b>".$data->phone;
	        })
	        ->editColumn('current_revenue',function($data){
		        if(rank::where('rank',$data->rankid)->where('company_id',$data->company_id)->exists()){
			        $rank=rank::where('rank',$data->rankid)->where('company_id',$data->company_id)->first();
			        $name="<img class='rank-img1' src='".asset('public/avatars/').'/'.$rank->image."'>";
		        }
		        else{
			        $name="";
		        }
		        return "<b>Rank : </b>".$name."<br><b>Revenue : </b>".$data->current_revenue;
	        })
//	        ->editColumn('rankid',function ($data){
//	        	if(rank::where('rank',$data->rankid)->where('company_id',$data->company_id)->exists()){
//	        		$rank=rank::where('rank',$data->rankid)->where('company_id',$data->company_id)->first();
//	        		return $rank->name;
//		        }
//		        else{
//	        		return "";
//		        }
//	        })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (Auth::user()->status == '1')
        {
            $affilates = affiliate::where('company_id',Auth::user()->typeid);
        }
        elseif (Auth::user()->status == '2')
        {
            $affilates = affiliate::where('invitee',Auth::user()->id);
        }

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
//            ->addAction(['width' => '120px'])
            ->ajax('')
            ->parameters([
                'dom' => 'frtip',
	            "autoWidth"=> false,
                'scrollX' => false,
//                'buttons' => [
//                    'print',
//                    'reset',
//                    'reload',
//                    [
//                         'extend'  => 'collection',
//                         'text'    => '<i class="fa fa-download"></i> Export',
//                         'buttons' => [
//                             'csv',
//                             'excel',
//                             'pdf',
//                         ],
//                    ],
//                    'colvis'
//                ]
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
//            'company_id' => ['name' => 'company_id', 'data' => 'company_id'],

            'photo' => ['name' => 'photo', 'data' => 'photo','width' => '10%'],

//            'activated' => ['name' => 'activated', 'data' => 'activated'],

            'name' => ['name' => 'name', 'data' => 'name','width' => '20%'],

//            'email' => ['name' => 'email', 'data' => 'email'],

//            'phone' => ['name' => 'phone', 'data' => 'phone'],

//            'invitee' => ['name' => 'invitee', 'data' => 'invitee'],

//            'paypal_email' => ['name' => 'paypal_email', 'data' => 'paypal_email'],

//            'rank' => ['name' => 'rankid', 'data' => 'rankid'],

            'current_revenue' => ['name' => 'current_revenue', 'data' => 'current_revenue','width' => '30%'],

//            'past_revid' => ['name' => 'past_revid', 'data' => 'past_revid'],

//            'level_p1_affiliateid' => ['name' => 'level_p1_affiliateid', 'data' => 'level_p1_affiliateid'],

//            'level_m1_affiliateid' => ['name' => 'level_m1_affiliateid', 'data' => 'level_m1_affiliateid']
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
