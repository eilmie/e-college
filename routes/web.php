<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomApplicationController;
use App\Http\Controllers\Admin\RoomApplicationController as AdminRoomApplicationController;
use App\Http\Controllers\PortalFetchController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\DashboardController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/applications', [AdminRoomApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}/proof', [AdminRoomApplicationController::class, 'viewProof'])->name('applications.proof');
    Route::post('/applications/{application}/medical', [AdminRoomApplicationController::class, 'verifyMedical'])->name('applications.medical');
    Route::post('/applications/{application}/disciplinary', [AdminRoomApplicationController::class, 'setDisciplinary'])->name('applications.disciplinary');
    Route::post('/applications/run-allocation', [AdminRoomApplicationController::class, 'runAllocation'])->name('applications.run-allocation');
    Route::resource('rooms', RoomController::class);
    Route::post('/rooms/{room}/assign', [RoomController::class, 'assignStudent'])->name('rooms.assign');
    Route::post('/allocations/{allocation}/remove', [RoomController::class, 'removeStudent'])->name('allocations.remove');
    Route::get('/applications/{application}', [AdminRoomApplicationController::class, 'show'])->name('applications.show');
    
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/application', [RoomApplicationController::class, 'create'])->name('application.create');
    Route::post('/application', [RoomApplicationController::class, 'store'])->name('application.store');
    Route::post('/portal/fetch', [PortalFetchController::class, 'fetch'])->name('portal.fetch');
});

require __DIR__.'/auth.php';
