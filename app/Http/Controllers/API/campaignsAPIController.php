<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatecampaignsAPIRequest;
use App\Http\Requests\API\UpdatecampaignsAPIRequest;
use App\Models\bot;
use App\Models\botCampaign;
use App\Models\campaigns;
use App\Models\categoryCampaign;
use App\Models\company;
use App\Models\favorite;
use App\Repositories\campaignsRepository;
use App\Models\AppUsers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class campaignsAPIController extends AppBaseController
{
    private $campaignsRepository;

    public function bot_lists(Request $request){
        $campaign_id = $request->campaign_id;
        if(empty($campaign_id)){
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "campaign id is required.";
            return $data;
        }
        $botCampaigns = botCampaign::where('campaign_id',$campaign_id)->get();
        if(empty($botCampaigns) || $botCampaigns->count() == 0){
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "no bots found for this campaign.";
            return $data;
        }else{
            foreach ($botCampaigns as $botCampaign){
                if(bot::where('bot_id',$botCampaign->bot_id)->exists()){
                    $bot = bot::where('bot_id',$botCampaign->bot_id)->first();
                    $res['bot_id'] = $botCampaign->bot_id;
                    $res['campaign_id'] = $botCampaign->campaign_id;
                    $res['bot_name'] = $bot->bot_name;
                    $res['bot_type'] = $bot->bot_type;
                }else{
                    $res['bot_id'] = $botCampaign->bot_id;
                    $res['campaign_id'] = $botCampaign->campaign_id;
                    $res['bot_name'] = "";
                    $res['bot_type'] = "";
                }
                $data1[]   = $res;
            }
            $data['success'] = true;
            $data['data']    = $data1;
            $data['message'] = "success";
            return $data;
        }
    }

    public function make_campaign(Request $request){
        $company_id         = $request->company_id;
        $campaign_name      = $request->campaign_name;
        $campaign_title     = $request->campaign_title;
        $campaign_link      = $request->campaign_link;
        $campaign_category  = $request->category_id;
        $campaign_bot       = $request->campaign_bot;
        if(empty($company_id) || empty($campaign_name) || empty($campaign_title) || empty($campaign_link)) {
            if (empty($company_id)) {
                $data['message'] = "company_id required";
            }
            if (empty($campaign_name)) {
                $data['message'] = "campaign_name required";
            }
            if (empty($campaign_title)) {
                $data['message'] = "campaign_title required";
            }
            if (empty($campaign_link)) {
                $data['message'] = "campaign_link required";
            }
            $data['success'] = false;
            $data['data']    = "";
            return $data;
        }
        if ($request->hasFile('campaign_image')) {
	        $extension = $request->file('campaign_image')->getClientOriginalExtension();
	        if($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg' || $extension == 'PNG' || $extension == 'svg'){
                $image = $request->file('campaign_image');
                $name = time() . $request->file('campaign_image')->getClientOriginalName();
                $mime = $request->file('campaign_image')->getClientOriginalExtension();

                $this->compress($request->file('campaign_image'), public_path('campaign_images') . '/' . $name, 100, $mime);
                $destinationPath = public_path('campaign_images');
                $campaign_image = $name;
            }
            else
            {
	            $data['success'] = false;;
	            $data['data'] = "";
	            $data['message'] = "Campaign image should be valid image type.";
	            return $data;
            }
        }else{
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
        if($campaigns){
            if(!empty($campaign_bot)) {
                $myArray = explode(',', $campaign_bot);
                foreach ($myArray as $bots) {
                    $botInput = [
                        'bot_id' => $bots,
                        'campaign_id' => $campaigns->id
                    ];
                    botCampaign::create($botInput);
                }
            }
            $catName = DB::table('categories')->where('id',$campaign_category)->first();
	        $campaigns['category_name'] = $catName->category_name;
            $data['success'] = true;
            $data['data']    = $campaigns;
            $data['message'] = "success";
        }
        else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "something went wrong, we couldn't save your campaign";
        }
        return $data;
    }

    public function update_campaign(Request $request){
        $campaign_id        = $request->campaign_id;
        $company_id         = $request->company_id;
        $campaign_name      = $request->campaign_name;
        $campaign_title     = $request->campaign_title;
        $campaign_link      = $request->campaign_link;
        $campaign_category  = $request->category_id;
        $campaign_bot       = $request->campaign_bot;

        if(campaigns::whereId($campaign_id)->exists()){
            $campaigns = campaigns::whereId($campaign_id)->first();
            if(empty($company_id) || empty($campaign_name) || empty($campaign_title) || empty($campaign_link)) {
                if (empty($company_id)) {
                    $data['message'] = "company_id required";
                }
                if (empty($campaign_name)) {
                    $data['message'] = "campaign_name required";
                }
                if (empty($campaign_title)) {
                    $data['message'] = "campaign_title required";
                }
                if (empty($campaign_link)) {
                    $data['message'] = "campaign_link required";
                }
                if (empty($campaign_bot)) {
                    $data['message'] = "campaign_bot required";
                }
                $data['success'] = false;
                $data['data']    = "";
                return $data;
            }
            if ($request->hasFile('campaign_image')) {
	            $extension = $request->file('campaign_image')->getClientOriginalExtension();
	            if($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg' || $extension == 'PNG' || $extension == 'svg'){
                    $filepath = public_path('campaign_images' . '/' . $campaigns->campaign_image);
                    $this->UnlinkImage($filepath);
                    $image = $request->file('campaign_image');
                    $name = time() . $request->file('campaign_image')->getClientOriginalName();
                    $destinationPath = public_path('campaign_images');
                    $mime = $request->file('campaign_image')->getClientOriginalExtension();

                    $this->compress($request->file('campaign_image'), public_path('campaign_images') . '/' . $name, 100, $mime);
                    $campaign_image = $name;
                }
                else
                {
	                $data['success'] = false;;
	                $data['data'] = "";
	                $data['message'] = "Campaign image should be valid image type.";
	                return $data;
                }
            }else{
                $campaign_image = $campaigns->campaign_image;
            }
            $campInput = [
                'company_id'        => $company_id,
                'campaign_name'     => $campaign_name,
                'campaign_title'    => $campaign_title,
                'campaign_link'     => $campaign_link,
                'campaign_category' => $campaign_category,
                'campaign_image'    => $campaign_image
            ];
            $campaigns = campaigns::whereId($campaign_id)->update($campInput);
            if($campaigns){
                if(!empty($campaign_bot)) {
                    $myArray = explode(',', $campaign_bot);
                    foreach ($myArray as $bots) {
                        $botInput = [
                            'bot_id' => $bots,
                            'campaign_id' => $campaign_id
                        ];
                        botCampaign::create($botInput);
                    }
                }
                $catName = DB::table('categories')->where('id',$campaign_category)->first();
                $campaigns['category_name'] = $catName->category_name;
                $data['success'] = true;
                $data['data']    = $campaigns;
                $data['message'] = "success";
            }else{
                $data['success'] = false;
                $data['data']    = "";
                $data['message'] = "something went wrong, we couldn't save your campaign";
            }
        }else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "Campaign doesn't exist.";
        }
        return $data;
    }

    public function delete_campaign(Request $request){
        $campaign_id = $request->campaign_id;
        if(campaigns::whereId($campaign_id)->exists()) {
            campaigns::whereId($campaign_id)->delete();
            botCampaign::where('campaign_id',$campaign_id)->delete();
            $data['success'] = true;
            $data['data']    = "";
            $data['message'] = "success";
        }else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "Campaign id doesn't exist.";
        }
        return $data;
    }

    public function add_click(Request $request){
        $campaign_id = $request->campaign_id;
        if(empty($campaign_id)){
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "Campaign id required";
            return $data;
        }
        if(campaigns::whereId($campaign_id)->exists()) {
            $campaign = campaigns::whereId($campaign_id)->first();
            if($campaign->campaign_clicks == "" || $campaign->campaign_clicks == 0){
                $click_count = 1;
            }else {
                $click_count = $campaign->campaign_clicks + 1;
            }
            $upd = campaigns::whereId($campaign_id)->update(['campaign_clicks'=>$click_count]);
            $res['category_id'] = $campaign->campaign_category;
            if($upd){
                $data['success'] = true;
                $data['data']    = $res;
                $data['message'] = "success";
            }else{
                $data['success'] = false;
                $data['data']    = "";
                $data['message'] = "Something went wrong.couldn't add click.";
            }
        }else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "Campaign not found.";
        }
        return $data;
    }

    public function add_view(Request $request){
        $campaign_id = $request->campaign_id;
        if(empty($campaign_id)){
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "Campaign id required";
            return $data;
        }
        if(campaigns::whereId($campaign_id)->exists()) {
            $campaign = campaigns::whereId($campaign_id)->first();
            if($campaign->campaign_views == "" || $campaign->campaign_views == 0){
                $view_count = 1;
            }else {
                $view_count = $campaign->campaign_views + 1;
            }
            $upd = campaigns::whereId($campaign_id)->update(['campaign_views'=>$view_count]);
            $upd['category_id'] = $campaign->campaign_category;
            if($upd){
                $data['success'] = true;
                $data['data']    = $upd;
                $data['message'] = "success";
            }else{
                $data['success'] = false;
                $data['data']    = "";
                $data['message'] = "Something went wrong.couldn't add click.";
            }
            return $data;
        }else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "Campaign not found.";
        }
    }

    public function campaigns_list(Request $request){
        $company_id = $request->company_id;
        if(campaigns::where('company_id',$company_id)->exists()){
            $campaign_details = campaigns::where('company_id',$company_id)->get();
            $d=[];
            foreach ($campaign_details as $details){
                $campaign_bot = botCampaign::where('campaign_id',$details['campaign_id'])->get();
                $campaign_bot_count = count($campaign_bot);
                $data1['campaign_id']        = $details['campaign_id'];
                $data1['campaign_name']      = $details['campaign_name'];
                $data1['campaign_title']     = $details['campaign_title'];
                $data1['campaign_link']      = $details['campaign_link'];
                $data1['campaign_category']  = $details['campaign_category'];
                $data1['campaign_views']     = $details['campaign_views'];
                $data1['campaign_clicks']    = $details['campaign_clicks'];
                $data1['campaign_bot']       = $campaign_bot_count;
                $catName= DB::table('categories')->whereId($campaign_details->campaign_category)->first();
                $data1['category_name']= $catName->category_name;
	            $data1['campaign_image'] = $details['campaign_image'];
	            $d[] = $data1;
            }
            $data['success'] = true;
            $data['data']    = $d;
            $data['message'] = "success";
        }else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "campaign for this company not found.";
        }
        return $data;
    }

    public function campaign_details(Request $request){
        $campaign_id = $request->campaign_id;
        if(campaigns::whereId($campaign_id)->exists()){
            $campaign = campaigns::whereId($campaign_id)->first();
            $data1['campaign_name']     = $campaign['campaign_name'];
            $data1['campaign_title']    = $campaign['campaign_title'];
            $data1['campaign_image']    = $campaign['campaign_image'];
            $data1['campaign_link']     = $campaign['campaign_link'];
            $catName = DB::table('categories')->where('id',$campaign['campaign_category'])->first();
            $data1['category_name'] = $catName->category_name;

            $campaign_bot = botCampaign::where('campaign_id',$campaign_id)->get();
            foreach ($campaign_bot as $campBots){
                $bots = bot::where('bot_id',$campBots['campaign_bot'])->first();
                $data1['campaign_bot '] = $bots['bot_name'];
            }
            $data['success'] = true;
            $data['data']    = $data1;
            $data['message'] = "campaign for this company not found.";
        }else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "campaign not found.";
        }
        return $data;
    }

    public function campaign_display(Request $request){
        $bot_id       = $request->bot_id;
        $bot_campaign = botCampaign::where('bot_id',$bot_id)->whereNotNull('campaign_id')->first();
        if($bot_campaign){
            $campaign_id  = $bot_campaign['campaign_id'];
            $camp_details = campaigns::whereId($campaign_id)->first();
            $catName = DB::table('categories')->where('id',$camp_details['campaign_category'])->first();
            $company = company::where('company_id',$camp_details['company_id'])->first();
            $data1['campaign_title']    = $camp_details['campaign_title'];
            $data1['campaign_image']    =  $camp_details['campaign_image'];
            $data1['campaign_link']     = $camp_details['campaign_link'];
            $data1['campaign_category'] = $camp_details['campaign_category'];
            $data1['category_name']     = $catName->category_name;
            $data1['company_id']        = $company['company_id'];
            $data1['company_FB']        = $company['company_FB'];
            $data1['company_IG']        = $company['company_IG'];
            $data1['company_LI']        = $company['company_LI'];
            $data['success'] = true;
            $data['data']    = $data1;
            $data['message'] = "matched";
        }
        else{
            $data['success'] = false;
            $data['data']    = "";
            $data['message'] = "campaign not found.";
        }
        return $data;
    }

    public function __construct(campaignsRepository $campaignsRepo)
    {
        $this->campaignsRepository = $campaignsRepo;
    }
    public function index(Request $request)
    {
        $this->campaignsRepository->pushCriteria(new RequestCriteria($request));
        $this->campaignsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $campaigns = $this->campaignsRepository->all();

        return $this->sendResponse($campaigns->toArray(), 'Campaigns retrieved successfully');
    }
    public function store(CreatecampaignsAPIRequest $request)
    {
        $input = $request->all();

        $campaigns = $this->campaignsRepository->create($input);

        return $this->sendResponse($campaigns->toArray(), 'Campaigns saved successfully');
    }
    public function show($id)
    {
        /** @var campaigns $campaigns */
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            return $this->sendError('Campaigns not found');
        }

        return $this->sendResponse($campaigns->toArray(), 'Campaigns retrieved successfully');
    }
    public function update($id, UpdatecampaignsAPIRequest $request)
    {
        $input = $request->all();
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            return $this->sendError('Campaigns not found');
        }
        $campaigns = $this->campaignsRepository->update($input, $id);
        return $this->sendResponse($campaigns->toArray(), 'campaigns updated successfully');
    }
    public function destroy($id)
    {
        $campaigns = $this->campaignsRepository->findWithoutFail($id);

        if (empty($campaigns)) {
            return $this->sendError('Campaigns not found');
        }
        $campaigns->delete();
        return $this->sendResponse($id, 'Campaigns deleted successfully');
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
