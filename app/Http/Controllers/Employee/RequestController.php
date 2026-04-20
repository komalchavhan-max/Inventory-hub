<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function equipmentRequestForm(){
        $availableEquipment = Equipment::where('status', 'Available')->get();
        return view('employee.requests.equipment-request', compact('availableEquipment'));
    }
    
    public function storeEquipmentRequest(Request $request){
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'priority' => 'required|in:Urgent,Normal,Low',
            'request_reason' => 'required|min:10'
        ]);
        
        EquipmentRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'request_date' => now(),
            'priority' => $validated['priority'],
            'request_reason' => $validated['request_reason'],
            'status' => 'Pending'
        ]);
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Equipment request submitted successfully!');
    }
    
    public function exchangeRequestForm(){
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        $availableEquipment = Equipment::where('status', 'Available')->get();
        return view('employee.requests.exchange-request', compact('myEquipment', 'availableEquipment'));
    }
    
    public function storeExchangeRequest(Request $request){
        $validated = $request->validate([
            'old_equipment_id' => 'required|exists:equipment,id',
            'requested_equipment_id' => 'required|exists:equipment,id',
            'exchange_reason' => 'required|min:10',
            'old_equipment_condition' => 'required',
            'has_damage' => 'boolean',
            'damage_description' => 'nullable|required_if:has_damage,1'
        ]);
        
        ExchangeRequest::create([
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
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Exchange request submitted successfully!');
    }
    
    public function repairRequestForm(){
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        return view('employee.requests.repair-request', compact('myEquipment'));
    }

    public function storeRepairRequest(Request $request){
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'issue_description' => 'required|min:10',
            'urgency' => 'required|in:Critical,High,Medium,Low',
            'location' => 'nullable|string',
            'photos_available' => 'boolean'
        ]);
        
        RepairRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'issue_description' => $validated['issue_description'],
            'urgency' => $validated['urgency'],
            'location' => $validated['location'] ?? null,
            'photos_available' => $validated['photos_available'] ?? false,
            'request_date' => now(),
            'status' => 'Pending'
        ]);
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Repair request submitted. Admin will review it.');
    }

    public function returnRequestForm(){
        $myEquipment = Equipment::where('assigned_to', Auth::id())->get();
        return view('employee.requests.return-request', compact('myEquipment'));
    }

    public function storeReturnRequest(Request $request){
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'return_reason' => 'required|in:Leaving Company,Exchange,Broken,Upgrade,Other',
            'equipment_condition' => 'required',
            'missing_parts' => 'nullable|string'
        ]);
        
        ReturnRequest::create([
            'user_id' => Auth::id(),
            'equipment_id' => $validated['equipment_id'],
            'return_reason' => $validated['return_reason'],
            'equipment_condition' => $validated['equipment_condition'],
            'missing_parts' => $validated['missing_parts'] ?? null,
            'return_date' => now(),
            'status' => 'Pending'
        ]);
        
        return redirect()->route('employee.dashboard')
            ->with('success', 'Return request submitted. Please wait for admin approval.');
    }

    public function myRequests(){
        $equipmentRequests = EquipmentRequest::where('user_id', Auth::id())->get();
        $exchangeRequests = ExchangeRequest::where('user_id', Auth::id())->get();
        $repairRequests = RepairRequest::where('user_id', Auth::id())->get();
        $returnRequests = ReturnRequest::where('user_id', Auth::id())->get();
        
        return view('employee.requests.my-requests', compact(
            'equipmentRequests', 'exchangeRequests', 'repairRequests', 'returnRequests'
        ));
    }
}