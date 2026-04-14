<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEquipment = Equipment::count();
        $available = Equipment::where('status', 'Available')->count();
        $assigned = Equipment::where('status', 'Assigned')->count();
        $archived = Equipment::where('status', 'Archived')->count();
        $inRepair = Equipment::where('status', 'In-Repair')->count();
        
        $recentEquipment = Equipment::with('category')->latest()->take(5)->get();
        
        $pendingEquipmentRequests = EquipmentRequest::where('status', 'Pending')->count();
        $pendingExchangeRequests = ExchangeRequest::where('status', 'Pending')->count();
        $pendingRepairRequests = RepairRequest::where('status', 'Pending')->count();
        $pendingReturnRequests = ReturnRequest::where('status', 'Pending')->count();
        
        return view('admin.dashboard.index', compact(
            'totalEquipment', 'available', 'assigned', 'inRepair','archived',
            'recentEquipment',
            'pendingEquipmentRequests', 'pendingExchangeRequests',
            'pendingRepairRequests', 'pendingReturnRequests'
        ));
    }
}