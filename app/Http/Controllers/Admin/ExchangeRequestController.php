<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Http\Requests\ExchangeRequestRejectRequest;

class ExchangeRequestController extends Controller
{
    public function index()
    {
        $requests = ExchangeRequest::with(['user', 'oldEquipment', 'requestedEquipment'])->latest()->paginate(10);
        $pendingCount = ExchangeRequest::where('status', 'Pending')->count();
        $approvedCount = ExchangeRequest::where('status', 'Approved')->count();
        $completedCount = ExchangeRequest::where('status', 'Completed')->count();
        $rejectedCount = ExchangeRequest::where('status', 'Rejected')->count();
        return view('admin.requests.exchange', compact('requests', 'pendingCount', 'approvedCount', 'completedCount', 'rejectedCount'));
    }
    
    public function approve($id)
    {
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Approved';
        $exchangeRequest->admin_approval_date = now();
        $exchangeRequest->save();
        
        return redirect()->back()->with('success', 'Exchange request approved!');
    }
    
    public function process($id)
    {
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        
        $oldEquipment = Equipment::find($exchangeRequest->old_equipment_id);    // Return old equipment
        if ($oldEquipment) {
            $oldEquipment->assigned_to = null;
            $oldEquipment->status = 'Available';
            $oldEquipment->save();
        }
        
        $newEquipment = Equipment::find($exchangeRequest->requested_equipment_id);   // Assign new equipment
        if ($newEquipment && $newEquipment->status == 'Available') {
            $newEquipment->assigned_to = $exchangeRequest->user_id;
            $newEquipment->status = 'Assigned';
            $newEquipment->save();
        }
        
        $exchangeRequest->status = 'Completed';
        $exchangeRequest->save();
        
        return redirect()->back()->with('success', 'Exchange processed successfully!');
    }
    
    public function reject(ExchangeRequestRejectRequest $request, $id)
    {
        $exchangeRequest = ExchangeRequest::findOrFail($id);
        $exchangeRequest->status = 'Rejected';
        $exchangeRequest->admin_message = $httpRequest->rejection_message;
        $exchangeRequest->save();
        
        return redirect()->back()->with('success', 'Exchange request rejected! Message sent to employee.');
    }
}