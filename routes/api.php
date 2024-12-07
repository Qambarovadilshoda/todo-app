<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Task\TaskController;

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('/tasks', TaskController::class);
    Route::get('/filter/tasks', [TaskController::class, 'tasksFilter']);
    Route::get('/update/status/{id}', [TaskController::class, 'updateStatus']);
    Route::get('logout',[AuthController::class,'logout']);
});
Route::post('register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
