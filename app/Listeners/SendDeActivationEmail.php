<?php

namespace App\Listeners;

use App\Events\Backend\UserDeactivated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Backend\UserActivationEmail;

class SendDeActivationEmail
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
     * @param  UserDeactivated  $event
     * @return void
     */
    public function handle(UserDeactivated $event)
    {
        $user       = $event->user;
        $is_active  = $event->is_active;

        try
        {
            $user->notify(new UserActivationEmail($user, $is_active));
        }
        catch (\Exception $e)
        {

        }
    }
}
