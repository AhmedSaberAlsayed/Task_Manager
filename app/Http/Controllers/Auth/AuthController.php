<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\createEmployeeReaquest;
use App\Http\Requests\Auth\LoginReaquest;
use App\Http\Requests\Auth\RegisterReaquest;
use App\Http\Requests\Auth\Updaterequest;
use App\Http\Requests\Auth\UpdateStatusrequest;
use App\Http\Traits\Api_designtrait;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use Api_designtrait;
        // ====== Create User Account =====================

    public function register(RegisterReaquest $registerReaquest){
        
        $register=User::create([
            "name"=> $registerReaquest->name,
            "email"=> $registerReaquest->email,
            "password"=> bcrypt($registerReaquest->password),
            "img_path"=> $registerReaquest->img_path,
            "role"=> $registerReaquest->role,
            "leader_id"=> $registerReaquest->leader_id
            ]);
            return $this->api_design(200,"user was registed successfully", $register);
        
    }
    // ====== Create Employee Account =====================
    public function createEmployee(createEmployeeReaquest $createEmployeeReaquest){
        $id=Auth::user()->id;
        $employee=User::create([
        "name"=> $createEmployeeReaquest->name,
        "email"=> $createEmployeeReaquest->email,
        "password"=> bcrypt($createEmployeeReaquest->password),
        "img_path"=> $createEmployeeReaquest->img_path,
        "leader_id"=> $id 
        ]);
       
        return $this->api_design(200,"employee was registed successfully", $employee);

    }
        // ====== Generate token =====================

    public function login(LoginReaquest $loginReaquest){
            $token = JWTAuth::attempt([
            "email"=> $loginReaquest->email,
            "password"=> $loginReaquest->password
        ]);
        if (!empty($token)) {
            $data= User::where('email', $loginReaquest->email)->first();
            return $this->api_design(200,"login succesfull", [$data,$token],null);
        }else {
            return $this->api_design(400,'eror',null, $token->errors());
        }
}
        // ====== Update User Account =====================
public function update(Updaterequest $updaterequest) 
{
    $this->authorize('update',[ User::class, $updaterequest, Auth::class]);
    $user=User::where('id',$updaterequest->id)->first();
        $user->update([
            "name"=> $updaterequest->name,
            "email"=> $updaterequest->email,
            "password"=> bcrypt($updaterequest->password),
            "img_path"=> $updaterequest->img_path,
        ]);

            if(Auth::user()->isAdmin())
            {
                $user->update([
                "role"=> $updaterequest->role,
                "leader_id"=> $updaterequest->leader_id
        ]);
            };
        
        return $this->api_design(200,'user update successfully', [$user,],null);
    }
        // ====== delete User Account =====================

public function delete(Request $request){
    $this->authorize('delete',[ User::class, $request]);
    $user=User::where('id',$request->id)->first();

    $user->delete();
    return $this->api_design(200,'user delete successfully',$user,null);
            // ====== index User Account =====================
}
// !$targetUser->isTeamLeader()
public function index()
{
    // $this->authorize('view',[ User::class]);
    if(Auth::user()->isAdmin()){
        $user=User::get()->all();
        return $this->api_design(200,'All users',$user,null);
    }elseif(Auth::user()->isTeamLeader()){
        $user=User::where('leader_id',Auth::user()->id)->get();
        return $this->api_design(200,'my users',$user,null);
    }
    return false;
}
}