<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\EquipmentStoreRequest;
use App\Http\Requests\EquipmentUpdateRequest;

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
    
    public function store(EquipmentStoreRequest $request)
    {
        $validated = $request->validate();

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
    
    public function update(EquipmentUpdateRequest $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        
       $validated = $request->validated();
        
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
        if ($equipment->assigned_to && $equipment->status != 'Archived') {
            return redirect()->back()->with('error', 'Cannot archive equipment that is currently assigned.');
        }  
        $equipment->archive();  

        return redirect()->route('admin.equipment.index')->with('success', 'Equipment archived. It will not be visible to employees.');
    }

    public function restore($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->restoreFromArchive();
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment restored successfully!');
    }
}