<?php

namespace App\Events\Backend;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class CreateUserFromBackend
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $request)
    {
        $this->user    = $user;
        $this->request = $request;
    }
}
