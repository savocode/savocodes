<?php

namespace App\Listeners;

use App\Events\SavingReferral;
use App\Http\Traits\JWTUserTrait;
use App\Models\Referral;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Auth;
use Illuminate\Support\Facades\Request;

class AddReferralStatusHistory
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
     * @param  SavingReferral  $event
     * @return void
     */
    public function handle(SavingReferral $event)
    {
        $referral = $event->referral;

        // Its a new entry
        if (null === $referral->status) {
            $status = Referral::DEFAULT_STATUS;
        } else if (array_key_exists('status', $referral->getDirty())) {
            $status = $referral->status;
        }

        if (isset($status)) {
            $user   = Auth::check() ? Auth::user() : JWTUserTrait::getUserInstance();
            $reason = Request::get('reason', NULL);

            $statusHistory = $referral->statusHistory()->getRelated()->fill([
                'status' => $status,
                'reason' => $reason,
            ]);

            $statusHistory->referral()->associate($referral);
            $statusHistory->user()->associate($user);
            $statusHistory->save();
        }
    }
}
