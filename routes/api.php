<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskTagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TagController;
use App\Models\Tag;


Route::apiResource('tasks', TaskController::class);
Route::post('tasks/{task}/tags', [TaskController::class, 'attachTags']);
Route::delete('tasks/{task}/tags/{tag}', [TaskController::class, 'detachTag']);
Route::post('/tasks/{task}/tags/attach', [TaskTagController::class, 'attachTag']);
Route::post('/tasks/{task}/tags/detach', [TaskTagController::class, 'detachTag']);
Route::get('/tags/{tag}/tasks', [TaskTagController::class, 'tasksByTag']);
Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags', [TagController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/tags', function () {
    return Tag::all();
});