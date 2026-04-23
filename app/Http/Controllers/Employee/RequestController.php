<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;
use App\Http\Requests\Employee\EquipmentRequestStoreRequest;
use App\Http\Requests\Employee\ExchangeRequestStoreRequest;
use App\Http\Requests\Employee\RepairRequestStoreRequest;
use App\Http\Requests\Employee\ReturnRequestStoreRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Gate;

class RequestController extends Controller
{
    public function equipmentRequestForm(){    // Show Equipment Request Form
        $availableEquipment = Equipment::where('status', 'Available')->get();
        return view('employee.requests.equipment-request', compact('availableEquipment'));
    }

    public function storeEquipmentRequest(EquipmentRequestStoreRequest $request){
        $validated = $request->validated();
        
        $equipmentRequest = EquipmentRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'request_date' => now(),
            'priority' => $validated['priority'],
            'request_reason' => $validated['request_reason'],
            'status' => 'Pending'
        ]);
        
        $equipment = Equipment::find($validated['equipment_id']);      // Get equipment name
        
        $admins = User::where('role', 'admin')->get();   // Notify ALL admins about new request
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'equipment_request',
                'request_id' => $equipmentRequest->id,
                'message' => 'New equipment request from ' . Auth::user()->name . ' for ' . ($equipment->name ?? 'equipment'),
                'status' => 'Pending',
                'is_read' => false
            ]);
        }
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Equipment request submitted successfully!');
    }
    
    public function exchangeRequestForm(){          
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        $availableEquipment = Equipment::where('status', 'Available')->get();
        return view('employee.requests.exchange-request', compact('myEquipment', 'availableEquipment'));
    }

    public function storeExchangeRequest(ExchangeRequestStoreRequest $request)
    {
        $validated = $request->validated();
        $oldEquipment = Equipment::find($validated['old_equipment_id']);
        
        if (!$oldEquipment) {
            return redirect()->back()->with('error', 'Equipment not found.');
        }
        
        if ($oldEquipment->assigned_to !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only exchange equipment that is assigned to you.');
        }

        $requestedEquipment = Equipment::find($validated['requested_equipment_id']);
        
        if (!$requestedEquipment) {
            return redirect()->back()->with('error', 'Requested equipment not found.');
        }
        
        if ($requestedEquipment->status !== 'Available') {
            return redirect()->back()->with('error', 'Requested equipment is not available.');
        }

        $exchangeRequest = ExchangeRequest::create([
            'user_id' => Auth::id(),
            'old_equipment_id' => $validated['old_equipment_id'],
            'requested_equipment_id' => $validated['requested_equipment_id'],
            'exchange_reason' => $validated['exchange_reason'],
            'old_equipment_condition' => $validated['old_equipment_condition'],
            'has_damage' => $validated['has_damage'] ?? false,
            'damage_description' => $validated['damage_description'] ?? null,
            'request_date' => now(),
            'status' => 'Pending'
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'exchange_request',
                'request_id' => $exchangeRequest->id,
                'message' => 'New exchange request from ' . Auth::user()->name,
                'status' => 'Pending',
                'is_read' => false
            ]);
        }
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Exchange request submitted successfully!');
    }

    
    public function repairRequestForm(){     // Show Repair Request Form
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        return view('employee.requests.repair-request', compact('myEquipment'));
    }

    public function storeRepairRequest(RepairRequestStoreRequest $request){
        $validated = $request->validated();

        $equipment = Equipment::find($validated['equipment_id']);
        
        if (!$equipment) {
            return redirect()->back()->with('error', 'Equipment not found.');
        }
        
        if ($equipment->assigned_to !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only report repair for equipment assigned to you.');
        }
        
        $repairRequest = RepairRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'issue_description' => $validated['issue_description'],
            'urgency' => $validated['urgency'],
            'location' => $validated['location'] ?? null,
            'photos_available' => $validated['photos_available'] ?? false,
            'request_date' => now(),
            'status' => 'Pending'
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'repair_request',
                'request_id' => $repairRequest->id,
                'message' => 'New repair request from ' . Auth::user()->name,
                'status' => 'Pending',
                'is_read' => false
            ]);
        }
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Repair request submitted successfully!');
    }

    public function returnRequestForm(){    // Show Return Request Form
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        return view('employee.requests.return-request', compact('myEquipment'));
    }

    public function storeReturnRequest(ReturnRequestStoreRequest $request){
        $validated = $request->validated();
        $equipment = Equipment::find($validated['equipment_id']);
        
        if (!$equipment) {
            return redirect()->back()->with('error', 'Equipment not found.');
        }
        
        if ($equipment->assigned_to !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only return equipment that is assigned to you.');
        }
        
        $returnRequest = ReturnRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'return_reason' => $validated['return_reason'],
            'equipment_condition' => $validated['equipment_condition'],
            'missing_parts' => $validated['missing_parts'] ?? null,
            'return_date' => now(),
            'status' => 'Pending'
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'return_request',
                'request_id' => $returnRequest->id,
                'message' => 'New return request from ' . Auth::user()->name,
                'status' => 'Pending',
                'is_read' => false
            ]);
        }
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Return request submitted successfully!');
    }

    public function myRequests(){    // Show All My Requests
        $equipmentRequests = EquipmentRequest::where('user_id', Auth::id())->get();
        $exchangeRequests = ExchangeRequest::where('user_id', Auth::id())->get();
        $repairRequests = RepairRequest::where('user_id', Auth::id())->get();
        $returnRequests = ReturnRequest::where('user_id', Auth::id())->get();
        
        return view('employee.requests.my-requests', compact(
            'equipmentRequests', 'exchangeRequests', 'repairRequests', 'returnRequests'
        ));
    }
}