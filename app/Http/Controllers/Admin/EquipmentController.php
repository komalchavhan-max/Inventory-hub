<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::with('assignedUser', 'category')->get();
        return view('admin.equipment.index', compact('equipment'));
    }
    
    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.equipment.create', compact('categories', 'users'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'serial_number' => 'required|unique:equipment,serial_number',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'specifications' => 'nullable',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
        ]);

        if (!empty($validated['specifications'])) {
            $decoded = json_decode($validated['specifications']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['specifications' => 'Invalid JSON format'])->withInput();
            }
            $validated['specifications'] = $validated['specifications'];
        }
        
        $validated['status'] = 'Available';
        Equipment::create($validated);
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment added successfully!');
    }
    
    public function show($id)
    {
        $equipment = Equipment::with('assignedUser', 'category')->findOrFail($id);
        return view('admin.equipment.show', compact('equipment'));
    }
    
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $users = User::all();
        $categories = Category::all();
        return view('admin.equipment.edit', compact('equipment', 'users', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'serial_number' => 'required|unique:equipment,serial_number,' . $id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:Available,Assigned,In-Repair',
            'description' => 'nullable',
            'specifications' => 'nullable',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        
        if (!empty($validated['specifications'])) {
            $decoded = json_decode($validated['specifications']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['specifications' => 'Invalid JSON format'])->withInput();
            }
            $validated['specifications'] = $validated['specifications'];
        }
        
        $equipment->update($validated);
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment updated successfully!');
    }
    
    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment deleted successfully!');
    }
}