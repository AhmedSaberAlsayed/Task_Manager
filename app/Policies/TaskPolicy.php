<?php

namespace App\Policies;

use App\Http\Requests\Auth\Updaterequest;
use App\Models\User;
use App\Models\task;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, task $task): bool
    {
        if ($user->isAdmin() || $task->created_by==$user->isTeamLeader()) {
            return true;
        } elseif ($user->isEmployee()) {
            return $user->id === $task->user_id;
        }
        return false;
    }
    /**
     * Determine whether the user can create models.
     */
    public function createTask(User $user,$createReaquest)
    {
        $Targettask = User::where("id", $createReaquest->user_id)->get()->first();
        // dd( $Targettask);
        if ($user->isAdmin()) {
            return true;
        }
        elseif ( $user->isTeamLeader() && $user->id === $Targettask->leader_id ) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, $updateReaquest ): bool
    {
         $TargetTask = task::where("id",$updateReaquest->id)->first();
         if ($user->isAdmin()) {
            return true;
         }
         elseif ( $user->isTeamLeader() &&$TargetTask->leader_id == $user->id){
            return true;
         }
        
        return false;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,$request): bool
    {
        $TargetTask = task::where("id",$request->id)->first();
        if ($user->isAdmin()) {
           return true;
        }elseif ( $user->isTeamLeader() &&$TargetTask->leader_id == $user->id){
           return true;
        }
       
       return false;    }
    public function updateStatus(User $user, Task $task)
{
    if ($user->isAdmin()) {
        return true;
    } elseif ($user->isTeamLeader()) {
        return $user->id === $task->user_id->leader_id;
    }
    return false;
}
}
