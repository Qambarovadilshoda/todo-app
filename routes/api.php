<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('/tasks', TaskController::class);
    Route::get('/filter/tasks', [TaskController::class, 'tasksFilter']);
    Route::get('/update/status/{id}', [TaskController::class, 'updateStatus']);
});
