<?php

namespace App\Listeners;

use App\Events\EmployeeUpdate;
use App\Notifications\Backend\EmployeeUpdate as NotificationEmployeeUpdate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmployeeUpdateEmail
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
     * @param  EmployeeUpdate  $event
     * @return void
     */
    public function handle(EmployeeUpdate $event)
    {
        $user             = $event->user;
        $is_password      = $event->is_password;

        try
        {
            $user->notify(new NotificationEmployeeUpdate($user, $is_password));
        }
        catch (\Exception $e)
        {

        }
    }
}
