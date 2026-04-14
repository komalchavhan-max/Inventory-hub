<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRequest;
use App\Models\Equipment;
use App\Models\Notification;
use Illuminate\Http\Request;

class EquipmentRequestController extends Controller
{
    public function index()
    {
        $requests = EquipmentRequest::with(['user', 'equipment'])->latest()->paginate(10);
        $pendingCount = EquipmentRequest::where('status', 'Pending')->count();
        $approvedCount = EquipmentRequest::where('status', 'Approved')->count();
        $rejectedCount = EquipmentRequest::where('status', 'Rejected')->count();
        $fulfilledCount = EquipmentRequest::where('status', 'Fulfilled')->count();
        return view('admin.requests.equipment', compact('requests', 'pendingCount', 'approvedCount', 'rejectedCount', 'fulfilledCount'));
    }
    
    public function approve($id)
    {
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->status = 'Approved';
        $equipmentRequest->approved_date = now();
        $equipmentRequest->save();
        

        $equipment = Equipment::find($equipmentRequest->equipment_id);
        if ($equipment && $equipment->status == 'Available') {
            $equipment->assigned_to = $equipmentRequest->user_id;
            $equipment->status = 'Assigned';
            $equipment->save();
            $equipmentRequest->status = 'Fulfilled';
            $equipmentRequest->save();
        }
        
        return redirect()->back()->with('success', 'Equipment request approved successfully!');
    }
    
    public function reject(Request $httpRequest, $id)
    {
        $httpRequest->validate([
            'rejection_message' => 'required|string|min:5'
        ]);
        
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->status = 'Rejected';
        $equipmentRequest->admin_message = $httpRequest->rejection_message;
        $equipmentRequest->save();
        
        return redirect()->back()->with('success', 'Equipment request rejected! Message sent to employee.');
    }

    public function showRejectForm($id)
    {
        $request = EquipmentRequest::findOrFail($id);
        return view('admin.requests.reject-modal', compact('request'));
    }
}