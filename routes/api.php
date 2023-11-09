<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Tasks\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    // User Routes
    Route::post('login/user',[AuthController::class, 'login']);
                            // =========admin ===============
    Route::middleware(['auth:api','Admin'])->group( function (){
        // users routes
        Route::post('register',[AuthController::class, 'register']);
        Route::post('delete/user',[AuthController::class,'delete']);
        Route::get('index',[AuthController::class,'index']);
            Route::post('update/user',[AuthController::class,'update']);
            // tasks Routes
            Route::post('create/task',[TaskController::class,'create']);
            Route::post('update/task',[TaskController::class,'update']);
            Route::post('delete/task',[TaskController::class,'delete']);
            Route::post('index/task',[TaskController::class,'index']);
            
            
    });
                            //======= Team leader============
    Route::middleware(['auth:api','Team_leader'])->group(function (){
        Route::post('create/employee',[AuthController::class,'createEmployee']);
        Route::post('delete/employee',[AuthController::class,'delete']);
        Route::get('index/employee',[AuthController::class,'index']);
        Route::post('update/employee',[AuthController::class,'update']);
            // task Routes
                Route::post('create/task/employee',[TaskController::class,'create']);
                Route::post('update/task/employee',[TaskController::class,'update']);
            Route::post('delete/task/employee',[TaskController::class,'delete']);
            Route::post('index/task',[TaskController::class,'index']);
            Route::post('show/task/employee',[TaskController::class,'show']);
    });
    Route::middleware(['auth:api','Employee'])->group( function (){
        Route::post('updatestatus',[AuthController::class,'updateStatus']);
        Route::get('show',[TaskController::class, 'show']);
    });
    





















// Route::group(['prefix'=> 'Team_leader','middleware'=> ['auth:sanctum','Team_leader']], function () {
//     // User Routes
//     Route::post('register',[AuthController::class, 'register']);
//     Route::post('login',[AuthController::class, 'login']);
//     // tasks Routes
//     Route::post('create',[TaskController::class,'create']);
//     Route::post('update',[TaskController::class,'update']);
//     Route::post('delete',[TaskController::class,'delete']);
//     Route::post('show',[TaskController::class,'show']);
// });
// Route::group(['prefix'=> 'user','middleware'=>'auth:sanctum'], function () {
//     Route::post('show',[TaskController::class,'show']);
// });