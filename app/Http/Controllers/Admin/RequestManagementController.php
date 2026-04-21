<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;
use App\Services\NotificationService;
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

    public function approveEquipmentRequest($id){            //Equipment Request
        $request = EquipmentRequest::findOrFail($id);
        $request->approve();
        
        NotificationService::equipmentRequest(
            $request->user_id,
            $request->id,
            $request->equipment->name,
            'approved'
        );
        
        return redirect()->back()->with('success', 'Request approved successfully');
    }
    
    public function rejectEquipmentRequest(Request $request, $id){
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->reject($request->rejection_reason);
        
        NotificationService::equipmentRequest(
            $equipmentRequest->user_id,
            $equipmentRequest->id,
            $equipmentRequest->equipment->name,
            'rejected',
            $request->rejection_reason ?? 'Not specified'
        );
        
        return redirect()->back()->with('success', 'Request rejected');
    }
    
    public function approveExchangeRequest($id){             //Exchange Request
        $request = ExchangeRequest::findOrFail($id);
        $request->approve();                              // auto-process the exchange
        
        NotificationService::exchangeRequest(
            $request->user_id,
            $request->id,
            $request->requestedEquipment->name ?? 'equipment',
            'approved'
        );
        
        return redirect()->back()->with('success', 'Exchange request approved and processed');
    }
    
    public function rejectExchangeRequest(Request $request, $id){
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Rejected';
        $exchangeRequest->admin_message = $request->rejection_message;
        $exchangeRequest->save();
        
        NotificationService::exchangeRequest(
            $exchangeRequest->user_id,
            $exchangeRequest->id,
            $exchangeRequest->requestedEquipment->name ?? 'equipment',
            'rejected',
            $request->rejection_message ?? 'Not specified'
        );
        
        return redirect()->back()->with('success', 'Exchange request rejected');
    }
 
    public function approveRepairRequest($id){           //Repair Request
        $request = RepairRequest::findOrFail($id);
        $request->approve();
        
        NotificationService::repairRequest(
            $request->user_id,
            $request->id,
            $request->equipment->name,
            'approved'
        );
        
        return redirect()->back()->with('success', 'Repair request approved');
    }
    
    public function rejectRepairRequest(Request $request, $id){
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Rejected';
        $repairRequest->admin_message = $request->rejection_message;
        $repairRequest->save();
        
        NotificationService::repairRequest(
            $repairRequest->user_id,
            $repairRequest->id,
            $repairRequest->equipment->name,
            'rejected',
            $request->rejection_message ?? 'Not specified'
        );
        
        return redirect()->back()->with('success', 'Repair request rejected');
    }
    
    public function completeRepair($id){
        $request = RepairRequest::findOrFail($id);
        $request->complete();
        
        NotificationService::repairRequest(
            $request->user_id,
            $request->id,
            $request->equipment->name,
            'completed'
        );
        
        return redirect()->back()->with('success', 'Repair marked as complete');
    }

    public function approveReturnRequest($id){              //Return Request
        $request = ReturnRequest::findOrFail($id);
        $request->approve();
        
        NotificationService::returnRequest(
            $request->user_id,
            $request->id,
            $request->equipment->name,
            'approved'
        );
        
        return redirect()->back()->with('success', 'Return request approved');
    }
    
    public function rejectReturnRequest(Request $request, $id){
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'Rejected';
        $returnRequest->admin_message = $request->rejection_message;
        $returnRequest->save();
        
        NotificationService::returnRequest(
            $returnRequest->user_id,
            $returnRequest->id,
            $returnRequest->equipment->name,
            'rejected',
            $request->rejection_message ?? 'Not specified'
        );
        
        return redirect()->back()->with('success', 'Return request rejected');
    }
    
    public function completeReturn($id){
        $request = ReturnRequest::findOrFail($id);
        $request->complete();
        
        NotificationService::returnRequest(
            $request->user_id,
            $request->id,
            $request->equipment->name,
            'completed'
        );
        
        return redirect()->back()->with('success', 'Return completed successfully');
    }
}