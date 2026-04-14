<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RepairRequest;
use App\Models\Equipment;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;

class RepairRequestController extends Controller
{
    public function index()
    {
        $requests = RepairRequest::with(['user', 'equipment'])->latest()->paginate(10);
        $pendingCount = RepairRequest::where('status', 'Pending')->count();
        $approvedCount = RepairRequest::where('status', 'Approved')->count();
        $completedCount = RepairRequest::where('status', 'Completed')->count();
        $rejectedCount = RepairRequest::where('status', 'Rejected')->count();
        return view('admin.requests.repair', compact('requests', 'pendingCount', 'approvedCount', 'completedCount', 'rejectedCount'));
    }
    
    public function approve($id)
    {
        $request = RepairRequest::findOrFail($id);
        $request->status = 'Approved';
        $request->save();
        
        $equipment = Equipment::find($request->equipment_id);
        if ($equipment) {
            $equipment->status = 'In-Repair';
            $equipment->assigned_to = null;
            $equipment->save();
        }
        
        return redirect()->back()->with('success', 'Repair request approved!');
    }
    
    public function complete($id)
    {
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Completed';
        $repairRequest->completion_date = now();
        $repairRequest->save();
        
        $equipment = Equipment::find($repairRequest->equipment_id);   // Update equipment status back to available
        if ($equipment) {
            $equipment->status = 'Available';
            $equipment->save();
        }
        
        return redirect()->back()->with('success', 'Repair completed!');
    }
    
    public function reject(Request $httpRequest, $id)
    {
        $httpRequest->validate([
            'rejection_message' => 'required|string|min:5'
        ]);
        
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = 'Rejected';
        $repairRequest->admin_message = $httpRequest->rejection_message;
        $repairRequest->save();
        
        return redirect()->back()->with('success', 'Repair request rejected! Message sent to employee.');
    }
}