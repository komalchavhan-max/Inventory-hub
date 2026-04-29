<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\EquipmentStoreRequest;
use App\Http\Requests\EquipmentUpdateRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Services\DataTableService;

class EquipmentController extends Controller
{
    public function index(Request $request){ 
        return view('admin.equipment.index');
    }

    public function getEquipmentData(Request $request){
        $equipment = Equipment::query()
            ->select('equipment.*','categories.name as category_name','users.name as assigned_to_name')
            ->leftJoin('categories', 'equipment.category_id', '=', 'categories.id')
            ->leftJoin('users', 'equipment.assigned_to', '=', 'users.id');
        
        if ($request->has('status') && $request->status != '') {
            $equipment->where('equipment.status', $request->status);
        }
        
        return DataTableService::equipmentData($equipment);
    }
    
    public function create(){
        $categories = Category::all();
        $users = User::all();
        return view('admin.equipment.create', compact('categories', 'users'));
    }
    
    public function store(EquipmentStoreRequest $request){
        $validated = $request->validated();
        
        if (!empty($validated['specifications'])) {
            if (json_decode($validated['specifications']) === null) {
                $text = $validated['specifications'];
                $jsonArray = [];

                if (strpos($text, ':') !== false || strpos($text, '=') !== false) {
                    $pairs = preg_split('/[,\n]/', $text);
                    foreach ($pairs as $pair) {
                        $parts = preg_split('/[:=]/', $pair);
                        if (count($parts) == 2) {
                            $key = trim($parts[0]);
                            $value = trim($parts[1]);
                            $jsonArray[$key] = $value;
                        }
                    }
                } else {
                    $jsonArray['specification'] = $text;
                }
                
                $validated['specifications'] = json_encode($jsonArray);
            }
        }
        
        $validated['status'] = 'Available';
        Equipment::create($validated);
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment added successfully!');
    }
    
    public function show($id){
        $equipment = Equipment::with('assignedUser', 'category')->findOrFail($id);
        return view('admin.equipment.show', compact('equipment'));
    }
    
    public function edit($id){
        $equipment = Equipment::findOrFail($id);
        $users = User::all();
        $categories = Category::all();
        return view('admin.equipment.edit', compact('equipment', 'users', 'categories'));
    }
    
    public function update(EquipmentUpdateRequest $request, $id){
        $equipment = Equipment::findOrFail($id);
        
        $validated = $request->validated();
        
        if (!empty($validated['specifications'])) {
            if (json_decode($validated['specifications']) === null) {
                $text = $validated['specifications'];
                $jsonArray = [];

                if (strpos($text, ':') !== false || strpos($text, '=') !== false) {
                    $pairs = preg_split('/[,\n]/', $text);
                    foreach ($pairs as $pair) {
                        $parts = preg_split('/[:=]/', $pair);
                        if (count($parts) == 2) {
                            $key = trim($parts[0]);
                            $value = trim($parts[1]);
                            $jsonArray[$key] = $value;
                        }
                    }
                } else {
                    $jsonArray['specification'] = $text;
                }
                
                $validated['specifications'] = json_encode($jsonArray);
            }
        }
        
        $equipment->update($validated);
        
        return redirect()->route('admin.equipment.index')
            ->with('success', 'Equipment "' . $equipment->name . '" updated successfully!');
    }
    
    public function destroy($id){
        $equipment = Equipment::findOrFail($id); 
        if ($equipment->assigned_to && $equipment->status != 'Archived') {
            return redirect()->back()->with('error', 'Cannot archive equipment that is currently assigned.');
        }  
        $equipment->archive();  

        return redirect()->route('admin.equipment.index')->with('success', 'Equipment archived. It will not be visible to employees.');
    }

    public function restore($id){
        $equipment = Equipment::findOrFail($id);
        $equipment->restoreFromArchive();
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment restored successfully!');
    }
}