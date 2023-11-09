<?php

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, )
    {
        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isTeamLeader()) {
            return true;
        } elseif ($user->isEmployee() ) {
            return true;
        }
        return false;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isTeamLeader()) {
            return true;
        }
        return false;
    }
    

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,$updaterequest)
    {        
        $TargetUser = User::where("id",$updaterequest->id)->first();
        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isTeamLeader() && $TargetUser->leader_id == $user->id) {
            return true;
        }
        return false;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,$request)
    { $TargetUser = User::where("id",$request->id)->first();

        if ($user->isAdmin()) {
            return true;
        } elseif ($user->isTeamLeader() && $TargetUser->leader_id == $user->id) {
            return true;
        }
        return false;
    }
    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, user $model): bool
    // {
    //     //
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  */
    // public function forceDelete(User $user, user $model): bool
    // {
    //     //
    // }
}
