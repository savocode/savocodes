<?php

namespace App\Listeners;

use App\Models\Notification;
use App\Events\ReferralStatusChange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReferralNotificationStore
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
        $reason     = $event->reason;

        $type   = $status?'accepted':'rejected';
        $title  = $status?'Referral request accepted':'Referral request rejected';

        $payload = [
            'type'           => $type,
            'message'        => "Your referral has been $type by the ".$referral->hospital->title,
            'reason'         => $reason
        ];

        info(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $notification['user_id']            = $user->id;
        $notification['user_type']          = $user->user_role_key_web;
        $notification['notification']       = $title;
        $notification['notification_data']  = $reason;
        $notification['notification_type']  = $type;
        $notification['payload']            = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $notification['is_read']            = 0;

        Notification::create($notification);

    }
}
