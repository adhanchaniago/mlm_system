<?php
namespace App\Http\Controllers;
use App\Http\Requests\CreaterankRequest;
use App\Http\Requests\UpdaterankRequest;
use App\Models\affiliate;
use App\Models\company;
use App\Models\plantable;
use App\Models\rank;
use App\Repositories\rankRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Image;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Validator;
use App\DataTables\rankDataTable;
class rankController extends AppBaseController
{
    /** @var  rankRepository */
    private $rankRepository;
    public function __construct(rankRepository $rankRepo)
    {
        $this->middleware('auth');
        $this->rankRepository = $rankRepo;
    }
    /**
     * Display a listing of the rank.
     *
     * @param Request $request
     * @return Response
     */
    public function index(rankDataTable $rankDataTable)
    {
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        if (Auth::user()->status == '1' || Auth::user()->status == 4)
        {
            $cid = Auth::user()->company_id;
            $company = company::whereId($cid)->first();
            $planTable = DB::table('companyAffiliatePlans')->where('company_id',$cid)->orderby('id','desc')->first();
            $plan = plantable::whereId($planTable->planid)->first();
            if ($planTable->payment == 0)
            {
                return redirect('stripe');
            }
            elseif (Auth::user()->activated==0)
            {
                return redirect('confirmEmail');
            }
            elseif(Auth::user()->affiliate_disabled==1)
            {
                return view('frontEnd.disabled');
            }
            elseif (Auth::user()->profile == 0)
            {
                return redirect('myProfile');
            }
            $ranks = rank::where('company_id',$cid)->orderby('id')->get();
            $company_rank = rank::where('company_id',$cid)->orderby('rank','desc')->first();
            if (isset($company_rank->rank))
            {
                $next_rank = (int)$company_rank->rank + 1;
            }
            else
            {
                $next_rank = 1;
            }
            $id = $cid;
            return $rankDataTable->render('ranks.index',compact('ranks','next_rank','id'));
        }
        else
        {
            return redirect('home');
        }
    }
    /**
     * Show the form for creating a new rank.
     *
     * @return Response
     */
    public function create()
    {
        $company_rank = rank::where('company_id',Auth::user()->company_id)->orderby('rank','desc')->first();
        if (isset($company_rank->rank)) {
            $next_rank = (int)$company_rank->rank + 1;
        }
        else
        {
            $next_rank = 1;
        }
        return view('ranks.create',compact('company_rank','next_rank'));
    }
    /**
     * Store a newly created rank in storage.
     *
     * @param CreaterankRequest $request
     *
     * @return Response
     */
    public function store(CreaterankRequest $request)
    {
        $cid = Auth::user()->company_id;

        $input = $request->except('image');
        if ($request->hasFile('image'))
        {
            $validator=Validator::make($request->all(), [
                'image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $photoName = rand(1, 777777777) . time() . '.' . $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientOriginalExtension();

                $this->compress($request->image, public_path('avatars') . '/' . $photoName, 100, $mime);
                $input['image'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        else
        {
            Flash::error(trans('rank.no-image'));
            return redirect()->back();
        }
        $rank = $this->rankRepository->create($input);
        Flash::success(trans('rank.saved'));
        $ranks = rank::where('company_id',$cid)->get();
        $affiliates = affiliate::where('company_id',$cid)->get();
        foreach($affiliates as $affiliate)
        {
            $this_rank = $affiliate->rankid;
            $rankid = $this->calculateRank($affiliate->id);
            if ($this_rank!=$rankid)
            {
                $update_affiliate['payout'] = 0;
            }
            $update_affiliate['rankid'] = $rankid;
            affiliate::whereId($affiliate->id)->update($update_affiliate);

        }
        return redirect(route('ranks.index'));
    }
    /**
     * Display the specified rank.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $rank = $this->rankRepository->findWithoutFail($id);
        if (empty($rank)) {
            Flash::error(trans('rank.error'));
            return redirect(route('ranks.index'));
        }
        return view('ranks.show')->with('rank', $rank);
    }
    /**
     * Show the form for editing the specified rank.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $rank = $this->rankRepository->findWithoutFail($id);
        if (empty($rank)) {
            Flash::error(trans('rank.error'));
            return redirect(route('ranks.index'));
        }
        return view('ranks.edit')->with('rank', $rank);
    }
    /**
     * Update the specified rank in storage.
     *
     * @param  int              $id
     * @param UpdaterankRequest $request
     *
     * @return Response
     */
    public function update($id, UpdaterankRequest $request)
    {
//        return $request->all();
        $update = $request->except('image','_method','_token');

        if ($request->hasFile('image'))
        {
            $validator=Validator::make($request->all(), [
                'image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes())
            {
                $filepath = public_path('avatars' . '/' . $request->image);
//            return $filepath;
                $this->UnlinkImage($filepath);
                $photoName = rand(1, 777777777) . time() . '.' . $request->image->getClientOriginalExtension();
                $mime = $request->image->getClientOriginalExtension();

                $this->compress($request->image, public_path('avatars') . '/' . $photoName, 100, $mime);

                $update['image'] = $photoName;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        rank::whereId($id)->update($update);
        $cid = Auth::user()->company_id;
        Flash::success(trans('rank.update'));
        $ranks = rank::where('company_id',$cid)->get();
        $affiliates = affiliate::where('company_id',$cid)->get();
        foreach($affiliates as $affiliate)
        {
            $this_rank = $affiliate->rankid;
            $rankid = $this->calculateRank($affiliate->id);
            if ($this_rank!=$rankid)
            {
                $update_affiliate['payout'] = 0;
            }
            $update_affiliate['rankid'] = $rankid;
            affiliate::whereId($affiliate->id)->update($update_affiliate);

        }
        return redirect(route('ranks.index'));
    }
    /**
     * Remove the specified rank from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $rank = $this->rankRepository->findWithoutFail($id);
        if (empty($rank)) {
            Flash::error(trans('rank.error'));
            return redirect(route('ranks.index'));
        }
        $this->rankRepository->delete($id);
        Flash::success(trans('rank.delete'));
        return redirect(route('ranks.index'));
    }
    function calculateRank($id)
    {
        $affiliate = affiliate::whereId($id)->first();
        $current_revenue = $affiliate->current_revenue;
        $ranks = rank::where('company_id',$affiliate->company_id)->get();
        foreach($ranks as $rank)
        {
            $current_rank = $rank->rank;
            $rank_next = $current_rank+1;
            if(rank::where('company_id',$affiliate->company_id)->where('rank',$rank_next)->exists())
            {
                $next_rank = rank::where('company_id',$affiliate->company_id)->where('rank',$rank_next)->first();
            }
            else
            {
                $next_rank = "";
            }
            if ($next_rank != "")
            {
                if ($current_revenue >= $rank->revenue_trigger && $current_revenue < $next_rank->revenue_trigger)
                {
                    $affiliate_rank = $rank->rank;
                    return $affiliate_rank;
                }
            }
            else
            {
                if ($current_revenue >= $rank->revenue_trigger)
                {
                    $affiliate_rank = $rank->rank;
                }
                else
                {
                    $affiliate_rank = 0;
                }
            }
        }
        return $affiliate_rank;
    }
    function compress($source, $destination, $quality,$mime) {



// Set a maximum height and width
        $width = 200;
        $height = 200;

// Content type
        header('Content-Type: image/'.$mime);

// Get new dimensions
        list($width_orig, $height_orig) = \getimagesize($source);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

// Resample
        $image_p = \imagecreatetruecolor($width, $height);
        $info = \getimagesize($source);

        if ($info['mime'] == 'image/jpg')
            $image = \imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/jpeg')
            $image = \imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif')
            $image = \imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png')
            $image = \imagecreatefrompng($source);


//            $image = \imagecreatefromjpeg($filename);
        \imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
        \imagejpeg($image_p, $destination, $quality);
        return $destination;
    }
    function UnlinkImage($filepath)
    {
        $old_image = $filepath;
        if (file_exists($old_image)) {
            @unlink($old_image);
        }
    }
}
