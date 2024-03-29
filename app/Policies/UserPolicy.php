<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->role == "OWNER" || $user->role == "ADMIN";
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->role == "OWNER" || $user->role == "ADMIN";
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == "OWNER" || $user->role == "ADMIN";
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($user->role == "OWNER" || $user->role == "ADMIN") {
            if ($model->isDirty('role')) {
                switch ($model->role) {
                    case "OWNER":
                        return $user->role == "OWNER";
                    case "ADMIN";
                        return $user->role == "OWNER" || $user->role == "ADMIN";
                    case "USER":
                        return $user->role == "OWNER" || $user->role == "ADMIN";
                    default:
                        return false;
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if ($user->role == "OWNER" || $user->role == "ADMIN") {
            switch ($model->role) {
                case "OWNER":
                    return $user->role == "OWNER";
                case "ADMIN";
                    return $user->role == "OWNER" || $user->role == "ADMIN";
                case "USER":
                    return $user->role == "OWNER" || $user->role == "ADMIN";
                default:
                    return false;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        return $user->role == "OWNER" || $user->role == "ADMIN";
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return $user->role == "OWNER";
    }
}
