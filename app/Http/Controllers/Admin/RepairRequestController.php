<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use App\Models\Equipment;
use App\Models\MaintenanceLog;
use App\Models\Notification;
use App\Services\DataTableService;
use Illuminate\Http\Request;

class RepairRequestController extends Controller
{
    public function index(){
        return view('admin.requests.repair');
    }
    
    public function getRepairRequestsData(){
        $requests = RepairRequest::with(['user', 'equipment'])->select('repair_requests.*');
        return DataTableService::repairRequestsData($requests);
    }
    
    public function approve($id){
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Approved';
        $repairRequest->save();
        
        $equipment = Equipment::find($repairRequest->equipment_id);
        if ($equipment) {
            $equipment->status = 'In-Repair';
            $equipment->assigned_to = null;
            $equipment->save();
        }
        
        Notification::create([
            'user_id' => $repairRequest->user_id,
            'type' => 'repair_request',
            'request_id' => $repairRequest->id,
            'message' => 'Your repair request for ' . ($equipment->name ?? 'equipment') . ' has been approved.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Repair request approved!');
    }
    
    public function reject(Request $request, $id){
        $request->validate([
            'rejection_message' => 'required|string|min:5'
        ]);
        
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Rejected';
        $repairRequest->admin_message = $request->rejection_message;
        $repairRequest->save();
        
        Notification::create([
            'user_id' => $repairRequest->user_id,
            'type' => 'repair_request',
            'request_id' => $repairRequest->id,
            'message' => 'Your repair request has been rejected. Reason: ' . $request->rejection_message,
            'status' => 'Rejected',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Repair request rejected!');
    }
    
    public function complete($id){
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Completed';
        $repairRequest->completion_date = now();
        $repairRequest->save();
        
        MaintenanceLog::create([
            'equipment_id' => $repairRequest->equipment_id,
            'issue_description' => $repairRequest->issue_description,
            'cost' => 0,
            'technician_name' => 'Pending',
            'repair_date' => now()
        ]);
        
        $equipment = Equipment::find($repairRequest->equipment_id);
        if ($equipment) {
            $equipment->status = 'Available';
            $equipment->save();
        }
        
        Notification::create([
            'user_id' => $repairRequest->user_id,
            'type' => 'repair_request',
            'request_id' => $repairRequest->id,
            'message' => 'Your equipment repair is complete. You can now request the equipment again.',
            'status' => 'Completed',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Repair completed!');
    }
}