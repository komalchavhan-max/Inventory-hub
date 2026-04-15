<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Equipment;
use Illuminate\Http\Request;

class MaintenanceLogController extends Controller
{
    public function index()
    {
        $logs = MaintenanceLog::with('equipment')->latest()->paginate(10);
        return view('admin.maintenance-logs.index', compact('logs'));
    }
    
    public function show($id)
    {
        $log = MaintenanceLog::with('equipment')->findOrFail($id);
        return view('admin.maintenance-logs.show', compact('log'));
    }
}