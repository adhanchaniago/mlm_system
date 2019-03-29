<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {
        require config('infyom.laravel_generator.path.api_routes');
    });
});


Route::auth();

Route::get('/home', 'HomeController@index');

Route::resource('affilates', 'affilatesController');



Route::resource('companies', 'companiesController');

Route::resource('emailcontents', 'emailcontentsController');

Route::resource('levels', 'levelsController');

Route::resource('payouthistories', 'payouthistoriesController');

Route::resource('plantables', 'plantablesController');

Route::resource('ranks', 'ranksController');

Route::resource('revenuehistories', 'revenuehistoriesController');

Route::resource('salescontents', 'salescontentController');

Route::resource('stripePayments', 'stripePaymentController');

Route::resource('timeouts', 'timeoutController');

Route::resource('weeklyfees', 'weeklyfeesController');