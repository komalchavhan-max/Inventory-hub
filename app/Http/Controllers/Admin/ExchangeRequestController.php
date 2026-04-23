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
        $requests = ExchangeRequest::with(['user', 'oldEquipment', 'requestedEquipment'])->select('exchange_requests.*');
        return DataTableService::exchangeRequestsData($requests, $request);
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
        
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Rejected';
        $exchangeRequest->admin_message = $request->rejection_message;
        $exchangeRequest->save();
        
        Notification::create([
            'user_id' => $exchangeRequest->user_id,
            'type' => 'exchange_request',
            'request_id' => $exchangeRequest->id,
            'message' => 'Your exchange request has been rejected. Reason: ' . $request->rejection_message,
            'status' => 'Rejected',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Exchange request rejected!');
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
            
            return redirect()->back()->with('success', 'Exchange processed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}