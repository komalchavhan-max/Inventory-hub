<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Equipment;
use App\Services\DataTableService;
use Illuminate\Http\Request;

class MaintenanceLogController extends Controller
{
    public function index(){
        return view('admin.maintenance-logs.index');
    }
    
    public function getMaintenanceLogsData(){
        $logs = MaintenanceLog::with('equipment')->select('maintenance_logs.*');
        return DataTableService::maintenanceLogsData($logs);
    }
    
    public function show($id){
        $log = MaintenanceLog::with('equipment')->findOrFail($id);
        return view('admin.maintenance-logs.show', compact('log'));
    }
}