<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\RequestController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('employee.dashboard');
})->middleware('auth')->name('dashboard');

// ========== ADMIN ROUTES ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/equipment', App\Http\Controllers\Admin\EquipmentController::class);
    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class); 
    Route::get('/categories/{slug}/equipment', [App\Http\Controllers\Admin\CategoryController::class, 'showEquipment'])->name('categories.equipment');
    Route::post('/equipment/{id}/assign', [App\Http\Controllers\Admin\EquipmentController::class, 'assign'])->name('equipment.assign');
});

// ========== EMPLOYEE ROUTES ==========
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    
    // Equipment Request
    Route::get('/requests/equipment', [RequestController::class, 'equipmentRequestForm'])->name('requests.equipment.form');
    Route::post('/requests/equipment', [RequestController::class, 'storeEquipmentRequest'])->name('requests.equipment.store');
    
    // Exchange Request
    Route::get('/requests/exchange', [RequestController::class, 'exchangeRequestForm'])->name('requests.exchange.form');
    Route::post('/requests/exchange', [RequestController::class, 'storeExchangeRequest'])->name('requests.exchange.store');
    
    // Repair Request
    Route::get('/requests/repair', [RequestController::class, 'repairRequestForm'])->name('requests.repair.form');
    Route::post('/requests/repair', [RequestController::class, 'storeRepairRequest'])->name('requests.repair.store');
    
    // Return Request
    Route::get('/requests/return', [RequestController::class, 'returnRequestForm'])->name('requests.return.form');
    Route::post('/requests/return', [RequestController::class, 'storeReturnRequest'])->name('requests.return.store');
    
    // My Requests
    Route::get('/my-requests', [RequestController::class, 'myRequests'])->name('my-requests');
});