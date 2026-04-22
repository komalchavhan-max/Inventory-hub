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
    public function index(){ 
        return view('admin.equipment.index');
    }

    public function getEquipmentData(){
        $equipment = Equipment::with(['category', 'assignedUser'])->select('equipment.*');
        
        return DataTables::of($equipment)
            ->addColumn('category_name', function($row) {
                return $row->category->name ?? 'Uncategorized';
            })
            ->addColumn('assigned_to_name', function($row) {
                return $row->assignedUser->name ?? 'Not Assigned';
            })
            ->addColumn('action', function($row){
                $view = '<a href="'.route('admin.equipment.show', $row->id).'" class="action-btn view" title="View" aria-label="View"><i class="bi bi-eye"></i></a>';

                if ($row->status == 'Archived'){
                    return '<div class="action-group">'.$view.'
                                <form action="'.route('admin.equipment.restore', $row->id).'" method="POST">
                                    '.csrf_field().'
                                    <button type="submit" class="action-btn restore" title="Restore" aria-label="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                </form>
                            </div>';
                }

                return '<div class="action-group">'.$view.'
                            <a href="'.route('admin.equipment.edit', $row->id).'" class="action-btn edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="'.route('admin.equipment.destroy', $row->id).'" method="POST" onsubmit="return confirm(\'Archive this equipment?\');">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="action-btn archive" title="Archive" aria-label="Archive"><i class="bi bi-archive"></i></button>
                            </form>
                        </div>';
            })
            ->editColumn('status', function($row){
                $map = [
                    'Available' => ['tint-success', 'Available'],
                    'Assigned'  => ['tint-warning', 'Assigned'],
                    'In-Repair' => ['tint-danger',  'In Repair'],
                    'Archived'  => ['tint-slate',   'Archived'],
                ];
                [$cls, $label] = $map[$row->status] ?? ['tint-slate', $row->status];
                return '<span class="badge-pill '.$cls.'">'.$label.'</span>';
            })
            ->editColumn('condition', function($row){
                $map = ['New' => 'tint-info', 'Good' => 'tint-success', 'Fair' => 'tint-warning', 'Poor' => 'tint-danger'];
                $cls = $map[$row->condition] ?? 'tint-slate';
                return '<span class="badge-pill '.$cls.'">'.$row->condition.'</span>';
            })
            ->rawColumns(['action', 'status', 'condition'])
            ->make(true);
    }
    
    public function create(){
        $categories = Category::all();
        $users = User::all();
        return view('admin.equipment.create', compact('categories', 'users'));
    }
    
    public function store(EquipmentStoreRequest $request){
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
            $decoded = json_decode($validated['specifications']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['specifications' => 'Invalid JSON format'])->withInput();
            }
            $validated['specifications'] = $validated['specifications'];
        }
        
        $equipment->update($validated);
        
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment updated successfully!');
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