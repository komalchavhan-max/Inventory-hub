<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{   
    public function index(){   
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        $recentEquipment = Equipment::with('category')->latest()->take(5)->get();
        $recentRequests = collect();
        $recentRequests = $recentRequests->concat(EquipmentRequest::where('user_id', Auth::id())->latest()->take(3)->get());
        $recentRequests = $recentRequests->concat(ExchangeRequest::where('user_id', Auth::id())->latest()->take(3)->get());
        $recentRequests = $recentRequests->concat(RepairRequest::where('user_id', Auth::id())->latest()->take(3)->get());
        $recentRequests = $recentRequests->concat(ReturnRequest::where('user_id', Auth::id())->latest()->take(3)->get());
        $recentRequests = $recentRequests->sortByDesc('created_at')->take(5);
        
        return view('employee.dashboard.index', compact('myEquipment', 'recentEquipment', 'recentRequests'));
    }
}