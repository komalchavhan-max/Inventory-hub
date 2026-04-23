<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\Equipment;
use App\Models\Notification;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
{
    public function index(){
        return view('admin.requests.return');
    }
    
    public function getReturnRequestsData(){
        $requests = ReturnRequest::with(['user', 'equipment'])->select('return_requests.*');
        return DataTableService::returnRequestsData($requests);
    }
    
    public function approve($id){
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'Approved';
        $returnRequest->save();
        
        Notification::create([
            'user_id' => $returnRequest->user_id,
            'type' => 'return_request',
            'request_id' => $returnRequest->id,
            'message' => 'Your return request has been approved. Please return the equipment to admin.',
            'status' => 'Approved',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Return request approved');
    }
    
    public function reject(Request $request, $id){
        $request->validate([
            'rejection_message' => 'required|string|min:5'
        ]);
        
        $returnRequest = ReturnRequest::findOrFail($id);
        $returnRequest->status = 'Rejected';
        $returnRequest->admin_message = $request->rejection_message;
        $returnRequest->save();
        
        Notification::create([
            'user_id' => $returnRequest->user_id,
            'type' => 'return_request',
            'request_id' => $returnRequest->id,
            'message' => 'Your return request has been rejected. Reason: ' . $request->rejection_message,
            'status' => 'Rejected',
            'is_read' => false
        ]);
        
        return redirect()->back()->with('success', 'Return request rejected!');
    }
    
    public function complete($id){
        DB::beginTransaction();
        
        try {
            $returnRequest = ReturnRequest::where('id', $id)->lockForUpdate()->first();
            
            if (!$returnRequest) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Return request not found.');
            }
            
            if ($returnRequest->status !== 'Approved') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Request must be approved before completing.');
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
            
            Notification::create([
                'user_id' => $returnRequest->user_id,
                'type' => 'return_request',
                'request_id' => $returnRequest->id,
                'message' => 'Your equipment return has been completed. Thank you.',
                'status' => 'Completed',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Return completed successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}