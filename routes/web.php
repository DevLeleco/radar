<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Radar\ClientController;
use App\Http\Controllers\Radar\ClientDashboardController;
use App\Http\Controllers\Radar\EquipmentController;
use App\Http\Controllers\Radar\QrCodeController;
use App\Http\Controllers\Radar\TechnicianController;
use App\Http\Controllers\Radar\TechnicianDashboardController;
use App\Http\Controllers\Radar\TicketController;
use App\Http\Controllers\Radar\UnitController;
use App\Http\Controllers\Radar\UserController;
use App\Http\Controllers\Radar\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'operations.home')->middleware('auth')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])
    ->middleware(['auth', 'radar.role:2'])
    ->name('client.dashboard');

Route::get('/technician/dashboard', [TechnicianDashboardController::class, 'index'])
    ->middleware(['auth', 'radar.role:3'])
    ->name('technician.dashboard');

Route::middleware(['auth', 'radar.role:1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', UserController::class)->except(['create', 'edit', 'update']);
    Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/password-reset', [UserController::class, 'sendPasswordReset'])->name('users.password.reset');
    Route::resource('clients', ClientController::class)->except(['create', 'edit', 'update']);
    Route::post('/clients/update', [ClientController::class, 'update'])->name('clients.update');
    Route::resource('units', UnitController::class)->except(['create', 'edit', 'update']);
    Route::post('/units/update', [UnitController::class, 'update'])->name('units.update');
    Route::resource('equipment', EquipmentController::class)->except(['create', 'edit', 'update']);
    Route::post('/equipment/update', [EquipmentController::class, 'update'])->name('equipment.update');
    Route::resource('work-orders', WorkOrderController::class)->except(['create', 'edit', 'update']);
    Route::post('/work-orders/update', [WorkOrderController::class, 'update'])->name('work-orders.update');
    Route::resource('technicians', TechnicianController::class)->only(['index', 'store', 'destroy']);

    Route::post('/tickets/notes', [TicketController::class, 'updateNotes'])->name('tickets.notes.update');
});

Route::middleware(['auth', 'radar.role:1,2'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets/history', [TicketController::class, 'history'])->name('tickets.history');
});

Route::middleware(['auth', 'radar.role:1,3'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets/queue', [TicketController::class, 'queue'])->name('tickets.queue');
});

Route::middleware(['auth', 'radar.role:3'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tickets/{ticket}/work-order', [TicketController::class, 'showForTechnician'])->whereNumber('ticket')->name('tickets.work-order.show');
    Route::post('/tickets/start', [TicketController::class, 'start'])->name('tickets.start');
    Route::post('/tickets/complete', [TicketController::class, 'complete'])->name('tickets.complete');
});

Route::middleware(['auth', 'radar.role:1,2'])->prefix('lookups')->name('lookups.')->group(function () {
    Route::post('/units-by-client', [EquipmentController::class, 'unitsByClient'])->name('units.by-client');
    Route::post('/equipment-by-unit', [UnitController::class, 'equipmentByUnit'])->name('equipment.by-unit');
});

Route::middleware(['auth', 'radar.role:1,2'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/open-from-qr/{equipmentId}', [TicketController::class, 'openFromQr'])->whereNumber('equipmentId')->name('tickets.from-qr.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->whereNumber('ticket')->name('tickets.show');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->whereNumber('ticket')->name('tickets.destroy');

    Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr-codes.index');
    Route::post('/qr-codes/unit', [QrCodeController::class, 'showUnit'])->name('qr-codes.units.show');
});
