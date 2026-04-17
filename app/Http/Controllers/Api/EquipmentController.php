<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Http\Requests\API\EquipmentStoreRequest;
use App\Http\Requests\API\EquipmentUpdateRequest;

class EquipmentController extends Controller
{
    public function index(Request $request){
        $query = Equipment::with('category', 'assignedUser');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if (!auth()->user()->isAdmin()) {
            $query->where('status', '!=', 'Archived');
        }

        $equipment = $query->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $equipment
        ]);
    }

    public function show($id){
        $equipment = Equipment::with('category', 'assignedUser', 'maintenanceLogs')->find($id);

        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not found'
            ], 404);
        }

        if (!auth()->user()->isAdmin() && $equipment->status == 'Archived') {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not available'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $equipment
        ]);
    }

    public function store(EquipmentStoreRequest $request){
        $data = $request->validated();
        $data['status'] = 'Available';

        $equipment = Equipment::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Equipment created successfully',
            'data' => $equipment
        ], 201);
    }

    public function update(EquipmentUpdateRequest $request, $id){
        $equipment = Equipment::find($id);

        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not found'
            ], 404);
        }

        $equipment->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Equipment updated successfully',
            'data' => $equipment
        ]);
    }

    public function destroy($id){
        $equipment = Equipment::find($id);

        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not found'
            ], 404);
        }

        if ($equipment->assigned_to && $equipment->status != 'Archived') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot archive equipment that is currently assigned'
            ], 400);
        }

        $equipment->archive();

        return response()->json([
            'success' => true,
            'message' => 'Equipment archived successfully'
        ]);
    }

    public function restore($id){
        $equipment = Equipment::find($id);

        if (!$equipment) {
            return response()->json([
                'success' => false,
                'message' => 'Equipment not found'
            ], 404);
        }

        $equipment->restoreFromArchive();

        return response()->json([
            'success' => true,
            'message' => 'Equipment restored successfully'
        ]);
    }
}