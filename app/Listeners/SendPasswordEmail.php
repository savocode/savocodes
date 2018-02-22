<?php

namespace App\Listeners;

use App\Notifications\CreateAccount;

class SendPasswordEmail
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
     * @param  $event
     * @return void
     */
    public function handle($event)
    {
        $user    = $event->user;
        $request = $event->request;

        try {
            $user->notify(new CreateAccount($user, $request));
        } catch (\Exception $e) {}
    }
}
