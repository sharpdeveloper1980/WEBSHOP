<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
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
        if($user->permission->is_webshop_admin || $user->permission->is_store_admin) {
            return true;
        }
        return false;
    }

    public function view(User $user)
    {
        if($user->permission->is_webshop_admin || $user->permission->is_store_admin) {
            return true;
        }
        return false;
    }

    public function create(User $user)
    {
        if($user->permission->is_webshop_admin || $user->permission->is_store_admin) {
            return true;
        }
        return false;
    }

    public function update(User $user)
    {
        if($user->permission->is_webshop_admin || $user->permission->is_store_admin) {
            return true;
        }
        return false;
    }

    public function delete(User $user)
    {
        if($user->permission->is_webshop_admin || $user->permission->is_store_admin) {
            return true;
        }
        return false;
    }
}