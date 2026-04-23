<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRequest;
use App\Models\Equipment;
use App\Models\Notification;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\DB;

class EquipmentRequestController extends Controller
{
    public function index(){
        return view('admin.requests.equipment');
    }
    
    public function getEquipmentRequestsData(){
        $requests = EquipmentRequest::with(['user', 'equipment'])->select('equipment_requests.*');
        return DataTableService::equipmentRequestsData($requests);
    }

    public function approve($id){
        DB::beginTransaction();
       
        try {
            $equipmentRequest = EquipmentRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$equipmentRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request not found.');
            }
            
            if ($equipmentRequest->status !== 'Pending') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request has already been processed.');
            }
            
            $equipmentRequest->status = 'Approved';
            $equipmentRequest->approved_date = now();
            $equipmentRequest->save();

            $equipment = Equipment::where('id', $equipmentRequest->equipment_id)->lockForUpdate()->first();
            
            if ($equipment) {
                if ($equipment->status !== 'Available') {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Equipment is not available for assignment.');
                }
                
                $equipment->assigned_to = $equipmentRequest->user_id;
                $equipment->status = 'Assigned';
                $equipment->save();
                
                $equipmentRequest->status = 'Fulfilled';
                $equipmentRequest->save();

                NotificationHelper::notifyUser(
                    $equipmentRequest->user_id,
                    'equipment_request',
                    $equipmentRequest->id,
                    'Your equipment request for ' . ($equipment->name ?? 'equipment') . ' has been approved and assigned to you.',
                    'Approved'
                );
                
                Notification::create([
                    'user_id' => auth()->id(),
                    'type' => 'equipment_request',
                    'request_id' => $equipmentRequest->id,
                    'message' => 'You approved equipment request #' . $equipmentRequest->id . ' for ' . ($equipment->name ?? 'equipment'),
                    'status' => 'Approved',
                    'is_read' => false
                ]);
                
                DB::commit();
                return redirect()->back()->with('success', 'Equipment request approved and assigned successfully!');
            }
            
            DB::rollBack();
            return redirect()->back()->with('error', 'Equipment not found!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id){
        $request->validate([
            'rejection_message' => 'required|string|min:5'
        ]);
        
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->status = 'Rejected';
        $equipmentRequest->admin_message = $request->rejection_message;
        $equipmentRequest->save();
        
        NotificationHelper::notifyUser(
            $equipmentRequest->user_id,
            'equipment_request',
            $equipmentRequest->id,
            'Your equipment request has been rejected. Reason: ' . $request->rejection_message,
            'Rejected'
        );
        
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'equipment_request',
            'request_id' => $equipmentRequest->id,
            'message' => 'You rejected equipment request #' . $equipmentRequest->id . '. Reason: ' . $request->rejection_message,
            'status' => 'Rejected',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Equipment request rejected!');
    }
}