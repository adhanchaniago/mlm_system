<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatefavoriteAPIRequest;
use App\Http\Requests\API\UpdatefavoriteAPIRequest;
use App\Models\favorite;
use App\Models\AppUsers;
use App\Models\campaigns;
use App\Repositories\favoriteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;

/**
 * Class favoriteController
 * @package App\Http\Controllers\API
 */

class favoriteAPIController extends AppBaseController
{
//    private $favoriteRepository;

    public function make_favorite(Request $request)
    {
        $user_id = $request->user_id;
        $campaign_id = $request->campaign_id;
        $is_favoiurite  = $request->is_favoiurite;
        if (empty($user_id)) {
            $data['success'] = false;
            $data['data'] = "";
            $data['message'] = "User id is required";
            return $data;
        }
        if (empty($campaign_id)) {
            $data['success'] = false;
            $data['data'] = "";
            $data['message'] = "Campaign id is required";
            return $data;
        }
        if($is_favoiurite == "true" || $is_favoiurite === true){
            if (favorite::where('campaign_id', $campaign_id)->where('user_id', $user_id)->exists()) {
                $data['success'] = false;
                $data['data'] = "";
                $data['message'] = "You already made it favorite";
            } else {
                $favInput = [
                    'user_id' => $user_id,
                    'campaign_id' => $campaign_id,
                ];
                $fav = favorite::create($favInput);
                $campaigns = campaigns::where('campaign_id', $campaign_id)->first();
                $company = $campaigns->company_id;
                $fav_user = DB::table('fav_user_list')->where('user_id', $user_id)->where('company_id', $company)->first();
                if (empty($fav_user)) {
                    DB::table('fav_user_list')->insert([
                        ['user_id' => $user_id, 'company_id' => $company, 'date' => date('d-m-Y')]
                    ]);
                    // **************************************************************************************************
                    //here it goes to mailchimp
                    $user = AppUsers::whereId($user_id)->first();
                    $list = DB::table('company_mailchimp_list')->where('company_id',$company)->where('Is_favorite',1)->first();
                    if(!empty($list) && !empty($user)){
                        $listId     = $list->list_id;
                        $apiKey     = $list->api_key;
                        $dataCenter = $list->data_center;
                        $postData = array(
                            "email_address" => "$user->email",
                            "status"        => "subscribed",// "subscribed","unsubscribed","cleaned","pending"
                            "merge_fields"  => array(
                                "NAME"          => "$user->name",
                                "PHONE"         => "$user->phone_number"
                            )
                        );
                        $ch = curl_init('https://'.$dataCenter.'.api.mailchimp.com/3.0/lists/'.$listId.'/members/');
                        curl_setopt_array($ch, array(
                            CURLOPT_POST => TRUE,
                            CURLOPT_RETURNTRANSFER => TRUE,
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: apikey '.$apiKey,
                                'Content-Type: application/json'
                            ),
                            CURLOPT_POSTFIELDS => json_encode($postData)
                        ));
                        $response = json_decode(curl_exec($ch),true);
                        $input = [
                            'membership_id' => $response['id'],
                            'list_id'       => $listId,
                            'IsSynced'      => 1,
                        ];
                        DB::table('fav_user_list')->where('user_id',$user_id)->where('company_id',$company)->update($input);
                        curl_close($ch);
                    }
                    // **************************************************************************************************
                }
                $data['success'] = true;
                $data['data'] = "";
                $data['message'] = "success";
            }
        }
        else{
            if(favorite::where('campaign_id', $campaign_id)->where('user_id', $user_id)->exists()){
                favorite::where('campaign_id', $campaign_id)->where('user_id', $user_id)->forceDelete();
            }
            $fav=favorite::where('user_id', $user_id)->get();
            $data['success'] = true;
            $data['data'] = "";
            $data['message'] = "success";
        }

        return $data;
    }
}