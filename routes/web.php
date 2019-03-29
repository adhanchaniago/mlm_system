<?php



/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/



Route::get('/', function () {

    if (Auth::user())

    {

        if (Auth::user()->status == '0') //if user is a super admin

        {

            return view('layouts.app');

        }

        elseif (Auth::user()->status == '1' && Auth::user()->payment == 1) //If the user is a company/admin and payment is completed

        {

            return view('frontEnd.home');

        }

        elseif(Auth::user()->status == '1' && Auth::user()->payment == 0) //If the user is company/admin and payment is not completed, redirecting to payment page

        {

            return redirect('stripe');

        }

        else //means user is affliate

        {

            return view('frontEnd.home');

        }

    }

    else {

        return view('frontEnd.landing');

    }

});





Auth::routes();



Route::get('/dashboard', 'HomeController@index');



Route::get('getusers', 'HomeController@getusers');



Route::get('selectType/{id}/{val}', 'HomeController@selectType');



Route::resource('fghs', 'fghController');



Route::resource('companies', 'companyController');



Route::resource('affiliates', 'affiliateController');



Route::resource('ranks', 'rankController');



Route::resource('levels', 'levelController');



Route::resource('revenuehistories', 'revenuehistoryController');



Route::resource('payouthistories', 'payouthistoryController');



Route::resource('plantables', 'plantableController');



Route::resource('emailcontents', 'emailcontentController');



Route::resource('salescontents', 'salescontentController');



Route::resource('weeklyfees', 'weeklyfeesController');



Route::resource('frontPages', 'frontPageController');



Route::resource('sliderImages', 'sliderImagesController');



//***************************************************Setting-up a plan *************************************************







//******************************************************Register as company or affliate **********************************

Route::get('register/{id}', function ($id) {

    $plan = App\Models\plantable::whereId($id)->first();

    return view('companies.company_register')->with('plan',$plan);

});

Route::post('register/affliate','home1Controller@affliateRegister');

Route::get('affliate/register/{id}/{invitee}','home1Controller@showAffliateForm');

Route::get('/changeStatus/{id}/{val}','HomeController@changeStatus'); //grating admin



//***************************************************************Timeout for Blocking **************************************

Route::get('/timeout','HomeController@timeout');

Route::get('/timeout/edit','HomeController@timeoutEdit');

Route::post('/timeout/save','HomeController@timeoutSave');

//******************************************************frontEnd*************************************************************

Route::get('/company/home','HomeController@companyIndex');

Route::get('/home','home1Controller@index');

Route::get('/edit/details','home1Controller@landing');



//*******************************************************Edit Company Profile ********************************************

Route::get('/edit/user/details','companyController@editDetails');

Route::post('/company/details/{id}','companyController@update');

Route::post('/affiliate/details/{id}','affiliateController@update');



//*************************************************************Stripe Payment ***************************************

// Route for stripe payment form.

Route::get('stripe', 'StripeController@payWithStripe');

// Route for stripe post request.

Route::post('stripe', 'StripeController@postPaymentWithStripe');



//***************************************************************Confirm Email *********************************************

Route::get('confirm/email/{token}', 'HomeController@confirmEmail');

Route::get('confirmEmail', 'HomeController@confirm_Email');

Route::get('resendMail/{id}', 'HomeController@resendEmail');



//******************************************************************Email Invitation*************************************

Route::post('send/activation-link', 'affiliateController@inviteEmail');

Route::get('/deleteSliderData/{id}','frontPageController@deleteSliderData');

//********************************************************************ContactUs Form ****************************************
Route::get('/messages','HomeController@messages');