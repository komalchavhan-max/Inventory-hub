<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EquipmentController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\RequestController;
use Illuminate\Support\Facades\Route;

// ========== Publi API========
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// ========== Protected API ==========
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Equipment
    Route::get('/equipment', [EquipmentController::class, 'index']);
    Route::get('/equipment/{id}', [EquipmentController::class, 'show']);
    Route::post('/equipment', [EquipmentController::class, 'store']);
    Route::put('/equipment/{id}', [EquipmentController::class, 'update']);
    Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);
    Route::post('/equipment/{id}/restore', [EquipmentController::class, 'restore']);
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    
    // Employee Requests
    Route::post('/requests/equipment', [RequestController::class, 'storeEquipmentRequest']);
    Route::post('/requests/exchange', [RequestController::class, 'storeExchangeRequest']);
    Route::post('/requests/repair', [RequestController::class, 'storeRepairRequest']);
    Route::post('/requests/return', [RequestController::class, 'storeReturnRequest']);
    Route::get('/my-requests', [RequestController::class, 'myRequests']);
    
    // Admin Only APIs
    Route::middleware('admin')->group(function () {
        // Equipment Requests
        Route::get('/admin/requests/equipment', [RequestController::class, 'equipmentRequests']);
        Route::post('/admin/requests/equipment/{id}/approve', [RequestController::class, 'approveEquipmentRequest']);
        Route::post('/admin/requests/equipment/{id}/reject', [RequestController::class, 'rejectEquipmentRequest']);
        
        // Exchange Requests
        Route::get('/admin/requests/exchange', [RequestController::class, 'exchangeRequests']);
        Route::post('/admin/requests/exchange/{id}/approve', [RequestController::class, 'approveExchangeRequest']);
        Route::post('/admin/requests/exchange/{id}/process', [RequestController::class, 'processExchangeRequest']);
        Route::post('/admin/requests/exchange/{id}/reject', [RequestController::class, 'rejectExchangeRequest']);
        
        // Repair Requests
        Route::get('/admin/requests/repair', [RequestController::class, 'repairRequests']);
        Route::post('/admin/requests/repair/{id}/approve', [RequestController::class, 'approveRepairRequest']);
        Route::post('/admin/requests/repair/{id}/complete', [RequestController::class, 'completeRepairRequest']);
        Route::post('/admin/requests/repair/{id}/reject', [RequestController::class, 'rejectRepairRequest']);
        
        // Return Requests
        Route::get('/admin/requests/return', [RequestController::class, 'returnRequests']);
        Route::post('/admin/requests/return/{id}/approve', [RequestController::class, 'approveReturnRequest']);
        Route::post('/admin/requests/return/{id}/complete', [RequestController::class, 'completeReturnRequest']);
        Route::post('/admin/requests/return/{id}/reject', [RequestController::class, 'rejectReturnRequest']);
        
        // Dashboard Stats
        Route::get('/admin/dashboard/stats', [RequestController::class, 'dashboardStats']);
    });
});