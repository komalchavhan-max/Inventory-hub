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
    
    public function getMaintenanceLogsData(Request $request){
        $logs = MaintenanceLog::select(
                'maintenance_logs.*',
                'equipment.name as equipment_name',
                'repair_requests.status as repair_status'
            )
            ->leftJoin('equipment', 'maintenance_logs.equipment_id', '=', 'equipment.id')
            ->leftJoin('repair_requests', function($join) {
                $join->on('maintenance_logs.equipment_id', '=', 'repair_requests.equipment_id')
                    ->whereRaw('repair_requests.id = (SELECT id FROM repair_requests WHERE equipment_id = maintenance_logs.equipment_id ORDER BY created_at DESC LIMIT 1)');
            });
        
        if ($request->has('order')) {
            $columnIndex = $request->input('order')[0]['column'];
            $sortDirection = $request->input('order')[0]['dir'];
            
            $columns = [
                0 => 'maintenance_logs.id',
                1 => 'equipment.name',
                2 => 'maintenance_logs.issue_description',
                3 => 'repair_requests.status',
                4 => 'maintenance_logs.repair_date',
                5 => 'maintenance_logs.created_at',
            ];
            
            if (isset($columns[$columnIndex])) {
                $logs->orderBy($columns[$columnIndex], $sortDirection);
            }
        } else {
            $logs->orderBy('maintenance_logs.id', 'desc');
        }
        
        return DataTableService::maintenanceLogsData($logs);
    }
    
    public function show($id){
        $log = MaintenanceLog::with('equipment')->findOrFail($id);
        return view('admin.maintenance-logs.show', compact('log'));
    }
}