<?php

namespace App\Listeners;


use App\Events\ReferralStatusChange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mockery\Exception;

class ReferralStatusPush
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
     * @param  ReferralStatusChange  $event
     * @return void
     */
    public function handle(ReferralStatusChange $event)
    {
        $user       = $event->user;
        $status     = $event->status;
        $referral   = $event->referral;
      //  $reason     = $event->reason;

        $type   = $status?'accepted':'rejected';
        $title  = $status?'Referral request accepted':'Referral request rejected';
        $body   = "Your referral has been $type by the ".$referral->hospital->title;

        $payload = [
            'type'           => $type,
            'message'        => $body
        ];

        if (isset($user->device()->device_token))
        {
            $user->devices->map(function ($record) use ($payload, $referral, $title, $body) {
                fcmNotification($record->device_token, $title, $body, $payload);
            });
        }
    }
}
