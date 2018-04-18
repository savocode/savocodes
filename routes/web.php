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
//use App\Classes\FireStoreHandler;
use App\Classes\RijndaelEncryption;

Route::prefix('')->namespace('Frontend')->group(function () {
    Route::get('verification/email', 'Auth\LoginController@emailVerification')->name('api.verification.email'); // For direct password reset

    Route::get('/home', function() {
        return view('welcome');
    });
    Route::get('/', function() {
        //return view('welcome');
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
    Route::get('reset-password',        'Auth\ForgotPasswordController@showLinkRequestForm')->name('backend.password.request');
    Route::post('password/email',       'Auth\ForgotPasswordController@sendResetLinkEmail')->name('backend.password.email');
    Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('backend.password.reset');
    Route::post('reset-password',       'Auth\ResetPasswordController@reset')->name('backend.password.final');

    Route::group(['middleware' => 'backend.auth'], function () {
        Route::get('/dashboard', 'DashboardController@getIndex')->name('backend.dashboard');
        Route::match(['GET', 'POST'],   '/system/edit-profile', 'DashboardController@editProfile')->name('backend.profile.setting');

        Route::group(['middleware' => 'backend.admin'], function () {
            Route::get('/users/verification',                               'UserController@verificationListPending');
            Route::get('/users/data',                                       'UserController@data');
            Route::get('/users/detail/{record}',                            'UserController@detail');
            Route::get('/users/block/{record}',                             'UserController@block');
            Route::get('/users/unblock/{record}',                           'UserController@unblock');
            Route::get('/users/verified/{record}',                          'UserController@verify');
            Route::get('/users/unverified/{record}',                        'UserController@unverify');
            Route::post('/users/{record}',                                  'UserController@handleVerification');
            Route::get('/users/{index?}',                                   'UserController@index');
            Route::delete('/users/{record}',                                'UserController@destroy');
            Route::get('/users/edit/{record}',                              'UserController@showEditForm');
            Route::put('/users/edit/{record}',                              'UserController@update');

            Route::get('/hospitals/data',                                   'HospitalController@data');
            Route::get('/hospitals/detail/{record}',                        'HospitalController@detail');
            Route::get('/hospitals/block/{record}',                         'HospitalController@block');
            Route::get('/hospitals/unblock/{record}',                       'HospitalController@unblock');
            Route::get('/hospitals/index',                                  'HospitalController@index');
            Route::get('/hospitals/create',                                 'HospitalController@showCreateForm');
            Route::post('/hospitals/create',                                'HospitalController@create');

            Route::get('/hospitals/edit/{record}',                          'HospitalController@showEditForm');
            Route::put('/hospitals/edit/{record}',                          'HospitalController@update');
            Route::delete('/hospitals/{record}',                            'HospitalController@destroy');

            //Hospital Employees
            Route::get('/hospitals/{record}/employees',                     'HospitalController@employees');
            Route::get('/hospitals/{record}/employees/data',                'HospitalController@employeesData');
            Route::get('/hospitals/{record}/employee/create',               'HospitalController@showEmployeeCreateForm');
            Route::post('/hospitals/{record}/employee/create',              'HospitalController@createEmployee');
            Route::get('/hospitals/{hospital}/employee/edit/{employee}',    'HospitalController@showEmployeeEditForm');
            Route::put('/hospitals/{hospital}/employee/edit/{employee}',    'HospitalController@editEmployee');
            Route::get('/hospitals/{hospital}/employee/detail/{record}',    'HospitalController@detailEmployee');

            Route::get('/employee/block/{record}',                          'HospitalController@blockEmployee');
            Route::get('/employee/unblock/{record}',                        'HospitalController@unblockEmployee');
            Route::delete('/employee/{record}',                             'HospitalController@destroyEmployee');


            Route::get('settings/criteria',                                 'SettingController@criteriaIndex');
            Route::post('settings/criteria/edit',                           'SettingController@criteriaEdit');

            Route::get('settings/profession',                               'SettingController@professionIndex');
            Route::put('settings/profession/edit/{record}',                 'SettingController@professionEdit');

            Route::match(['GET', 'POST'], '/reports',                       'ReportsController@index');
            Route::match(['GET', 'POST'], '/system/edit-settings',          'DashboardController@editSettings')->name('backend.settings');

            Route::group(['prefix' => 'reports'], function() {
                Route::get('dashboard', 'ReportsController@dashboard')->name('backend.reports.dashboard');
                Route::match(['GET', 'POST'], 'car/statistics', 'ReportsController@carStatistics')->name('backend.reports.car.statistics');
                Route::match(['GET', 'POST'], 'popular/driver', 'ReportsController@popularDriver')->name('backend.reports.popular.driver');
            });
        });

        Route::group(['middleware' => 'backend.employee'], function(){

            Route::get('/physicians/data',                   'EmployeeController@physiciansData');
            Route::get('/physicians/detail/{record}',        'EmployeeController@physiciansDetail');
            Route::get('/physicians/{index?}',               'EmployeeController@physiciansIndex');


            Route::get('/referrals/index',               'EmployeeController@referralsIndex');
            Route::get('/referrals/detail/{record}',     'EmployeeController@referralsDetail');
            Route::get('/referrals/data',                'EmployeeController@referralsData');
            Route::post('/referrals/accept/{record}',    'EmployeeController@referralsAccept');
            Route::post('/referrals/reject/{record}',    'EmployeeController@referralsReject');

            Route::get('/reports/referrals',             'EmployeeController@referralsReports');
        });
    });
});

Route::get('/test', function () {

    $user = User::find(6);
    $user->{'2fa'} = '234';
    dd($user);

    dd(RijndaelEncryption::encrypt('name'));

    dd(collect(User::getEncryptionFields())->exclude('password')->concat(['old_pwd']));

    /*$users = User::all();
    foreach ($users as $user) {
        // $user->first_name = \App\Classes\RijndaelEncryption::encrypt($user->first_name);
        // $user->phone      = \App\Classes\RijndaelEncryption::encrypt($user->phone);
        // $user->email      = \App\Classes\RijndaelEncryption::encrypt($user->email);
        // $user->last_name  = \App\Classes\RijndaelEncryption::encrypt($user->last_name);
        $user->password  = bcrypt('17eFwzMTTeC8ToIISebueQ==');

        $user->save();
    }

    dd('dDONE');*/

    // dd( \App\Classes\RijndaelEncryption::decrypt('lxXNOYlO1kE2ZtPbk+AHkg==') );
    // dd(mb_convert_encoding(\App\Classes\RijndaelEncryption::decrypt('jkVhj2xLcnLxL/szizcbgA=='), 'UTF-8'));
    /*$text = \App\Classes\RijndaelEncryption::decrypt('jkVhj2xLcnLxL/szizcbgA==');
    dd(mb_detect_encoding($text, mb_detect_order(), true));
    dd(iconv(mb_detect_encoding($text, mb_detect_order(), true), "ASCII", $text));*/

    $text = '1';
    $encrypted = \App\Classes\RijndaelEncryption::encrypt($text);
    dd($encrypted);
    $value = trim(\App\Classes\RijndaelEncryption::decrypt($encrypted));
    dd(($text === $value), $value);
    $encrypted = \App\Classes\RijndaelEncryption::encrypt($value);
    // $encrypted = \App\Classes\RijndaelEncryption::encrypt('abc123');
    dd($encrypted);
    $value = \App\Classes\RijndaelEncryption::decrypt('');
    dd($value);

    return;

    $ref = new \App\Models\Referral([
        'first_name' => 'test',
    ]);

    $user = User::find(5);
    $ref->doctor()->associate($user); // by object
    $ref->hospital()->associate(1); // by id
    $ref->save();

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
    select * from `users` where `email` = ? and `role_id` = ? and `users`.`deleted_at` is null limit 1 - a:2:{i:0;s:44:"JwIIPzqJ4GTGBwWYcP57W5fJC2ojlp5MMydP9e6b1ns=";i:1;i:3;}
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

Route::get('appmaisters-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('backend.admin');

Route::get('/email-decrypt', function(Illuminate\Http\Request $request){
   echo RijndaelEncryption::encrypt($request->get('email'));
    dd(RijndaelEncryption::decrypt($request->get('email', '')));
});

Route::get('test-notification', function(Illuminate\Http\Request $request){

    $type   = 'accepted';
    $title  = 'Referral request accepted';
    $body   = "Your referral has been $type by the ";

    $payload = [
        'type'           => $type,
        'message'        => $body
    ];


    fcmNotification($request->token, $title, $body, $payload);

    echo "Send";

});

Route::get('check-test', function(){
    $registrationIds = '3212462313213213';
    $msg = [
        'title'   => 'GAM',
        'text'   => 'asldjkasd',
        'vibrate' => 1,
        'sound'   => 1
    ];

    $msg_data = [
        'body' => 'asldjkasd'
    ];
    $fields = [
        'to'  => $registrationIds,
        'notification' => $msg,
        'data' => $msg_data
    ];

    echo "<pre>";
    print_r($fields);
    echo "<pre>";exit;
});
