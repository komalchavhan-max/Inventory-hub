<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EquipmentController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\RequestController;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC API (No Authentication Required) ==========
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');

// ========== PROTECTED API (Authentication Required) ==========
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // User Management
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Equipment Management
    Route::get('/equipment', [EquipmentController::class, 'index']);
    Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
    Route::post('/equipment', [EquipmentController::class, 'store']);
    Route::put('/equipment/{id}', [EquipmentController::class, 'update']);
    Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);
    Route::post('/equipment/{id}/restore', [EquipmentController::class, 'restore']);
    
    // Category Management
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    
    // Request Management (Employee)
    Route::post('/requests/equipment', [RequestController::class, 'storeEquipmentRequest']);
    Route::post('/requests/exchange', [RequestController::class, 'storeExchangeRequest']);
    Route::post('/requests/repair', [RequestController::class, 'storeRepairRequest']);
    Route::post('/requests/return', [RequestController::class, 'storeReturnRequest']);
    Route::get('/my-requests', [RequestController::class, 'myRequests']);
    
    // Admin Only APIs
    Route::middleware('admin')->group(function () {
        Route::get('/admin/requests/equipment', [RequestController::class, 'equipmentRequests']);
        Route::post('/admin/requests/equipment/{id}/approve', [RequestController::class, 'approveEquipmentRequest']);
        Route::post('/admin/requests/equipment/{id}/reject', [RequestController::class, 'rejectEquipmentRequest']);
        
        Route::get('/admin/requests/exchange', [RequestController::class, 'exchangeRequests']);
        Route::post('/admin/requests/exchange/{id}/approve', [RequestController::class, 'approveExchangeRequest']);
        Route::post('/admin/requests/exchange/{id}/reject', [RequestController::class, 'rejectExchangeRequest']);
        Route::post('/admin/requests/exchange/{id}/process', [RequestController::class, 'processExchangeRequest']);
        
        Route::get('/admin/requests/repair', [RequestController::class, 'repairRequests']);
        Route::post('/admin/requests/repair/{id}/approve', [RequestController::class, 'approveRepairRequest']);
        Route::post('/admin/requests/repair/{id}/reject', [RequestController::class, 'rejectRepairRequest']);
        Route::post('/admin/requests/repair/{id}/complete', [RequestController::class, 'completeRepairRequest']);
        
        Route::get('/admin/requests/return', [RequestController::class, 'returnRequests']);
        Route::post('/admin/requests/return/{id}/approve', [RequestController::class, 'approveReturnRequest']);
        Route::post('/admin/requests/return/{id}/reject', [RequestController::class, 'rejectReturnRequest']);
        Route::post('/admin/requests/return/{id}/complete', [RequestController::class, 'completeReturnRequest']);
        
        Route::get('/admin/dashboard/stats', [RequestController::class, 'dashboardStats']);
    });
});