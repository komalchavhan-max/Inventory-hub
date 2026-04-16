<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        $id = $this->route('equipment');
        
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

    public function messages()
    {
        return [
            'name.required' => 'Equipment name is required',
            'serial_number.required' => 'Serial number is required',
            'serial_number.unique' => 'This serial number already exists',
            'category_id.required' => 'Please select a category',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
            'assigned_to.exists' => 'Selected employee does not exist',
        ];
    }
}