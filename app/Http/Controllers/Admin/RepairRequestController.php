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
    
    public function getRepairRequestsData(Request $request){
        $requests = RepairRequest::select('repair_requests.*', 
                'users.name as employee_name',
                'equipment.name as equipment_name'
            )
            ->leftJoin('users', 'repair_requests.user_id', '=', 'users.id')
            ->leftJoin('equipment', 'repair_requests.equipment_id', '=', 'equipment.id');
        
        if ($request->has('order')) {
            $columnIndex = $request->input('order')[0]['column'];
            $sortDirection = $request->input('order')[0]['dir'];
            
            $columns = [
                0 => 'repair_requests.id',
                1 => 'users.name',
                2 => 'equipment.name',
                3 => 'repair_requests.issue_description',
                4 => 'repair_requests.urgency',
                5 => 'repair_requests.request_date',
                6 => 'repair_requests.status',
            ];
            
            if (isset($columns[$columnIndex])) {
                $requests->orderBy($columns[$columnIndex], $sortDirection);
            }
        } else {
            $requests->orderBy('repair_requests.id', 'desc');
        }
        
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

            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'repair_request',
                'request_id' => $repairRequest->id,
                'message' => 'You approved repair request #' . $repairRequest->id,
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
            
            $repairRequest->status = 'Rejected';
            $repairRequest->admin_message = $request->rejection_message;
            $repairRequest->save();
            
            DB::commit();
            
            Notification::create([
                'user_id' => $repairRequest->user_id,
                'type' => 'repair_request',
                'request_id' => $repairRequest->id,
                'message' => 'Your repair request has been rejected. Reason: ' . $request->rejection_message,
                'status' => 'Rejected',
                'is_read' => false
            ]);
            
            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'repair_request',
                'request_id' => $repairRequest->id,
                'message' => 'You rejected repair request #' . $repairRequest->id . '. Reason: ' . $request->rejection_message,
                'status' => 'Rejected',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Repair request rejected!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    public function complete($id){
        $repairRequest = RepairRequest::findOrFail($id);
        
        $log = MaintenanceLog::create([
            'equipment_id' => $repairRequest->equipment_id,
            'issue_description' => $repairRequest->issue_description,
            'cost' => request('cost', 0),
            'repair_date' => now(),
            'status' => 'Completed' 
        ]);
    
        $repairRequest->status = 'Completed';
        $repairRequest->save();
        
        $equipment = Equipment::find($repairRequest->equipment_id);
        if ($equipment) {
            $equipment->status = 'Available';
            $equipment->save();
        }
        
        return redirect()->back()->with('success', 'Repair completed successfully');
    }
}