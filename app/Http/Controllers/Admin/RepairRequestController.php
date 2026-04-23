<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use App\Models\Equipment;
use App\Models\MaintenanceLog;
use App\Models\Notification;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        
        try {
            $repairRequest = RepairRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$repairRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Repair request not found.');
            }
            
            if ($repairRequest->status !== 'Pending') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request has already been processed.');
            }
            
            $repairRequest->status = 'Approved';
            $repairRequest->save();
            
            $equipment = Equipment::where('id', $repairRequest->equipment_id)->lockForUpdate()->first();
            if ($equipment) {
                $equipment->status = 'In-Repair';
                $equipment->assigned_to = null;
                $equipment->save();
            }
            
            DB::commit();
            
            Notification::create([
                'user_id' => $repairRequest->user_id,
                'type' => 'repair_request',
                'request_id' => $repairRequest->id,
                'message' => 'Your repair request for ' . ($equipment->name ?? 'equipment') . ' has been approved.',
                'status' => 'Approved',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Repair request approved!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
        DB::beginTransaction();
        
        try {
            $repairRequest = RepairRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$repairRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Repair request not found.');
            }
            
            if ($repairRequest->status !== 'Approved') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request must be approved before completing.');
            }
            
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
            
            $equipment = Equipment::where('id', $repairRequest->equipment_id)->lockForUpdate()->first();
            if ($equipment) {
                $equipment->status = 'Available';
                $equipment->save();
            }
            
            DB::commit();
            
            Notification::create([
                'user_id' => $repairRequest->user_id,
                'type' => 'repair_request',
                'request_id' => $repairRequest->id,
                'message' => 'Your equipment repair is complete. You can now request the equipment again.',
                'status' => 'Completed',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Repair completed!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}