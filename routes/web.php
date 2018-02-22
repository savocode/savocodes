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
use App\Models\User;
use App\Classes\FireStoreHandler;
use App\Models\Trip;
use App\Models\TripRide;
use App\Models\TripRideOffer;
use App\Models\TripMember;
use App\Models\PassengerCard;

Route::prefix('')->namespace('Frontend')->group(function () {
    Route::get('verification/email', 'Auth\LoginController@emailVerification')->name('api.verification.email'); // For direct password reset

    Route::get('/home', function() {
        return view('welcome');
    });
    Route::get('/', function() {
        return view('welcome');
    });

    // Reset password via link
    Route::get('account/reset/{status}', 'UserController@passwordResetStatus');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('frontend.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('frontend.password.request');
});

Route::prefix('backend')->namespace('Backend')->group(function () {
    Route::get('/', function() { return redirect('/backend/dashboard'); });
    Route::get('/login', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('/logout', 'Auth\LoginController@logout');
    Route::get('/cities/{stateID}', 'DashboardController@getCities');

    // Password Reset Routes...
    Route::get('reset-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('backend.password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backend.password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('backend.password.reset');
    Route::post('reset-password', 'Auth\ResetPasswordController@reset')->name('backend.password.final');

    Route::group(['middleware' => 'backend.auth'], function () {
        Route::get('/dashboard', 'DashboardController@getIndex')->name('backend.dashboard');
        Route::match(['GET', 'POST'], '/system/edit-profile', 'DashboardController@editProfile')->name('backend.profile.setting');

        Route::group(['middleware' => 'backend.admin'], function () {
            Route::get('/users/verification', 'UserController@verificationListPending');
            Route::get('/users/data', 'UserController@data');
            Route::get('/users/detail/{record}', 'UserController@detail');
            Route::get('/users/purchases/{record}', 'UserController@purchases');
            Route::get('/users/block/{record}', 'UserController@block');
            Route::get('/users/unblock/{record}', 'UserController@unblock');
            Route::get('/users/verified/{record}', 'UserController@verify');
            Route::get('/users/unverified/{record}', 'UserController@unverify');
            Route::post('/users/{record}', 'UserController@handleVerification');
            Route::get('/users/{index?}', 'UserController@index');
            Route::delete('/users/{record}', 'UserController@destroy');

            Route::get('/users/create/driver', 'DriverController@createDriver');
            Route::post('/users/create/driver', 'DriverController@storeDriver');
            Route::get('/users/edit/driver/{record}', 'DriverController@editDriver');
            Route::post('/users/edit/driver/{record}', 'DriverController@updateDriver');

            Route::get('/users/create/passenger', 'PassengerController@createPassenger');
            Route::post('/users/create/passenger', 'PassengerController@storePassenger');
            Route::get('/users/edit/passenger/{record}', 'PassengerController@editPassenger');
            Route::post('/users/edit/passenger/{record}', 'PassengerController@updatePassenger');

            Route::get('/reviews/data', 'ReviewController@data');
            Route::get('/reviews/{index?}', 'ReviewController@index');
            Route::delete('/reviews/{record}', 'ReviewController@destroy');
            Route::get('/reviews/approve/{record}', 'ReviewController@approve');
            Route::get('/reviews/disapprove/{record}', 'ReviewController@disapprove');

            Route::get('/user-stats/data', 'StatsController@data');
            Route::get('/user-stats/detail/{record}', 'StatsController@userStats');
            Route::get('/user-stats/{index?}', 'StatsController@paymentTransaction');

            Route::get('/promo-codes/create', 'PromoCodeController@create');
            Route::post('/promo-codes', 'PromoCodeController@save');
            Route::delete('/promo-codes/{record}', 'PromoCodeController@delete');
            Route::get('/promo-codes/data', 'PromoCodeController@data');
            Route::get('/promo-codes/edit/{record}', 'PromoCodeController@edit');
            Route::post('/promo-codes/{record}', 'PromoCodeController@update');
            Route::get('/promo-codes/{index?}', 'PromoCodeController@index')->name('promo-codes.index');

            Route::get('/schools/create', 'SchoolController@create');
            Route::post('/schools', 'SchoolController@save');
            Route::delete('/schools/{record}', 'SchoolController@delete');
            Route::get('/schools/edit/{record}', 'SchoolController@edit');
            Route::post('/schools/{record}', 'SchoolController@update');
            Route::get('/schools/{index?}', 'SchoolController@index')->name('schools.index');

            Route::match(['GET', 'POST'], '/reports', 'ReportsController@index');
            Route::match(['GET', 'POST'], '/system/edit-settings', 'DashboardController@editSettings')->name('backend.settings');

            Route::group(['prefix' => 'trips'], function() {
                Route::get('payments', 'TripController@payments')->name('backend.payments');
                Route::get('payments/data', 'TripController@paymentsData')->name('backend.payments.data');
                Route::get('payments/detail/{record}', 'TripController@paymentDetail')->name('backend.payments.detail');

                Route::get('canceled', 'TripController@canceledTrips')->name('backend.trips.canceled');
                Route::get('canceled/data', 'TripController@canceledTripsData')->name('backend.trips.canceled.data');
            });

            Route::group(['prefix' => 'reports'], function() {
                Route::get('dashboard', 'ReportsController@dashboard')->name('backend.reports.dashboard');
                Route::match(['GET', 'POST'], 'car/statistics', 'ReportsController@carStatistics')->name('backend.reports.car.statistics');
                Route::match(['GET', 'POST'], 'popular/driver', 'ReportsController@popularDriver')->name('backend.reports.popular.driver');
            });
        });
    });
});

Route::get('/test', function () {

    return 'w0w';

});

// Development Routes [START]
Route::get('/debug/rest-api', function() {
    $request = request();
    $request->merge([
        '_token'       => JWTAuth::fromUser(User::find(96)),
        'trip_id'      => '76',
        'passenger_id' => '96',
    ]);
    // $inject = app('App\Http\Requests\Api\SearchRideRequest');
    if ( isset($inject) ) {
        $request = $inject;
    }
    // dd($request->all());
    return app('App\Http\Controllers\Api\RideController')->driverDeletePassenger($request);
});

Route::get('/debug/query-debugger', function() {
    $query = <<<LOG
    select * from `trips` where `id` in (?, ?, ?, ?, ?) - a:5:{i:0;i:66;i:1;i:76;i:2;i:77;i:3;i:78;i:4;i:79;}
LOG;

    list($query, $bindings) = explode(' - ', $query);
    $query = trim($query);
    $bindings = unserialize($bindings);

    $replace = [];
    foreach ($bindings as $value) {
        if ( is_object($value) ) {
            $value = $value->__toString();
        }

        $replace[] = gettype($value) === 'string' ? "'".removeQuotes($value)."'" : $value;
    }

    foreach ($replace as $newValue) {
        $query = preg_replace('/'.preg_quote('?', '/').'/', $newValue, $query, 1);
    }

    return '<pre>'.$query.'</pre>';
});

Route::get('/debug/log-to-curl', function() {
    $query = <<<LOG
    [2018-01-25 09:46:32] log.DEBUG: Time: January 25, 2018, 9:46 am
URL: http://192.168.168.114/seatus/public/api/v1/passenger/payment/history
Method: POST
Input: Array
(
    [_token] => eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjE3MCwiaXNzIjoiaHR0cDovLzE5Mi4xNjguMTY4LjExNC9zZWF0dXMtZGV2L3B1YmxpYy9hcGkvdjEvbG9naW4iLCJpYXQiOjE1MTAyMjI4MTcsImV4cCI6MTU0MTc1ODgxNywibmJmIjoxNTEwMjIyODE3LCJqdGkiOiJsUjNZWnFnYXFsTnBaaUZDIn0.4UwNSFmXC4l5AdhhdUFcCcJyPsPxnlfws5jtyh2X5NU
    [limit] => 1
    [page] => 2
)
LOG;

    preg_match('%URL: (.*)%', $query, $url);
    preg_match('%Method: (.*)%', $query, $method);
    preg_match_all('%\[(.*?)\] => (.*)%', $query, $body, PREG_SET_ORDER);

    $result = [];
    foreach ($body as $key => $value) {
        $result[] = $value[1] . '=' . str_replace("'", "\'", $value[2]);
    }

    return "curl -X ".$method[1]." --header 'Content-Type: application/x-www-form-urlencoded' --header 'Accept: application/json' -d '".implode('&', $result)."' '".$url[1]."'";
});

Route::get('/debug/firebase-user-update', function() {
    set_time_limit(500);
    $users = User::users()->get();
    foreach ($users as $me) {
        event(new App\Events\Api\JWTUserRegistration($me));
    }
});
Route::get('/debug/firestore-user-update/{id}', function($id='') {
    $users = User::users()->whereId($id)->get();
    foreach ($users as $me) {
        event(new App\Events\Api\JWTUserUpdate($me));
    }
});

Route::get('validate-configuration-appmaisters', function() {
    $allGood              = true;
    $requiredSettingCount = 0;
    $requiredEnvironment  = [
        'LOG_WEBSERVICE',
    ];

    foreach ($requiredEnvironment as $env) {
        if ( null === env($env) ) {
            $allGood = false;
            echo "$env environment does not exist.<br />";
        }
    }

    if ( App\Models\Setting::count() != $requiredSettingCount ) {
        $allGood = false;
        echo 'Setting does not meet the required entry, please verify';
        echo '<br />';
    }

    if ( false === $allGood ) {
        return '<br /><span style="color:red;font-weight:bold;">[x]</span> You donot meet the requirement, please adjust accordingly and re-run the test.';
    } else {
        return '<span style="color:green;font-weight:bold;">[✓]</span> You are good to go!';
    }

});
// Development Routes [END]

Route::get('appmaisters-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
