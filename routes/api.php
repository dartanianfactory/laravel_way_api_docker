<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/_v1/tasks', [TaskController::class, 'index']);
Route::post('/_v1/tasks', [TaskController::class, 'store']);
Route::get('/_v1/tasks/{task}', [TaskController::class, 'show']);
Route::put('/_v1/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/_v1/tasks/{task}', [TaskController::class, 'destroy']);
