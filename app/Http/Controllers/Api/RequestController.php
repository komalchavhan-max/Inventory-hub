<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;
use App\Models\MaintenanceLog;
use App\Http\Requests\API\EquipmentRequestStoreRequest;
use App\Http\Requests\API\ExchangeRequestStoreRequest;
use App\Http\Requests\API\RepairRequestStoreRequest;
use App\Http\Requests\API\ReturnRequestStoreRequest;
use App\Http\Requests\API\RejectRequest;
use App\Http\Requests\API\ExchangeRequestRejectRequest;
use App\Http\Requests\API\RepairRequestRejectRequest;
use App\Http\Requests\API\ReturnRequestRejectRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function storeEquipmentRequest(EquipmentRequestStoreRequest $request) {        //Employee store equipment request
        $equipmentRequest = EquipmentRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'request_date' => now(),
            'priority' => $request->priority,
            'request_reason' => $request->request_reason,
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Equipment request submitted successfully',
            'data' => $equipmentRequest
        ], 201);
    }

    public function storeExchangeRequest(ExchangeRequestStoreRequest $request){      //  Employee Exchange Equipment Request
        $exchangeRequest = ExchangeRequest::create([
            'user_id' => Auth::id(),
            'old_equipment_id' => $request->old_equipment_id,
            'requested_equipment_id' => $request->requested_equipment_id,
            'exchange_reason' => $request->exchange_reason,
            'old_equipment_condition' => $request->old_equipment_condition,
            'has_damage' => $request->has_damage ?? false,
            'damage_description' => $request->damage_description ?? null,
            'request_date' => now(),
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Exchange request submitted successfully',
            'data' => $exchangeRequest
        ], 201);
    }

    public function storeRepairRequest(RepairRequestStoreRequest $request){       //Employee Store repair request
        $repairRequest = RepairRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'issue_description' => $request->issue_description,
            'urgency' => $request->urgency,
            'location' => $request->location ?? null,
            'photos_available' => $request->photos_available ?? false,
            'request_date' => now(),
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Repair request submitted successfully',
            'data' => $repairRequest
        ], 201);
    }

    public function storeReturnRequest(ReturnRequestStoreRequest $request){  //Employee Store Return Request
        $returnRequest = ReturnRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $request->equipment_id,
            'return_reason' => $request->return_reason,
            'equipment_condition' => $request->equipment_condition,
            'missing_parts' => $request->missing_parts ?? null,
            'return_date' => now(),
            'status' => 'Pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Return request submitted successfully',
            'data' => $returnRequest
        ], 201);
    }

    public function myRequests(){  //Employee Get My Requests
        return response()->json([
            'success' => true,
            'data' => [
                'equipment_requests' => EquipmentRequest::where('user_id', Auth::id())->with('equipment')->get(),
                'exchange_requests' => ExchangeRequest::where('user_id', Auth::id())->with('oldEquipment', 'requestedEquipment')->get(),
                'repair_requests' => RepairRequest::where('user_id', Auth::id())->with('equipment')->get(),
                'return_requests' => ReturnRequest::where('user_id', Auth::id())->with('equipment')->get(),
            ]
        ]);
    }

    public function equipmentRequests(){    //Admin Get all equipment requests
        $requests = EquipmentRequest::with(['user', 'equipment'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function exchangeRequests(){    //Admin Get all exchange requests
        $requests = ExchangeRequest::with(['user', 'oldEquipment', 'requestedEquipment'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function repairRequests(){    //Admin Get all repair requests
        $requests = RepairRequest::with(['user', 'equipment'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function returnRequests(){      //Admin Get all return requests
        $requests = ReturnRequest::with(['user', 'equipment'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function approveEquipmentRequest($id){
        DB::beginTransaction();
        
        try {
            $request = EquipmentRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$request) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Request not found'], 404);
            }
            
            if ($request->status !== 'Pending') {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Request already processed'], 400);
            }
            
            $request->status = 'Approved';
            $request->approved_date = now();
            $request->save();

            $equipment = Equipment::where('id', $request->equipment_id)->lockForUpdate()->first();
            if ($equipment && $equipment->status == 'Available') {
                $equipment->assigned_to = $request->user_id;
                $equipment->status = 'Assigned';
                $equipment->save();
                $request->status = 'Fulfilled';
                $request->save();
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Equipment request approved successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function rejectEquipmentRequest(RejectRequest $request, $id){     //Admin Reject Equipment Request
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->status = 'Rejected';
        $equipmentRequest->admin_message = $request->rejection_message;
        $equipmentRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Equipment request rejected'
        ]);
    }

    public function approveExchangeRequest($id){   //Admin Approve Exchange Request
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Approved';
        $exchangeRequest->admin_approval_date = now();
        $exchangeRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Exchange request approved'
        ]);
    }

    public function processExchangeRequest($id){
        DB::beginTransaction();
        
        try {
            $exchangeRequest = ExchangeRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$exchangeRequest) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Exchange request not found'], 404);
            }
            
            if ($exchangeRequest->status !== 'Approved') {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Request must be approved first'], 400);
            }
            
            $oldEquipment = Equipment::where('id', $exchangeRequest->old_equipment_id)->lockForUpdate()->first();
            $newEquipment = Equipment::where('id', $exchangeRequest->requested_equipment_id)->lockForUpdate()->first();
            
            if ($newEquipment && $newEquipment->status == 'Available') {
                $oldEquipment->assigned_to = null;
                $oldEquipment->status = 'Available';
                $oldEquipment->save();
                
                $newEquipment->assigned_to = $exchangeRequest->user_id;
                $newEquipment->status = 'Assigned';
                $newEquipment->save();
            }
            
            $exchangeRequest->status = 'Completed';
            $exchangeRequest->save();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exchange processed successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function rejectExchangeRequest(ExchangeRequestRejectRequest $request, $id){    // Reject Exchange Request
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Rejected';
        $exchangeRequest->admin_message = $request->rejection_message;
        $exchangeRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Exchange request rejected'
        ]);
    }

    public function approveRepairRequest($id){
        DB::beginTransaction();
        
        try {
            $repairRequest = RepairRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$repairRequest) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Repair request not found'], 404);
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

            return response()->json([
                'success' => true,
                'message' => 'Repair request approved'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function completeRepairRequest($id){
        DB::beginTransaction();
        
        try {
            $repairRequest = RepairRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$repairRequest) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Repair request not found'], 404);
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

            return response()->json([
                'success' => true,
                'message' => 'Repair completed successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function rejectRepairRequest(RepairRequestRejectRequest $request, $id){    // Reject Repair Request
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Rejected';
        $repairRequest->admin_message = $request->rejection_message;
        $repairRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Repair request rejected'
        ]);
    }

    public function approveReturnRequest($id){          // Approve Return Request
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'Approved';
        $returnRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Return request approved'
        ]);
    }

    public function completeReturnRequest($id){
        DB::beginTransaction();
        
        try {
            $returnRequest = ReturnRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$returnRequest) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Return request not found'], 404);
            }
            
            $returnRequest->status = 'Completed';
            $returnRequest->admin_verified = true;
            $returnRequest->save();

            $equipment = Equipment::where('id', $returnRequest->equipment_id)->lockForUpdate()->first();
            if ($equipment) {
                $equipment->assigned_to = null;
                $equipment->status = 'Available';
                $equipment->save();
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Return completed successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function rejectReturnRequest(ReturnRequestRejectRequest $request, $id){      // Reject Return Request
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'Rejected';
        $returnRequest->admin_message = $request->rejection_message;
        $returnRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Return request rejected'
        ]);
    }

    public function dashboardStats(){      //Dashboard Statices
        return response()->json([
            'success' => true,
            'data' => [
                'total_equipment' => Equipment::count(),
                'available' => Equipment::where('status', 'Available')->count(),
                'assigned' => Equipment::where('status', 'Assigned')->count(),
                'in_repair' => Equipment::where('status', 'In-Repair')->count(),
                'archived' => Equipment::where('status', 'Archived')->count(),
                'pending_equipment_requests' => EquipmentRequest::where('status', 'Pending')->count(),
                'pending_exchange_requests' => ExchangeRequest::where('status', 'Pending')->count(),
                'pending_repair_requests' => RepairRequest::where('status', 'Pending')->count(),
                'pending_return_requests' => ReturnRequest::where('status', 'Pending')->count(),
            ]
        ]);
    }
}