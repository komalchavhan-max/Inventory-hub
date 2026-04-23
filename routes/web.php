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
    
    // DataTables AJAX Endpoints
    Route::get('/equipment-data', [App\Http\Controllers\Admin\EquipmentController::class, 'getEquipmentData'])->name('equipment.data');
    Route::get('/categories-data', [App\Http\Controllers\Admin\CategoryController::class, 'getCategoriesData'])->name('categories.data');
    Route::get('/requests/equipment-data', [App\Http\Controllers\Admin\EquipmentRequestController::class, 'getEquipmentRequestsData'])->name('requests.equipment.data');
    Route::get('/requests/exchange-data', [App\Http\Controllers\Admin\ExchangeRequestController::class, 'getExchangeRequestsData'])->name('requests.exchange.data');
    Route::get('/requests/repair-data', [App\Http\Controllers\Admin\RepairRequestController::class, 'getRepairRequestsData'])->name('requests.repair.data');
    Route::get('/requests/return-data', [App\Http\Controllers\Admin\ReturnRequestController::class, 'getReturnRequestsData'])->name('requests.return.data');
    Route::get('/maintenance-logs-data', [App\Http\Controllers\Admin\MaintenanceLogController::class, 'getMaintenanceLogsData'])->name('maintenance-logs.data');
    
    // Resource Routes
    Route::resource('/equipment', App\Http\Controllers\Admin\EquipmentController::class);  // Only ONE!
    Route::resource('/categories', App\Http\Controllers\Admin\CategoryController::class);
    
    // Category Routes
    Route::get('/categories/{slug}/equipment', [App\Http\Controllers\Admin\CategoryController::class, 'showEquipment'])->name('categories.equipment');
    Route::post('/equipment/{id}/assign', [App\Http\Controllers\Admin\EquipmentController::class, 'assign'])->name('equipment.assign');
    Route::post('/equipment/{id}/restore', [App\Http\Controllers\Admin\EquipmentController::class, 'restore'])->name('equipment.restore');
    
    // Request View Routes
    Route::get('/requests/equipment', [App\Http\Controllers\Admin\EquipmentRequestController::class, 'index'])->name('requests.equipment');
    Route::get('/requests/exchange', [App\Http\Controllers\Admin\ExchangeRequestController::class, 'index'])->name('requests.exchange');
    Route::get('/requests/repair', [App\Http\Controllers\Admin\RepairRequestController::class, 'index'])->name('requests.repair');
    Route::get('/requests/return', [App\Http\Controllers\Admin\ReturnRequestController::class, 'index'])->name('requests.return');
    
    // Equipment Request Actions (Keep these - they use individual controllers)
    Route::post('/requests/equipment/{id}/approve', [App\Http\Controllers\Admin\EquipmentRequestController::class, 'approve'])->name('requests.equipment.approve');
    Route::post('/requests/equipment/{id}/reject', [App\Http\Controllers\Admin\EquipmentRequestController::class, 'reject'])->name('requests.equipment.reject');
    
    // Exchange Request Actions
    Route::post('/requests/exchange/{id}/approve', [App\Http\Controllers\Admin\ExchangeRequestController::class, 'approve'])->name('requests.exchange.approve');
    Route::post('/requests/exchange/{id}/process', [App\Http\Controllers\Admin\ExchangeRequestController::class, 'process'])->name('requests.exchange.process');
    
    // Repair Request Actions
    Route::post('/requests/repair/{id}/approve', [App\Http\Controllers\Admin\RepairRequestController::class, 'approve'])->name('requests.repair.approve');
    Route::post('/requests/repair/{id}/complete', [App\Http\Controllers\Admin\RepairRequestController::class, 'complete'])->name('requests.repair.complete');
    
    // Return Request Actions
    Route::post('/requests/return/{id}/approve', [App\Http\Controllers\Admin\ReturnRequestController::class, 'approve'])->name('requests.return.approve');
    Route::post('/requests/return/{id}/complete', [App\Http\Controllers\Admin\ReturnRequestController::class, 'complete'])->name('requests.return.complete');
    
    // REJECT ROUTES - Using RequestManagementController (more complete)
    Route::post('/requests/exchange/{id}/reject', [App\Http\Controllers\Admin\RequestManagementController::class, 'rejectExchangeRequest'])->name('requests.exchange.reject');
    Route::post('/requests/repair/{id}/reject', [App\Http\Controllers\Admin\RequestManagementController::class, 'rejectRepairRequest'])->name('requests.repair.reject');
    Route::post('/requests/return/{id}/reject', [App\Http\Controllers\Admin\RequestManagementController::class, 'rejectReturnRequest'])->name('requests.return.reject');
    
    // Maintenance Logs
    Route::get('/maintenance-logs', [App\Http\Controllers\Admin\MaintenanceLogController::class, 'index'])->name('maintenance-logs.index');
    Route::get('/maintenance-logs/{id}', [App\Http\Controllers\Admin\MaintenanceLogController::class, 'show'])->name('maintenance-logs.show');
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

// ========== GUEST ROUTES ==========
Route::middleware('guest')->group(function () {
    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('throttle:3,1');
    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:3,1')->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
});

// ========== NOTIFICATION ROUTES ==========
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/fetch', [App\Http\Controllers\NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});