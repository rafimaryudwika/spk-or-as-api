<?php

namespace App\Http\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function admin(User $user)
    {
        return $user->roles_id == 1;
    }
    public function panitia(User $user)
    {
        return $user->roles_id == 1 || 2;
    }
}
