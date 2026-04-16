<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\Equipment;
use App\Http\Requests\ReturnRequestRejectRequest;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $requests = ReturnRequest::with(['user', 'equipment'])->latest()->paginate(10);
        $pendingCount = ReturnRequest::where('status', 'Pending')->count();
        $approvedCount = ReturnRequest::where('status', 'Approved')->count();
        $completedCount = ReturnRequest::where('status', 'Completed')->count();
        $rejectedCount = ReturnRequest::where('status', 'Rejected')->count();
        return view('admin.requests.return', compact('requests', 'pendingCount', 'approvedCount', 'completedCount', 'rejectedCount'));
    }
    
    public function approve($id)
    {
        $request = ReturnRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();
        
        return redirect()->back()->with('success', 'Return request approved. Please verify equipment.');
    }
    
    public function complete($id)
    {
        $request = ReturnRequest::findOrFail($id);
        $request->status = 'Completed';
        $request->admin_verified = true;
        $request->save();
        
        $equipment = Equipment::find($request->equipment_id);
        if ($equipment) {
            $equipment->assigned_to = null;
            $equipment->status = 'Available';
            $equipment->save();
        }
        
        return redirect()->back()->with('success', 'Return completed! Equipment is now available.');
    }

    public function reject(ReturnRequestRejectRequest $request, $id)
    { 
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'Rejected';
        $returnRequest->admin_message = $httpRequest->rejection_message;
        $returnRequest->save();
        
        return redirect()->back()->with('success', 'Return request rejected! Message sent to employee.');
    }
}