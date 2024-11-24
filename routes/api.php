<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/task-lists', [TaskListController::class, 'index']);
    Route::post('/task-lists', [TaskListController::class, 'store']);
    Route::put('/task-lists/{taskListid}', [TaskListController::class, 'update']);
    Route::delete('/task-lists/{taskListid}', [TaskListController::class, 'destroy']);
    
    Route::put('/task-lists/add-task/{taskListid}', [TaskListController::class, 'addTaskToList']);
    Route::put('/task-lists/remove-task/{taskListid}', [TaskListController::class, 'removeTaskFromList']);


    Route::get('/shared-task-lists', [TaskListController::class, 'getSharedTaskLists']);
    Route::post('/shared-task-lists', [TaskListController::class, 'storeSharedTaskList']);
    Route::put('/shared-task-lists/{sharedTaskListId}', [TaskListController::class, 'updateSharedTaskListPermission']);
    Route::delete('/shared-task-lists/{sharedTaskListId}', [TaskListController::class, 'deleteSharedTaskList']);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{taskId}', [TaskController::class, 'update']);
    Route::delete('/tasks/{taskId}', [TaskController::class, 'destroy']);


    Route::get('/users/{username}', [UserController::class, 'getUsersByUsername']);
});
