<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateStatusrequest;
use App\Http\Requests\task\createReaquest;
use App\Http\Requests\task\UpdateReaquest;
use App\Http\Traits\Api_designtrait;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    use Api_designtrait;
    // public function __construct(){
    //     $this->authorizeResource(Task::class, Task::class);
    // }
            // ====== create task   =====================

   public function create(createReaquest $createReaquest)
   {
    $this->authorize("createTask", Task::class);
    
     $id=Auth::user()->id;
    $create=Task::create([
        "title"=> $createReaquest->title,
        "describtion"=>$createReaquest->describtion,
        "deadline"=> $createReaquest->deadline,
        "created_by"=> $id,
        "status"=> $createReaquest->status,
        "user_id"=> $createReaquest->user_id,
    ]);
   
    return $this->api_design(200,"task create succsefully",$create,null);
   
}
        // ====== index task   =====================

public function index()
{
    $this->authorize("view", Task::class);
    $Task=Task::get()->all();
        return $this->api_design(200,'All users',$Task,null);

}
        // ====== show task   =====================

public function show(){
    $user=Auth::user();
    $task = Task::where('user_id',$user->id)->get();
    return $this->api_design(200,'my tasks', $task);
}

        // ====== update task   =====================

public function update(UpdateReaquest $updateReaquest) 
{
    $this->authorize("update", Task::class);

    $update=Task::where('id',$updateReaquest->id)->first();
    $update->update([
        "title"=> $updateReaquest->title,
        "describtion"=>$updateReaquest->describtion,
        "deadline"=> $updateReaquest->deadline ,
        "created_by"=> $updateReaquest->created_by ,
        "status"=> $updateReaquest->status,
        "user_id"=> $updateReaquest->user_id
    ]);
    return $this->api_design(200,"the user update succesfully",$update,null);

}
        // ====== Delete task=====================

public function delete(Request $request){
    $this->authorize("delete", Task::class);
    $task=Task::where('id',$request->id)->first();
    $task->delete();
    return $this->api_design(200,'user delete successfully',$task,null);
    
}
        // ====== updateStatus of task=====================

        public function updateStatus(UpdateStatusrequest $request, Task $task)
        {
            $this->authorize('updateStatus', Task::class);
            $task->update(['status' => $request->status]);
            return $this->api_design(200,'All users',$task,null);
        }
        
}
