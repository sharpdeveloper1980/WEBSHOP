<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the resource.
     *
     * @param  User  $user
     * @return mixed
     */
    
    public function viewAny(User $user)
    {
        return $user->permission->is_webshop_admin;
    }
}