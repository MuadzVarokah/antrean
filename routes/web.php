<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {return view('welcome');});

// Halaman publik
Route::get('/', [QueueController::class, 'index'])->name('queue.index');
Route::post('/queue/take', [QueueController::class, 'take'])->name('queue.take');
Route::get('/queue/current', [QueueController::class, 'current'])->name('queue.current');
Route::get('/queue/last', [QueueController::class, 'last'])->name('queue.last');
Route::get('/queue/list', [QueueController::class, 'list'])->name('queue.list');

// Auth routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/queue/next', [AdminController::class, 'next'])->name('queue.next');
    Route::post('/queue/prev', [AdminController::class, 'prev'])->name('queue.prev');
    Route::post('/queue/complete', [AdminController::class, 'complete'])->name('queue.complete');
    Route::post('/queue/skip', [AdminController::class, 'skip'])->name('queue.skip');
    Route::post('/queue/jump', [AdminController::class, 'jump'])->name('queue.jump');
    Route::get('/queue/data', [AdminController::class, 'queueData'])->name('queue.data');
});