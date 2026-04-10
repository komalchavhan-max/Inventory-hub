<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    // READ: Show all equipment (uses index.blade.php)
    public function index()
    {
        $equipment = Equipment::with('assignedUser')->get();
        return view('admin.equipment.index', compact('equipment'));
    }
    
    // CREATE: Show add form (uses create.blade.php)
    public function create()
    {
        return view('admin.equipment.create');
    }
    
    // CREATE: Save to database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'serial_number' => 'required|unique:equipment,serial_number',
            'category' => 'required',
            'description' => 'nullable',
            'specifications' => 'nullable',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
        ]);
        
        $validated['status'] = 'Available';
        
        Equipment::create($validated);
        
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment added successfully!');
    }
    
    // READ: Show single equipment (uses show.blade.php)
    public function show($id)
    {
        $equipment = Equipment::with('assignedUser')->findOrFail($id);
        return view('admin.equipment.show', compact('equipment'));
    }
    
    // UPDATE: Show edit form (uses edit.blade.php)
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $users = User::all();
        return view('admin.equipment.edit', compact('equipment', 'users'));
    }
    
    // UPDATE: Save changes to database
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'serial_number' => 'required|unique:equipment,serial_number,' . $id,
            'category' => 'required',
            'status' => 'required|in:Available,Assigned,In-Repair',
            'description' => 'nullable',
            'specifications' => 'nullable',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        
        $equipment->update($validated);
        
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment updated successfully!');
    }
    
    // DELETE: Remove from database
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();
        
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment deleted successfully!');
    }
}