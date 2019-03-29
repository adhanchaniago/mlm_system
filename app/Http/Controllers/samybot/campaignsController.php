<?php

namespace App\Http\Controllers\samybot;

use App\Http\Controllers\Controller;
use App\DataTables\campaignsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatecampaignsRequest;
use App\Http\Requests\UpdatecampaignsRequest;
use App\Models\bot;
use App\Models\category;
use App\Models\campaigns;
use App\Models\company;
use App\Models\botCampaign;
use App\Models\categoryCampaign;
use App\Repositories\campaignsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Illuminate\Support\Facades\Validator;
use DB;

class campaignsController extends Controller
{
    /** @var  campaignsRepository */
    private $campaignsRepository;

    public function __construct(campaignsRepository $campaignsRepo)
    {
        $this->middleware('auth');
        $this->campaignsRepository = $campaignsRepo;
    }

    public function campaign(){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $id= Auth::user()->company_id;
        $company = company::whereId($id)->first();
        $plans = DB::table('bot_plans')->where('company_id',$id)->where('payment_status',1)->first();
        $simple_plans = DB::table('bot_plans')->where('company_id',$id)->first();
        if (Auth::user()->status == '0')
        {
            return view('home');
        }
        elseif (Auth::user()->activated != 1)
        {
            return redirect('confirmEmail');
        }
        elseif($company->bot_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->profile != 1)
        {
            return redirect('myProfile');
        }
        elseif(Auth::user()->status != '0' && empty($simple_plans))
        {
            Flash::error(trans('plan.purchase_required'));
            return redirect('samybot/plans');
        }
        elseif (Auth::user()->status != '0' &&  Auth::user()->samy_bot == 1 && empty($plans))
        {
            if(Auth::user()->status != '4'){
                $aff = affiliate::whereId(Auth::user()->affiliate_id)->first();
                if(!empty($aff->invitee)){
                    $inv_usr = User::whereId($aff->invitee)->first();
                    Flash::error(trans('plan.payment_required')."<a href='".url('samybot/plan?affiliate_id='.$inv_usr->affiliate_id)."'>".trans('plan.click_here')."</a>");
                    return redirect('payment');
                }
            }
            Flash::error(trans('plan.payment_required')."<a href='".url('samybot/plans')."'>".trans('plan.click_here')."</a>");
            return redirect('payment');
        }
        elseif(Auth::user()->status == '1' && Auth::user()->samy_bot == 0)
        {
            Flash::error(trans('plan.purchase_required'));
            return redirect('samybot/plans');
        }
        elseif(Auth::user()->status == '1' || Auth::user()->status == '4')
        {
            if(DB::table('bot_plans')->where('company_id',$id)->where('payment_status',1)->exists()){
                $campaigns = campaigns::where('company_id',$id)->get();
                $categories = category::get();
                return view('samybot.campaigns',compact('campaigns','categories'));
            }else{
                Flash::error(trans('plan.purchase_required'));
                return redirect('samybot/plans');
            }
        }
    }

    public function new_campaign(){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $id= Auth::user()->company_id;
        $company = company::whereId($id)->first();
        $plans = DB::table('bot_plans')->where('company_id',$id)->where('payment_status',1)->first();
        $simple_plans = DB::table('bot_plans')->where('company_id',$id)->first();
        if (Auth::user()->status == '0')
        {
            return view('home');
        }
        elseif (Auth::user()->activated != 1)
        {
            return redirect('confirmEmail');
        }
        elseif($company->bot_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->profile != 1)
        {
            return redirect('myProfile');
        }
        elseif(Auth::user()->status != '0' && empty($simple_plans))
        {
            Flash::error(trans('plan.purchase_required'));
            return redirect('samybot/plans');
        }
        elseif (Auth::user()->status != '0' &&  Auth::user()->samy_bot == 1 && empty($plans))
        {
            if(Auth::user()->status != '4'){
                $aff = affiliate::whereId(Auth::user()->affiliate_id)->first();
                if(!empty($aff->invitee)){
                    $inv_usr = User::whereId($aff->invitee)->first();
                    Flash::error(trans('plan.payment_required')."<a href='".url('samybot/plan?affiliate_id='.$inv_usr->affiliate_id)."'>".trans('plan.click_here')."</a>");
                    return redirect('payment');
                }
            }
            Flash::error(trans('plan.payment_required')."<a href='".url('samybot/plans')."'>".trans('plan.click_here')."</a>");
            return redirect('payment');
        }
        else{
            $id= Auth::user()->company_id;
            $bots = bot::where('company_id',$id)->where('bot_type','idle')->get();
            $categories = category::get();
            return view('samybot.new_campaigns',compact('bots','categories'));
        }
    }

    public function create_campaign(Request $request)
    {
        $id= Auth::user()->company_id;
        $validator = Validator::make($request->all(), [
            'heading'     => 'required',
            'title'       => 'required',
            'link'        => 'required',
            'category'    => 'required',
            'campaign_id' => 'required'
        ]);
        if ($validator->fails()) {
            flash("All the Fields are Required.")->error();
            return redirect('samybot/new_campaign');
        }
        $company_id         = $id;
        $campaign_name      = $request->heading;
        $campaign_category  = $request->category;
        $campaign_title     = $request->title;
        $campaign_link      = $request->link;
        $campaign_bot       = $request->bots;
        if ($request->hasFile('campaign_image')) {
            $validator=Validator::make($request->all(), [
                'campaign_image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'campaign_image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes()) {
                $name = time() . $request->file('campaign_image')->getClientOriginalName();
                $mime = $request->file('campaign_image')->getClientOriginalExtension();

                $this->compress($request->file('campaign_image'), public_path('campaign_images') . '/' . $name, 100, $mime);
                $campaign_image = $name;
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        } else {
            $campaign_image = "default.png";
        }
        $campInput = [
            'company_id'        => $company_id,
            'campaign_name'     => $campaign_name,
            'campaign_title'    => $campaign_title,
            'campaign_link'     => $campaign_link,
            'campaign_image'    => $campaign_image,
            'campaign_category' => $campaign_category,
        ];
        $campaigns = campaigns::create($campInput);
        $campaign_id = $campaigns->id;
        if ($campaigns) {
            if(!empty($campaign_bot)){
                foreach ($campaign_bot as $bots) {
                    $botInput = [
                        'bot_id' => $bots,
                        'campaign_id' => $campaign_id
                    ];
                    botCampaign::create($botInput);
                    bot::where('bot_id',$bots)->update(['bot_type'=>'release']);
                }
            }
            flash('for your campaign to be available on other apps than samy, please download the samy campaign manager, edit your campaign and launch it again. The app will be able to add code to your samy bot directly!');
            return redirect('samybot/campaigns');
        }else{
            flash('Something Went Wrong! Try Again.')->error();
            return redirect('samybot/new_campaign');
        }
    }

    public function update_campaign(Request $request){
        $id = $request->id;
        $old_capaign       = campaigns::whereId($id)->first();
        $campaign_name     = $request->heading;
        $campaign_title    = $request->title;
        $campaign_link     = $request->link;
        $campaign_category = $request->category;
        if ($request->hasFile('campaign_image')) {
            $validator=Validator::make($request->all(), [
                'campaign_image' => 'mimes:jpg,png,gif,jpeg,PNG,svg',
            ],
                [
                    'campaign_image.mimes' => trans('auth.only_image'),
                ]
            );
            if ($validator->passes()) {
                $filepath = public_path('campaign_images' . '/' . $old_capaign->campaign_image);
                $this->UnlinkImage($filepath);
                $name = time() . $request->file('campaign_image')->getClientOriginalName();
                $mime = $request->file('campaign_image')->getClientOriginalExtension();

                $this->compress($request->file('campaign_image'), public_path('campaign_images') . '/' . $name, 100, $mime);
                $campaign_image = $name;
                $campInput = [
                    'campaign_name'     => $campaign_name,
                    'campaign_title'    => $campaign_title,
                    'campaign_link'     => $campaign_link,
                    'campaign_image'    => $campaign_image,
                    'campaign_category' => $campaign_category,
                ];
            }
            else
            {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        } else {
            $campInput = [
                'campaign_name'     => $campaign_name,
                'campaign_title'    => $campaign_title,
                'campaign_link'     => $campaign_link,
                'campaign_category' => $campaign_category,
            ];
        }
        $campaigns = campaigns::whereId($id)->update($campInput);
        if ($campaigns) {
            return redirect('samybot/campaigns');
        }
    }

    public function saveBot($botId,$campId){
        $Input = [
            'bot_id' => $botId,
            'campaign_id' => $campId
        ];
        botCampaign::create($Input);
        bot::where('bot_id',$botId)->update(['bot_type'=>'release']);
        return "success";
    }

    public function releaseBot($botId,$campId){
        if(botCampaign::where('bot_id', $botId)->where('campaign_id' , $campId)->exists()){
            botCampaign::where('bot_id', $botId)->where('campaign_id' , $campId)->forcedelete();
            bot::where('bot_id',$botId)->update(['bot_type'=>'idle']);
        }
        return "success";
    }

    public function samy_bots(){
        $domain = request()->getHost();
        if (Auth::user()->status == '4' && $domain != ''.env('APP_DOMAIN').'' )
        {
            return redirect('home');
        }
        $id= Auth::user()->company_id;
        $company = company::whereId($id)->first();
        $plans = DB::table('bot_plans')->where('company_id',$id)->where('payment_status',1)->first();
        $simple_plans = DB::table('bot_plans')->where('company_id',$id)->first();
        if (Auth::user()->status == '0')
        {
            return view('home');
        }
        elseif (Auth::user()->activated != 1)
        {
            return redirect('confirmEmail');
        }
        elseif($company->bot_disabled == 1)
        {
            return view('frontEnd.disabled');
        }
        elseif (Auth::user()->profile != 1)
        {
            return redirect('myProfile');
        }
        elseif(Auth::user()->status != '0' && empty($simple_plans))
        {
            Flash::error(trans('plan.purchase_required'));
            return redirect('samybot/plans');
        }
        elseif (Auth::user()->status != '0' &&  Auth::user()->samy_bot == 1 && empty($plans))
        {
            if(Auth::user()->status != '4'){
                $aff = affiliate::whereId(Auth::user()->affiliate_id)->first();
                if(!empty($aff->invitee)){
                    $inv_usr = User::whereId($aff->invitee)->first();
                    Flash::error(trans('plan.payment_required')."<a href='".url('samybot/plan?affiliate_id='.$inv_usr->affiliate_id)."'>".trans('plan.click_here')."</a>");
                    return redirect('payment');
                }
            }
            Flash::error(trans('plan.payment_required')."<a href='".url('samybot/plans')."'>".trans('plan.click_here')."</a>");
            return redirect('payment');
        }
        else {
            $id = Auth::user()->company_id;
            $bots = bot::where('company_id', $id)->get();
            return view('samybot.samy_bots', compact('bots'));
        }
    }

    public function lifetime_graph($campaign_id){
        $bots = \App\Models\botCampaign::where('campaign_id',$campaign_id)->get(); $bots_count = count($bots);
        $lifetimeSql = \App\Models\campaigns::select(
            DB::raw('sum(campaign_clicks) as clicks'),
            DB::raw('sum(campaign_views) as views'),
            DB::raw("DATE_FORMAT(created_at,'%m %Y') as months")
        )
            ->where('campaign_id',$campaign_id)
            ->groupBy('months')
            ->first();
        $emparray[] = array(
            'y' => "$lifetimeSql->months",
            'a' => $lifetimeSql->clicks,
            'b' => $lifetimeSql->views,
            'c' => $bots_count
        );
        return $emparray;
    }

    public function delete_campaign($id){
        $company= Auth::user()->company_id;
        $bots = botCampaign::where('campaign_id',$id)->get();
        if(!empty($bots)){
            foreach ($bots as $bot){
                if(bot::where('bot_id',$bot->bot_id)->where('company_id',$company)->exists()){
                    bot::where('bot_id',$bot->bot_id)->where('company_id',$company)->update(['bot_type' => 'idle']);
                }
            }
        }
        campaigns::whereId($id)->delete();
        botCampaign::where('campaign_id',$id)->forcedelete();
        categoryCampaign::where('campaign_id',$id)->forcedelete();
        flash("Deleted Successfully")->success();
        return redirect('samybot/campaigns');
    }

    public function index(campaignsDataTable $campaignsDataTable)
    {
        return $campaignsDataTable->render('campaigns.index');
    }

    /**
     * Show the form for creating a new campaigns.
     *
     * @return Response
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store a newly created campaigns in storage.
     *
     * @param CreatecampaignsRequest $request
     *
     * @return Response
     */
    public function store(CreatecampaignsRequest $request)
    {
        $input = $request->all();

        $campaigns = $this->campaignsRepository->create($input);

        Flash::success('Campaigns saved successfully.');

        return redirect(route('campaigns.index'));
    }

    /**
     * Display the specified campaigns.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            Flash::error('Campaigns not found');

            return redirect(route('campaigns.index'));
        }

        return view('campaigns.show')->with('campaigns', $campaigns);
    }

    /**
     * Show the form for editing the specified campaigns.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            Flash::error('Campaigns not found');

            return redirect(route('campaigns.index'));
        }

        return view('campaigns.edit')->with('campaigns', $campaigns);
    }

    /**
     * Update the specified campaigns in storage.
     *
     * @param  int              $id
     * @param UpdatecampaignsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecampaignsRequest $request)
    {
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            Flash::error('Campaigns not found');

            return redirect(route('campaigns.index'));
        }

        $campaigns = $this->campaignsRepository->update($request->all(), $id);

        Flash::success('Campaigns updated successfully.');

        return redirect(route('campaigns.index'));
    }

    /**
     * Remove the specified campaigns from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            Flash::error('Campaigns not found');

            return redirect(route('campaigns.index'));
        }

        $this->campaignsRepository->delete($id);

        Flash::success('Campaigns deleted successfully.');

        return redirect(route('campaigns.index'));
    }
    function compress($source, $destination, $quality,$mime) {
// Set a maximum height and width
        $width = 375;
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
