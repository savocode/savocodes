<?php

namespace App\Events\Api;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class JWTUserLogout
{
    use SerializesModels;

    public $user;
    public $attributes;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $attributes=array())
    {
        $this->user = $user;
        $this->attributes = $attributes;
    }
}
