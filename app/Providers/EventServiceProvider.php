<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Webservice Events & Listeners
        'App\Events\Api\JWTUserLogin' => [
            // 'App\Listeners\Api\JWTUserLoginSyncWithFirestore',
        ],
        'App\Events\Api\JWTUserUpdate' => [
            // 'App\Listeners\Api\JWTUserUpdateSyncWithFirestore',
        ],
        'App\Events\Api\JWTUserRegistration' => [
            // 'App\Listeners\SendWelcomeEmail',
            'App\Listeners\SendVerificationEmail',
            // 'App\Listeners\Api\JWTUserRegistrationSyncWithFirestore',
        ],
        'App\Events\Backend\CreateUserFromBackend' => [
            // 'App\Listeners\SendPasswordEmail',
            // 'App\Listeners\Api\JWTUserRegistrationSyncWithFirestore',
        ],
        'App\Events\Api\JWTUserLogout' => [
            // 'App\Listeners\Api\SendHiddenPushNotification',
        ],
        'App\Events\Backend\UserDeleted' => [
            // 'App\Listeners\Api\SendHiddenPushNotification',
            // 'App\Listeners\DeleteUserData',
        ],
        'App\Events\Backend\UserDeactivated' => [
             'App\Listeners\SendDeActivationEmail',
        ],
        'App\Events\Backend\UserActivated' => [
            'App\Listeners\SendActivationEmail'
        ],
        'App\Events\SavingReferral' => [
            'App\Listeners\AddReferralStatusHistory',
        ],
        'App\Events\UserPasswordChanged' => [
            'App\Listeners\SendPasswordChangedEmail',
        ],
        'App\Events\EmployeeUpdate' => [
            'App\Listeners\SendEmployeeUpdateEmail',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
