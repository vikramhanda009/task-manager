<?php

declare(strict_types=1);

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All routes are named so views and redirects use route() helpers rather
| than hard-coded paths. URL changes then only require editing this file.
|
| NOTE: The /tasks/reorder route MUST be registered before /tasks/{task}
| so Laravel does not try to resolve "reorder" as a Task model binding.
|
*/

// Route::get('/', fn() => redirect()->route('tasks.index'));

// // Tasks
// Route::resource('tasks', TaskController::class);

// // Drag-drop reorder
// Route::post('/tasks/reorder', [TaskController::class, 'reorder'])
//     ->name('tasks.reorder');

// // Projects (bonus)
// Route::resource('projects', ProjectController::class);

Route::redirect('/', '/tasks');

// Task routes
Route::resource('tasks', TaskController::class);

// Reorder route (important ⭐)
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])
    ->name('tasks.reorder');

// Project routes (bonus)
Route::resource('projects', ProjectController::class);