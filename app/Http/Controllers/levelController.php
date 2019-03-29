<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreatelevelRequest;
use App\Http\Requests\UpdatelevelRequest;
use App\Models\company;
use App\Models\plantable;
use App\Repositories\levelRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\DataTables\levelDataTable;
use App\Models\level;
class levelController extends AppBaseController
{
    /** @var  levelRepository */
    private $levelRepository;
    public function __construct(levelRepository $levelRepo)
    {
        $this->levelRepository = $levelRepo;
    }
    /**
     * Display a listing of the level.
     *
     * @param Request $request
     * @return Response
     */
    public function index(levelDataTable $levelDataTable)
    {
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        if (Auth::user()->status == '1' || Auth::user()->status == 4) {
            $id = Auth::user()->company_id;
            $planTable = DB::table('companyAffiliatePlans')->where('company_id',$id)->orderby('id','desc')->first();
            $company = company::whereId($id)->first();
            if ($planTable->payment == 0)
            {
                return redirect('stripe');
            }
            elseif (Auth::user()->activated==0)
            {
                return redirect('confirmEmail');
            }
            elseif($company->affiliate_disabled==1)
            {
                return view('frontEnd.disabled');
            }
            elseif (Auth::user()->profile == 0)
            {
                return redirect('myProfile');
            }

            $plan = plantable::whereId($planTable->planid)->first();
            $max_level = $plan->levels;
            $max_affiliates = $plan->affiliates;
            $level_count = level::where('company_id',$id)->count();
            $levels = level::where('company_id',$id)->orderby('id')->get();
            $levels2 = level::where('company_id',$id)->orderby('id','desc')->first();
            if (isset($levels2->level))
            {
                $last_level = (int)$levels2->level;
            }
            else
            {
                $last_level = 0;
            }
            $next_level = $last_level + 1;
            return $levelDataTable->render('levels.index',compact('level_count','levels','next_level','max_level','max_affiliates','id'));
        }
        else
        {
            return redirect('home');
        }
//        $this->levelRepository->pushCriteria(new RequestCriteria($request));
//        $levels = $this->levelRepository->all();
//
//        return view('levels.index')
//            ->with('levels', $levels);
    }
    /**
     * Show the form for creating a new level.
     *
     * @return Response
     */
    public function create()
    {
        $id = Auth::user()->company_id;
        $levels = level::where('company_id',$id)->orderby('id','desc')->first();

        if (isset($levels->level))
        {
            $last_level = (int)$levels->level;
        }
        else
        {
            $last_level = 0;
        }
        $next_level = $last_level + 1;
        return view('levels.create',compact('levels','last_level','next_level'));
    }
    /**
     * Store a newly created level in storage.
     *
     * @param CreatelevelRequest $request
     *
     * @return Response
     */
    public function store(CreatelevelRequest $request)
    {
        $input = $request->all();
        $level = $this->levelRepository->create($input);
        Flash::success(trans('level.saved'));
        return redirect(route('levels.index'));
    }
    /**
     * Display the specified level.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $level = $this->levelRepository->findWithoutFail($id);
        if (empty($level)) {
            Flash::error(trans('level.error'));
            return redirect(route('levels.index'));
        }
        return view('levels.show')->with('level', $level);
    }
    /**
     * Show the form for editing the specified level.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $level = $this->levelRepository->findWithoutFail($id);
        if (empty($level)) {
            Flash::error(trans('level.error'));
            return redirect(route('levels.index'));
        }
        return view('levels.edit')->with('level', $level);
    }
    /**
     * Update the specified level in storage.
     *
     * @param  int              $id
     * @param UpdatelevelRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatelevelRequest $request)
    {
        $level = $this->levelRepository->findWithoutFail($id);
        if (empty($level)) {
            Flash::error(trans('level.error'));
            return redirect(route('levels.index'));
        }
        $level = $this->levelRepository->update($request->all(), $id);
        Flash::success(trans('level.update'));
        return redirect(route('levels.index'));
    }
    /**
     * Remove the specified level from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $level = $this->levelRepository->findWithoutFail($id);
        if (empty($level)) {
            Flash::error(trans('level.error'));
            return redirect(route('levels.index'));
        }
        $this->levelRepository->forcedelete($id);
        Flash::success(trans('level.delete'));
        return redirect(route('levels.index'));
    }
    public function deleteLevel()
    {
        $id= Auth::user()->company_id;
        level::where('company_id',$id)->orderby('id','desc')->limit(1)->forcedelete();
        Flash::success(trans('level.delete'));
        return redirect(route('levels.index'));
    }
}
