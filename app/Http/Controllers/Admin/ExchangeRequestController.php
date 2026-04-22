<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use App\Models\Equipment;
use App\Models\Notification;
use App\Services\DataTableService;
use Illuminate\Http\Request;

class ExchangeRequestController extends Controller
{
    public function index(){
        return view('admin.requests.exchange');
    }
    
    public function getExchangeRequestsData(){
        $requests = ExchangeRequest::with(['user', 'oldEquipment', 'requestedEquipment'])->select('exchange_requests.*');
        return DataTableService::exchangeRequestsData($requests);
    }
    
    public function approve($id){
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Approved';
        $exchangeRequest->admin_approval_date = now();
        $exchangeRequest->save();
        
        Notification::create([
            'user_id' => $exchangeRequest->user_id,
            'type' => 'exchange_request',
            'request_id' => $exchangeRequest->id,
            'message' => 'Your exchange request has been approved.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Exchange request approved!');
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
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        
        $oldEquipment = Equipment::find($exchangeRequest->old_equipment_id);
        if ($oldEquipment) {
            $oldEquipment->assigned_to = null;
            $oldEquipment->status = 'Available';
            $oldEquipment->save();
        }
        
        $newEquipment = Equipment::find($exchangeRequest->requested_equipment_id);
        if ($newEquipment && $newEquipment->status == 'Available') {
            $newEquipment->assigned_to = $exchangeRequest->user_id;
            $newEquipment->status = 'Assigned';
            $newEquipment->save();
        }
        
        $exchangeRequest->status = 'Completed';
        $exchangeRequest->save();
        
        Notification::create([
            'user_id' => $exchangeRequest->user_id,
            'type' => 'exchange_request',
            'request_id' => $exchangeRequest->id,
            'message' => 'Your exchange request has been processed successfully.',
            'status' => 'Completed',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Exchange processed successfully!');
    }
}