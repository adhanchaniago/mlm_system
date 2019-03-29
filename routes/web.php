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

Auth::routes();



//Route::get('/dashboard', 'HomeController@index');

Route::get('/welcome', 'HomeController@samyindex');

Route::get('/', 'home1Controller@samyindex');

//***********************************************************Resources***********************************************
Route::resource('companies', 'companyController');
Route::resource('SamyBotPlans', 'SamyBotPlansController');
Route::resource('affiliates', 'affiliateController');
Route::resource('ranks', 'rankController');
Route::resource('levels', 'levelController');
Route::resource('revenuehistories', 'revenuehistoryController');
Route::resource('payouthistories', 'payouthistoryController');
Route::resource('plantables', 'plantableController');
Route::resource('emailcontents', 'emailcontentController');
Route::resource('linkedinPlans', 'linkedin_plansController');
Route::resource('salescontents', 'salescontentController');
Route::resource('weeklyfees', 'weeklyfeesController');
Route::resource('frontPages', 'frontPageController');
Route::resource('sliderImages', 'sliderImagesController');

//****************************************************************CRON JOBS *************************************************

Route::get('planExpire','cronController@planExpireCron');
Route::get('autoCharge','cronController@autoChargeCommission');
Route::get('resetStats','cronController@resetStats');
Route::get('autoChargeSuccess/{id}/{total}','cronController@autoChargeSuccess');

//****************************************************************Payments*****************************************************
Route::get('autorenewMlm/{id}/{val}','StripeController@StopMlmAutoRenewal');
Route::get('Affiliate/Renewal/{id}','StripeController@AffiliateRenewal');
Route::get('stripe', 'StripeController@payWithStripe');
Route::post('paymentWithStripe', 'StripeController@postPaymentWithStripe');
Route::get('autoPay/{id}/{total}','home1Controller@autoPay');
Route::get('/savedCards','companyController@savedCards');
Route::get('/savedCards/add','companyController@addCard');
Route::get('/editCard/{id}','companyController@editCard');
Route::post('/SavedCards/Store','companyController@storeCard');
Route::post('/savedCards/edit/{id}','companyController@updateCard');
Route::get('/deletecard/{id}','companyController@destroyCard');
Route::get('/activateCard/{id}','StripeController@activateCard');

//**************************************************************Register/Invitation********************************************

Route::get('register/{id}','home1Controller@CompanyRegister');
Route::get('terms','home1Controller@terms');
Route::post('register/affliate','home1Controller@affliateRegister');
Route::post('invite-link', 'affiliateController@inviteEmail');
Route::get('affliate/register/{id}/{invitee}/{email}/{special}','home1Controller@showAffliateForm');
Route::get('{company}/affliate/register/{id}/{invitee}/{email}/{special}','home1Controller@showAffliateForm1');
Route::get('invite-user/{id}/{invitee}', 'home1Controller@showDirectAffliateForm');
Route::get('{company_name}/invite-user/{id}/{invitee}', 'home1Controller@showinDirectAffliateForm');
Route::post('/purchaseLink','affiliateController@purchaseLink');
Route::post('/samyBotLink','affiliateController@samyBotLink');
Route::post('/samy_affiliate/purchase_success','home1Controller@purchase_success');
Route::post('/cookie_duration','home1Controller@cookie_duration');

Route::get('getusers', 'HomeController@getusers');
Route::get('selectType/{id}/{val}', 'HomeController@selectType');

Route::get('mailchimp/index', 'emailcontentController@mailchimp');
Route::post('mailchimp/create', 'emailcontentController@mailchimpCreate');
Route::post('mailchimp/update', 'emailcontentController@mailchimpUpdate');

Route::get('validatePhone/{phone}','home1Controller@validatePhone');

Route::get('validateProfilePhone/{phone}','homeController@validatePhone');

Route::get('logout',function ()
{
    \Illuminate\Support\Facades\Auth::logout();
});

Route::get('/changeStatus/{id}/{val}','HomeController@changeStatus'); //grating admin
Route::get('/disableCompany/{id}/{val}/{col}','companyController@disableCompany');
Route::get('/paypalCredentials','companyController@paypalCredentials');
Route::post('/paypalCredentials','companyController@SavepaypalCredentials');
Route::get('/pending/affiliate','companyController@pendingSamyAffiliateCompanies');
Route::get('/pending/samybot','companyController@pendingSamybotCompanies');
Route::get('/pending/linkedin','companyController@pendingSamyLinkedinCompanies');
Route::get('/samy_affiliate/show/{id}','companyController@showAffiliateCompany');
Route::get('/samy_bot/show/{id}','companyController@showBotCompany');
Route::get('/samy_linkedIn/show/{id}','companyController@showLinkCompany');
Route::get('/checkDomain/{value}/{id}','companyController@checkDomain');
Route::get('/changeBotPlanStatus/{id}/{value}','SamyBotPlansController@changeBotPlanStatus');

//***************************************************************Timeout for Blocking and activate charge **************************************

Route::get('/timeout','HomeController@timeout');

Route::get('/timeout/edit','HomeController@timeoutEdit');

Route::get('/activateCharge','HomeController@activateCharge');

Route::get('/shipping','HomeController@shipping');

Route::get('/email','HomeController@email');

Route::post('/email/edit','HomeController@emailEdit');

Route::post('/shipping/edit','HomeController@shippingEdit');

Route::post('/activateCharge/edit','HomeController@activateChargeedit');

Route::post('/timeout/save','HomeController@timeoutSave');

//******************************************************frontEnd*************************************************************

Route::get('/company/home','HomeController@companyIndex');

Route::get('/plans','home1Controller@plans');

Route::get('/home','home1Controller@index');

Route::get('/edit/details','home1Controller@landing');

Route::get('/changeTerm/{val}','home1Controller@changeTerm');



//*******************************************************Edit Company Profile ********************************************

Route::get('/myProfile','companyController@editDetails');

Route::get('/edit/smtp/company','companyController@editSMTPDetails');

Route::post('/myProfile/{id}','companyController@update');

Route::post('/edit/smtp/company/{id}','companyController@smtp');

Route::post('/affiliate/details/{id}','affiliateController@updateUser');

//***************************************************************Confirm Email *********************************************

Route::get('confirm/email/{token}', 'HomeController@confirmEmail');

Route::get('confirmEmail', 'HomeController@confirm_Email');

Route::get('resendMail/{id}', 'HomeController@resendEmail');

//******************************************************************Email Invitation/Purchase Link*************************************

Route::get('checkMail/{mail}', 'affiliateController@checkMail');

Route::get('/deleteSliderData/{id}','frontPageController@deleteSliderData');

//********************************************************************ContactUs Form ****************************************
Route::get('/messages','HomeController@messages');
Route::get('/deleteMsg/{id}','HomeController@deleteMsg');
Route::post('/contactUs','home1Controller@contactUsStore');

//************************************************************************Company Billing Details and saved cards ******************************
Route::get('/billing/company','companyController@billing');
Route::get('/emailcontentsedit','emailcontentController@edit');

//***********************************************************************Company Email Content *******************************

//**************************************************Delete a Level *************************************
Route::get('/deleteLevel','levelController@deleteLevel');

//*******************************************************Affiliate search sort filters ****************************************
Route::get('/SearchByName/{id}/{array}','affiliateController@SearchByName');
Route::get('/descendingSort/{val}/{array}','affiliateController@descendingSort');
Route::get('/ascendingSort/{val}/{array}','affiliateController@ascendingSort');
Route::get('/filterbyRank/{array}/{val}','affiliateController@filterbyRank');
Route::get('/todayStats/{val}','companyController@todayStats');
Route::get('/overallStats/{val}','affiliateController@overallStats');

//********************************************************Exporting **********************************************************
Route::get('/monthlyBreakdownPdf/{month}/{year}/{monthName}','payouthistoryController@monthlyBreakdownPdf');
Route::get('/monthlyBreakdownCsv/{month}/{year}/{monthName}','payouthistoryController@monthlyBreakdownCsv');
Route::post('/savePayoutMethod','payouthistoryController@savePayoutMethod');
Route::get('/exportSales/{id}','affiliateController@exportSales');
Route::get('/exportSalesPdf/{id}','affiliateController@exportSalesPdf');
Route::get('/marketing/help','affiliateController@exportSales');

//****************************************************Affiliates FrontEnd********************************************************
Route::get('/marketing/help','affiliateController@marketingHelp');
Route::get('/sales','affiliateController@affilaiteSales');
Route::get('/stats','affiliateController@affilaiteStats');
Route::get('/domain','home1Controller@errorDomain');
Route::post('/superadmin/register','home1Controller@superAdminRegister');
Route::get('/language/{value}','home1Controller@changeLanguage');
Route::get('/checkcompanyName/{id}','companyController@checkcompanyName');
Route::get('manualPayout','payouthistoryController@manualPayout');
Route::get('paypalPayout','payouthistoryController@paypalPayout');
Route::get('paypal_email','HomeController@paypalEmail');
Route::post('paypal_email','HomeController@savepaypalEmail');


//*********************************************************************Invoice**************************************
Route::get('/invoice/{id}','companyController@invoice');

//*******************************************MailChimp********************************************

//******************************************** Samy BOt *******************************************************

// Controllers Within The "App\Http\Controllers\samybot" Namespace
Route::prefix('samybot')->group(function () {
    Route::group(['namespace' => 'samybot'], function()
    {

        Route::post('ReSubscription','StripeController@ReSubscription');
        Route::get('RenewBot/{id}','StripeController@RenewBot');
        Route::get('autorenew/{id}/{val}','StripeController@StopBotAutoRenewal');
        Route::post('stripe','StripeController@stripePayment');
        Route::get('payment','StripeController@payment');
        Route::get('cron','cronController@samybotCron');
        Route::get('releaseBot/{bot}/{camp}','campaignsController@releaseBot');
        Route::get('saveBot/{bot}/{camp}','campaignsController@saveBot');
        Route::get('samy_bots','campaignsController@samy_bots');
        Route::get('release/{id}/{campaign}','SamyController@samyBotRelease');
        Route::get('lifetime_graph/{id}','campaignsController@lifetime_graph');
        Route::get('campaign/delete/{id}','campaignsController@delete_campaign');
        Route::get('fetch_plan_data/{planId}/{pack}','SamyController@fetch_plan_data');
        Route::get('searchUser/{id}','SamyController@searchUser');
        Route::get('find_fav_user/{id}','SamyController@searchFavorites');
        Route::get('plans','SamyController@samybot_plan');
        Route::get('plan','Samy1Controller@samybot_plan');
        Route::get('campaigns','campaignsController@campaign');
        Route::get('my_prospects','SamyController@my_prospects');
        Route::get('favorite_users','SamyController@favorite_users');
        Route::get('new_campaign','campaignsController@new_campaign');
        Route::post('proceed_to_pay','SamyController@register_update');
        Route::get('proceed_to_order','SamyController@proceed_to_order');
        Route::post('proceed_to_order','SamyController@proceed_to_order');
        Route::post('proceed_payment','Samy1Controller@samybot_register');
        Route::post('create_campaign','campaignsController@create_campaign');
        Route::post('update_campaign','campaignsController@update_campaign');
        Route::get('syncMailchimp/{id}/{comp}/{list}/{type}','SamyController@syncMailchimp');
        Route::get('syncFavMailchimp/{id}/{comp}/{list}/{type}','SamyController@syncMailchimp');
        Route::get('favorites/csv/{id}','SamyController@favoriteCsv');
        Route::get('prospects/csv','SamyController@prospectsCsv');
        Route::get('invoice/{id}','SamyController@generateInvoice');
        Route::get('generateAndSendInvoice/{id}','SamyController@generateAndSendInvoice');
    });
});


// Controllers Within The "App\Http\Controllers\samyLinkedIn" Namespace

Route::get('samylinkedIn/payment','StripeController@LinkedInpayment');

Route::prefix('samylinkedIn')->group(function () {
    Route::group(['namespace' => 'samylinkedIn'], function()
    {
        Route::get('plans','LinkedInController@plans');
        Route::get('checkout/{id}','LinkedInController@checkout');
        Route::post('proceed_to_pay','LinkedInController@proceed_to_pay');
        Route::post('proceed_payment','LinkedInController@proceed_payment');
        Route::get('campaigns','LinkedInController@campaigns');
        Route::get('new_campaign','LinkedInController@new_campaign');
    });
});

Route::get('/{val}','home1Controller@loginCompany');
