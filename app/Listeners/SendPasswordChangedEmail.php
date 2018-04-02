<?php

namespace App\Listeners;

use App\Notifications\Backend\EmployeePasswordChanged;

class SendPasswordChangedEmail
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
        $user           = $event->user;
        $password       = $event->password;

        try
        {
            $user->notify(new EmployeePasswordChanged($user, $password));
        }
        catch (\Exception $e)
        {

        }
    }
}
