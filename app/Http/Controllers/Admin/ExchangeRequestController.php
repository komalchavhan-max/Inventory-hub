<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use App\Models\Equipment;
use App\Models\Notification;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;

class ExchangeRequestController extends Controller
{
    public function index(){
        return view('admin.requests.exchange');
    }
    
    public function getExchangeRequestsData(Request $request){
        $requests = ExchangeRequest::select(
                'exchange_requests.*',
                'users.name as employee_name',
                'old_equipment.name as old_equipment_name',
                'new_equipment.name as requested_equipment_name'
            )
            ->leftJoin('users', 'exchange_requests.user_id', '=', 'users.id')
            ->leftJoin('equipment as old_equipment', 'exchange_requests.old_equipment_id', '=', 'old_equipment.id')
            ->leftJoin('equipment as new_equipment', 'exchange_requests.requested_equipment_id', '=', 'new_equipment.id');
      
        if ($request->has('order')) {
            $columnIndex = $request->input('order')[0]['column'];
            $sortDirection = $request->input('order')[0]['dir'];
           
            $columns = [
                0 => 'exchange_requests.id',
                1 => 'users.name',              
                2 => 'old_equipment.name',     
                3 => 'new_equipment.name',      
                4 => 'exchange_requests.exchange_reason',
                5 => 'exchange_requests.has_damage',
                6 => 'exchange_requests.request_date',
                7 => 'exchange_requests.status',
            ];
            
            if (isset($columns[$columnIndex])) {
                $requests->orderBy($columns[$columnIndex], $sortDirection);
            }
        } else {
            $requests->orderBy('exchange_requests.id', 'desc');
        }
        
        return DataTableService::exchangeRequestsData($requests);
    }
    
    public function approve($id){
        DB::beginTransaction();
        
        try {
            $exchangeRequest = ExchangeRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$exchangeRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Exchange request not found.');
            }
            
            if ($exchangeRequest->status !== 'Pending') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request has already been processed.');
            }
            
            $exchangeRequest->status = 'Approved';
            $exchangeRequest->admin_approval_date = now();
            $exchangeRequest->save();
            
            DB::commit();
            
            Notification::create([
                'user_id' => $exchangeRequest->user_id,
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'Your exchange request has been approved.',
                'status' => 'Approved',
                'is_read' => false
            ]);
            
            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'You approved exchange request #' . $exchangeRequest->id,
                'status' => 'Approved',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Exchange request approved!');
            
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
            $exchangeRequest = ExchangeRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$exchangeRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Exchange request not found.');
            }
            
            if ($exchangeRequest->status !== 'Pending') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request has already been processed.');
            }
            
            $exchangeRequest->status = 'Rejected';
            $exchangeRequest->admin_message = $request->rejection_message;
            $exchangeRequest->save();
            
            DB::commit();
            
            // Notify employee
            Notification::create([
                'user_id' => $exchangeRequest->user_id,
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'Your exchange request has been rejected. Reason: ' . $request->rejection_message,
                'status' => 'Rejected',
                'is_read' => false
            ]);
            
            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'You rejected exchange request #' . $exchangeRequest->id . '. Reason: ' . $request->rejection_message,
                'status' => 'Rejected',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Exchange request rejected!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    public function process($id){
        DB::beginTransaction();
        
        try {
            $exchangeRequest = ExchangeRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$exchangeRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Exchange request not found.');
            }
            
            if ($exchangeRequest->status !== 'Approved') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request must be approved before processing.');
            }
            
            $oldEquipment = Equipment::where('id', $exchangeRequest->old_equipment_id)->lockForUpdate()->first();
            $newEquipment = Equipment::where('id', $exchangeRequest->requested_equipment_id)->lockForUpdate()->first();
            
            if (!$oldEquipment || !$newEquipment) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Equipment not found.');
            }
            
            if ($newEquipment->status !== 'Available') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Requested equipment is not available.');
            }
            
            $oldEquipment->assigned_to = null;
            $oldEquipment->status = 'Available';
            $oldEquipment->save();
            
            $newEquipment->assigned_to = $exchangeRequest->user_id;
            $newEquipment->status = 'Assigned';
            $newEquipment->save();
            
            $exchangeRequest->status = 'Completed';
            $exchangeRequest->save();
            
            DB::commit();
            
            Notification::create([
                'user_id' => $exchangeRequest->user_id,
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'Your exchange request has been processed successfully.',
                'status' => 'Completed',
                'is_read' => false
            ]);
 
            Notification::create([
                'user_id' => auth()->id(),
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'You processed exchange request #' . $exchangeRequest->id,
                'status' => 'Completed',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Exchange processed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}