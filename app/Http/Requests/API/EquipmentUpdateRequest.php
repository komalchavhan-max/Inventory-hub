<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentUpdateRequest extends FormRequest
{
    public function authorize(){
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules(){
        $id = $this->route('id');
        
        return [
            'name' => 'required|min:3|max:255',
            'serial_number' => 'required|unique:equipment,serial_number,' . $id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:Available,Assigned,In-Repair,Archived',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}