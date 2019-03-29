<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatebotAPIRequest;
use App\Http\Requests\API\UpdatebotAPIRequest;
use App\Models\bot;
use App\Models\AppUsers;
use App\Models\botCampaign;
use App\Models\category;
use App\Models\categoryCampaign;
use App\Models\campaigns;
use App\Models\favorite;
use App\Repositories\botRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;

/**
 * Class botController
 * @package App\Http\Controllers\API
 */
class botAPIController extends AppBaseController {
	/** @var  botRepository */
	private $botRepository;

	public function __construct( botRepository $botRepo ) {
		$this->botRepository = $botRepo;
	}

	public function make_bot( Request $request ) {
		$bot_id      = $request->bot_id;
		$company_id  = $request->company_id;
		$instance_id = $request->instance_id;
		$bot_name    = $request->bot_name;
		$bot_type    = $request->bot_type;
		if ( empty( $bot_id ) || empty( $company_id ) || empty( $instance_id ) || empty( $bot_name ) ) {
			$data['success'] = false;
			$data['data']    = "";
			$data['message'] = "Required all the fields";

			return $data;
		}
		if ( bot::where( 'bot_id', $bot_id )->exists() ) {
			$data['success'] = false;
			$data['data']    = "";
			$data['message'] = "Bot is already exists.";

			return $data;
		}
		$botInput = [
			'bot_id'      => $bot_id,
			'company_id'  => $company_id,
			'instance_id' => $instance_id,
			'bot_name'    => $bot_name,
			'bot_type'    => $bot_type,
		];
		$bot      = bot::create( $botInput );
		if ( $bot ) {
			$data['success'] = true;
			$data['data']    = $bot;
			$data['message'] = "success";
		} else {
			$data['success'] = false;
			$data['data']    = "";
			$data['message'] = "something went wrong.Couldn't create bot";
		}

		return $data;
	}

	public function edit_bot( Request $request ) {
		$bot_id      = $request->bot_id;
		$instance_id = $request->instance_id;
		$bot_name    = $request->bot_name;
		$bot_type    = $request->bot_type;
		if ( bot::where( 'bot_id', $bot_id )->exists() ) {
			$botInput = [
				'bot_id'      => $bot_id,
				'bot_name'    => $bot_name,
				'instance_id' => $instance_id,
				'bot_type'    => $bot_type,
			];
			$bot      = bot::where( 'bot_id', $bot_id )->update( $botInput );
			if ( $bot_type == "idle" ) {
				botCampaign::where( 'bot_id', $bot_id )->delete();
			}
			$data['success'] = true;
			$data['data']    = $bot;
			$data['message'] = "success";
		} else {
			$data['success'] = false;
			$data['data']    = "";
			$data['message'] = "Bot id not exists.";
		}

		return $data;
	}

	public function verify_beacon( Request $request ) {
		$instance_id = $request->beacon;
		if ( bot::where( 'instance_id', $instance_id )->exists() ) {
			$data['success'] = true;
			$data['data']    = $instance_id;
			$data['message'] = "success";
		} else {
			$data['success'] = false;
			$data['data']    = "";
			$data['message'] = "not found";
		}

		return $data;
	}

	public function filter_category( Request $request ) {
		$category = $request->category_id;
		if ( ! empty( $category ) ) {
			$campaigns = categoryCampaign::where( 'category_id', $category )->get();
			if ( $campaigns->count() <= 0 ) {
				$data['success'] = false;
				$data['data']    = "";
				$data['message'] = "No bots in this category.";

				return $data;
			}
			foreach ( $campaigns as $campaign ) {
				$bot    = botCampaign::where( 'campaign_id', $campaign->campaign_id )->first();
				$bots[] = $bot->bot_id;
			}
			$data['success'] = true;
			$data['data']    = $bots;
			$data['message'] = "success";
		} else {
			$data['success'] = false;
			$data['data']    = "";
			$data['message'] = "category id is empty";
		}

		return $data;
	}

	public function get_categories() {
		$cats            = category::get();
		$data['success'] = true;
		$data['data']    = $cats;
		$data['message'] = "success";

		return $data;
	}

	public function instance_campaign( Request $request ) {
		$instance_id = $request->instance_id;
		$user_id     = $request->user_id;
		if (empty($user_id)) {
			$campData = [];
			if ( bot::where('instance_id', $instance_id )->exists() ) {
				$bots = bot::where( 'instance_id', $instance_id )->first();
                if (botCampaign::where( 'bot_id', $bots->bot_id)->exists()) {
                    $bot_campaigns = botCampaign::where( 'bot_id', $bots->bot_id )->get();
                    foreach ( $bot_campaigns as $bot_campaign ) {
                        $campaigns = campaigns::whereId($bot_campaign->campaign_id )->first();
                        DB::table( 'campaign_view' )->insert( [
                            [
                                'user_id'     => 0,
                                'campaign_id' => $campaigns->id,
                                'company_id'  => $campaigns->company_id,
                                'date'        => date( 'd-m-Y' )
                            ]
                        ]);
                        if(!empty($campaigns->campaign_views)) {
                            $views = $campaigns->campaign_views + 1;
                        } else {
                            $views = 1;
                        }
                        campaigns::whereId($campaigns->id)->update(['campaign_views' => $views]);
                        $campaigns->is_favoiurite = false;
                        $campaigns->instance_id   = $instance_id;
                        $cat                            = category::whereId($campaigns->campaign_category)->first();
                        $campaigns['campaign_category'] = $cat->category_name;
                        $campaigns['category_id']       = $campaigns->campaign_category;
                        $campData = $campaigns;
                    }
                    $data['success'] = true;
                    $data['data']    = $campData;
                    $data['message'] = "success";
                } else {
                    $data['success'] = false;
                    $data['data']    = "";
                    $data['message'] = "No campaigns are associated with this instance";
                }
			} else {
				$data['success'] = false;
				$data['data']    = "";
				$data['message'] = "No campaigns are associated with this instance";
			}
		}
		else {
			if(AppUsers::whereId($user_id)->exists() ) {
				$user       = AppUsers::whereId( $user_id )->first();
				$campData   = [];
				if(bot::where('instance_id', $instance_id )->exists() ) {
					$bots = bot::where( 'instance_id', $instance_id )->first();
                    if (botCampaign::where('bot_id', $bots->bot_id )->exists()) {
                        $bot_campaigns = botCampaign::where('bot_id', $bots->bot_id)->get();
                        foreach ($bot_campaigns as $bot_campaign) {
                            $campaigns     = campaigns::whereId($bot_campaign->campaign_id)->first();
                            $campaign_view = DB::table('campaign_view')->where('user_id',$user_id )->where( 'campaign_id', $campaigns->id)->first();
                            if (empty($campaign_view)) {
                                DB::table( 'campaign_view' )->insert( [
                                    [
                                        'user_id'     => $user_id,
                                        'campaign_id' => $campaigns->id,
                                        'company_id'  => $campaigns->company_id,
                                        'date'        => date( 'd-m-Y' )
                                    ]
                                ] );
                            }
                            if ( ! empty( $campaigns->campaign_views ) ) {
                                $views = $campaigns->campaign_views + 1;
                            } else {
                                $views = 1;
                            }
                            campaigns::whereId($campaigns->id)->update(['campaign_views' => $views ]);
                            if (favorite::where('user_id',$user_id )->where('campaign_id',$campaigns->id)->exists() ) {
                                $campaigns->is_favoiurite = true;
                            } else {
                                $campaigns->is_favoiurite = false;
                            }
                            $campaigns->instance_id         = $instance_id;
                            $cat                            = category::whereId($campaigns->campaign_category)->first();
                            $campaigns['campaign_category'] = $cat->category_name;
                            $campaigns['category_id']       = $campaigns->campaign_categor;
                            $campData = $campaigns;
                        }
//		            #################################
                        if (!empty($user_id)){
                            $company_user = DB::table('company_user_list')->where('user_id', $user_id )->where( 'company_id', $campData->company_id )->first();
                            if ( empty( $company_user ) ) {
                                    DB::table( 'company_user_list' )->insert( [
                                        [ 'user_id'    => $user_id,
                                          'company_id' => $campData->company_id,
                                          'date'       => date( 'd-m-Y' )
                                        ]
                                    ] );
                            }
                            $list = DB::table( 'company_mailchimp_list' )->where( 'company_id', $campData->company_id)->where( 'Is_Prospect', 1 )->first();
                            if ( ! empty( $list ) && ! empty( $user ) ) {
                                    $listId     = $list->list_id;
                                    $apiKey     = $list->api_key;
                                    $dataCenter = $list->data_center;
                                    $postData   = array(
                                        "email_address" => "$user->email",
                                        "status"        => "subscribed",
                                        // "subscribed","unsubscribed","cleaned","pending"
                                        "merge_fields"  => array(
                                            "NAME"  => "$user->name",
                                            "PHONE" => "$user->phone_number"
                                        )
                                    );
                                    $ch         = curl_init( 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' );
                                    curl_setopt_array( $ch, array(
                                        CURLOPT_POST           => true,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_HTTPHEADER     => array(
                                            'Authorization: apikey ' . $apiKey,
                                            'Content-Type: application/json'
                                        ),
                                        CURLOPT_POSTFIELDS     => json_encode( $postData )
                                    ) );
                                    $response = json_decode( curl_exec( $ch ), true );
                                    if(isset($response['id'])){
                                        $input    = [
                                            'membership_id' => $response['id'],
                                            'list_id'       => $listId,
                                            'IsSynced'      => 1,
                                        ];
                                        DB::table( 'company_user_list' )->where( 'user_id', $user_id )->where( 'company_id', $campData->company_id )->update( $input );
                                    }
                                    curl_close( $ch );
                                }
                        }
                        else {
                            if ( isset( $campaigns ) ) {
                                $campaigns->is_favoiurite = false;
                            }
                        }
//		            #################################
                        $data['success'] = true;
                        $data['data']    = $campData;
                        $data['message'] = "success";
                    }
                    else {
                        $data['success'] = false;
                        $data['data']    = "";
                        $data['message'] = "No campaigns are associated with this instance";
                    }
				}
				else {
					$data['success'] = false;
					$data['data']    = "";
					$data['message'] = "No campaigns are associated with this instance";
				}
			}
			else {
				$data['success'] = false;
				$data['data']    = "";
				$data['message'] = "User not exists";
			}
		}
		return $data;
	}

	/**
	 * Display a listing of the bot.
	 * GET|HEAD /bots
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index( Request $request ) {
		$this->botRepository->pushCriteria( new RequestCriteria( $request ) );
		$this->botRepository->pushCriteria( new LimitOffsetCriteria( $request ) );
		$bots = $this->botRepository->all();

		return $this->sendResponse( $bots->toArray(), 'Bots retrieved successfully' );
	}

	/**
	 * Store a newly created bot in storage.
	 * POST /bots
	 *
	 * @param CreatebotAPIRequest $request
	 *
	 * @return Response
	 */
	public function store( CreatebotAPIRequest $request ) {
		$input = $request->all();

		$bots = $this->botRepository->create( $input );

		return $this->sendResponse( $bots->toArray(), 'Bot saved successfully' );
	}

	/**
	 * Display the specified bot.
	 * GET|HEAD /bots/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show( $id ) {
		/** @var bot $bot */
		$bot = $this->botRepository->findWithoutFail( $id );

		if ( empty( $bot ) ) {
			return $this->sendError( 'Bot not found' );
		}

		return $this->sendResponse( $bot->toArray(), 'Bot retrieved successfully' );
	}

	/**
	 * Update the specified bot in storage.
	 * PUT/PATCH /bots/{id}
	 *
	 * @param  int $id
	 * @param UpdatebotAPIRequest $request
	 *
	 * @return Response
	 */
	public function update( $id, UpdatebotAPIRequest $request ) {
		$input = $request->all();

		/** @var bot $bot */
		$bot = $this->botRepository->findWithoutFail( $id );

		if ( empty( $bot ) ) {
			return $this->sendError( 'Bot not found' );
		}

		$bot = $this->botRepository->update( $input, $id );

		return $this->sendResponse( $bot->toArray(), 'bot updated successfully' );
	}

	/**
	 * Remove the specified bot from storage.
	 * DELETE /bots/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy( $id ) {
		/** @var bot $bot */
		$bot = $this->botRepository->findWithoutFail( $id );

		if ( empty( $bot ) ) {
			return $this->sendError( 'Bot not found' );
		}

		$bot->delete();

		return $this->sendResponse( $id, 'Bot deleted successfully' );
	}
}
