<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestManagementController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function pendingRequests(){
        $equipmentRequests = EquipmentRequest::where('status', 'Pending')->get();
        $exchangeRequests = ExchangeRequest::where('status', 'Pending')->get();
        $repairRequests = RepairRequest::where('status', 'Pending')->get();
        $returnRequests = ReturnRequest::where('status', 'Pending')->get();
        
        return view('admin.requests.pending', compact(
            'equipmentRequests', 'exchangeRequests', 'repairRequests', 'returnRequests'
        ));
    }
    
    public function approveEquipmentRequest($id){
        $request = EquipmentRequest::findOrFail($id);
        $request->approve();
        
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'equipment_request',
            'request_id' => $request->id,
            'message' => 'Your equipment request for ' . $request->equipment->name . ' has been approved.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Request approved successfully');
    }
    
    public function rejectEquipmentRequest(Request $request, $id){
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->reject($request->rejection_reason);
        
        Notification::create([
            'user_id' => $equipmentRequest->user_id,
            'type' => 'equipment_request',
            'request_id' => $equipmentRequest->id,
            'message' => 'Your equipment request was rejected. Reason: ' . ($request->rejection_reason ?? 'Not specified'),
            'status' => 'Rejected',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Request rejected');
    }
    
    public function approveExchangeRequest($id){
        $request = ExchangeRequest::findOrFail($id);
        $request->approve();                 // This will auto-process the exchange
        
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'exchange_request',
            'request_id' => $request->id,
            'message' => 'Your exchange request has been approved and processed.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Exchange request approved and processed');
    }
    
    public function approveRepairRequest($id){
        $request = RepairRequest::findOrFail($id);
        $request->approve();
        
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'repair_request',
            'request_id' => $request->id,
            'message' => 'Your repair request has been approved. Equipment will be repaired soon.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Repair request approved');
    }
    
    public function completeRepair($id){
        $request = RepairRequest::findOrFail($id);
        $request->complete();
        
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'repair_request',
            'request_id' => $request->id,
            'message' => 'Your equipment repair is complete. You can now request the equipment again.',
            'status' => 'Completed',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Repair marked as complete');
    }
    
    public function approveReturnRequest($id){
        $request = ReturnRequest::findOrFail($id);
        $request->approve();
        
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'return_request',
            'request_id' => $request->id,
            'message' => 'Your return request has been approved. Please return the equipment to admin.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Return request approved');
    }
    
    public function completeReturn($id){
        $request = ReturnRequest::findOrFail($id);
        $request->complete();
        
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'return_request',
            'request_id' => $request->id,
            'message' => 'Your equipment return has been completed. Thank you.',
            'status' => 'Completed',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Return completed successfully');
    }
}