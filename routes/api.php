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

Route::middleware(['api'])->namespace('Api')->prefix('v1')->group(function () {

    Route::get('boot-me-up', 'WebserviceController@initializationConfigs');

    Route::post('register', 'WebserviceController@register');
    Route::post('login', 'WebserviceController@login');
    Route::post('2fa-auth', 'WebserviceController@verify2FaCode');
    Route::post('reset-password', 'WebserviceController@resetPassword');
    Route::post('resend-verification-email', 'WebserviceController@resendVerificationEmail');
    Route::post('logout', 'WebserviceController@logout');
    Route::post('contact-us', 'WebserviceController@saveContactUs');
    Route::get('criteria', 'WebserviceController@criteria');

    Route::match(['GET', 'PUT', 'POST'], 'test', 'WebserviceController@testMethods');

    Route::group(['middleware' => 'jwt-auth'], function () {

        Route::group(['prefix' => 'account'], function() {
            Route::post('me', 'WebserviceController@viewMyProfile');
            Route::post('update', 'WebserviceController@updateMyProfile');
            Route::post('view/{user_id}', 'WebserviceController@viewProfile');
        });

        Route::post('hospital/search', 'WebserviceController@searchHospitalsByZipCode');
        Route::post('hospital/detail', 'WebserviceController@getHospitalDetail');

        Route::post('referral/submit', 'WebserviceController@submitReferral');
        Route::post('referral/list', 'WebserviceController@getReferrals');

    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
