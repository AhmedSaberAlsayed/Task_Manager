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
    public function createTask(User $user)
    {
        if ($user->isAdmin() || $user->isTeamLeader()) {
            return true;
        }
        return false;
    }
    

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, task $task ): bool
    {
        return $user->isAdmin() || ($user->isTeamLeader() && $user->id === $task->user_id->leader_id);

    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, task $task): bool
    {
        return $user->isAdmin() || ($user->isTeamLeader() && $user->id === $task->user_id->leader_id);

    }
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
