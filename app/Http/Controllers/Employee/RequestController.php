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
        
        $equipment = Equipment::find($validated['equipment_id']);
        NotificationHelper::notifyAdmins(
            'equipment_request',
            $equipmentRequest->id,
            'New equipment request from ' . Auth::user()->name . ' for ' . ($equipment->name ?? 'equipment'),
            'Pending'
        );
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Equipment request submitted successfully!');
    }
    
    public function exchangeRequestForm(){          
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        $availableEquipment = Equipment::where('status', 'Available')->get();
        return view('employee.requests.exchange-request', compact('myEquipment', 'availableEquipment'));
    }

    public function storeExchangeRequest(ExchangeRequestStoreRequest $request){
        $validated = $request->validated();
        
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
        
        NotificationHelper::notifyAdmins(
            'exchange_request',
            $exchangeRequest->id,
            'New exchange request from ' . Auth::user()->name,
            'Pending'
        );
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Exchange request submitted successfully!');
    }
    
    public function repairRequestForm(){     // Show Repair Request Form
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        return view('employee.requests.repair-request', compact('myEquipment'));
    }

    public function storeRepairRequest(RepairRequestStoreRequest $request){
        $validated = $request->validated();
        
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
        
        $equipment = Equipment::find($validated['equipment_id']);
        NotificationHelper::notifyAdmins(
            'repair_request',
            $repairRequest->id,
            'New repair request from ' . Auth::user()->name . ' for ' . ($equipment->name ?? 'equipment'),
            'Pending'
        );
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Repair request submitted. Admin will review it.');
    }

    public function returnRequestForm(){    // Show Return Request Form
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        return view('employee.requests.return-request', compact('myEquipment'));
    }

    public function storeReturnRequest(ReturnRequestStoreRequest $request){
        $validated = $request->validated();
        
        $returnRequest = ReturnRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'return_reason' => $validated['return_reason'],
            'equipment_condition' => $validated['equipment_condition'],
            'missing_parts' => $validated['missing_parts'] ?? null,
            'return_date' => now(),
            'status' => 'Pending'
        ]);
        
        $equipment = Equipment::find($validated['equipment_id']);
        NotificationHelper::notifyAdmins(
            'return_request',
            $returnRequest->id,
            'New return request from ' . Auth::user()->name . ' for ' . ($equipment->name ?? 'equipment'),
            'Pending'
        );
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Return request submitted. Please wait for admin approval.');
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