<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//*********************************************************************************
Route::post('make_user', 'userAPIController@make_user');
Route::post('get_user', 'userAPIController@get_user');

Route::post('user_login', 'userAPIController@user_login');

Route::get('activate_users/{link}', 'userAPIController@activate_users');

Route::get('resendMail/{id}', 'userAPIController@resendMail');

Route::post('activate_user', 'userAPIController@activate_user_api');

Route::post('update_user', 'userAPIController@update_user');

Route::post('delete_user', 'userAPIController@delete_user');

Route::post('admin_login', 'userAPIController@admin_login');

Route::post('forgot_password', 'userAPIController@forgot_password');

Route::post('reset_password', 'userAPIController@reset_password');
//*********************************************************************************
Route::resource('favorites', 'favoriteAPIController');

Route::post('make_favorite', 'favoriteAPIController@make_favorite');
//*********************************************************************************
Route::resource('campaigns', 'campaignsAPIController');

Route::post('make_campaign', 'campaignsAPIController@make_campaign');

Route::post('update_campaign', 'campaignsAPIController@update_campaign');

Route::post('delete_campaign', 'campaignsAPIController@delete_campaign');

Route::post('campaigns_list', 'campaignsAPIController@campaigns_list');

Route::post('campaign_details', 'campaignsAPIController@campaign_details');

Route::post('campaign_display', 'campaignsAPIController@campaign_display');

Route::post('add_click', 'campaignsAPIController@add_click');

Route::post('add_view', 'campaignsAPIController@add_view');

Route::post('bot_lists', 'campaignsAPIController@bot_lists');

//*********************************************************************************
Route::resource('connects', 'connectAPIController');

Route::post('make_connect', 'connectAPIController@make_connect');
//*********************************************************************************
Route::resource('category_campaigns', 'category_campaignAPIController');
//*********************************************************************************
Route::resource('category_campaigns', 'categoryCampaignAPIController');
//*********************************************************************************
Route::resource('bot_campaigns', 'botCampaignAPIController');

//*********************************************************************************
Route::resource('bots', 'botAPIController');

Route::post('make_bot', 'botAPIController@make_bot');

Route::get('get_categories', 'botAPIController@get_categories');

Route::post('edit_bot', 'botAPIController@edit_bot');

Route::post('verify_beacon', 'botAPIController@verify_beacon');

Route::post('instance_campaign', 'botAPIController@instance_campaign');

Route::post('filter_category', 'botAPIController@filter_category');
//*********************************************************************************
Route::resource('categories', 'categoryAPIController');
//*********************************************************************************
Route::resource('companies', 'companyAPIController');