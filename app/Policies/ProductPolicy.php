<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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

    public function view(User $user)
    {
        return $user->permission->is_webshop_admin;
    }

    public function create(User $user)
    {
        return $user->permission->is_webshop_admin;
    }

    public function update(User $user)
    {
        return $user->permission->is_webshop_admin;
    }

    public function delete(User $user)
    {
        return $user->permission->is_webshop_admin;
    }
}