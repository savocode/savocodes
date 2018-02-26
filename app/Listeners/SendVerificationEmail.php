<?php

namespace App\Listeners;

use App\Classes\Email;
use App\Events\Api\JWTUserRegistration;
use App\Notifications\Api\Registration;

class SendVerificationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  JWTUserRegistration  $event
     * @return void
     */
    public function handle(JWTUserRegistration $event)
    {
        $user       = $event->user;
        $attributes = $event->attributes;

        try {
            $user->notify( new Registration($user, $attributes['password']) );
        } catch (\Exception $e) {}
    }
}
