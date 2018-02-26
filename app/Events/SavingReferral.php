<?php

namespace App\Events;

use App\Models\Referral;
use Illuminate\Queue\SerializesModels;

class SavingReferral
{
    use SerializesModels;

    public $referral;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }
}
