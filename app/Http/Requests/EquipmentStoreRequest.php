<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentStoreRequest extends FormRequest
{
    public function authorize(){
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules(){
        return [
            'name' => 'required|min:3|max:255',
            'serial_number' => 'required|unique:equipment,serial_number',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'condition' => 'nullable|in:New,Good,Fair,Poor',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Equipment name is required',
            'name.min' => 'Equipment name must be at least 3 characters',
            'serial_number.required' => 'Serial number is required',
            'serial_number.unique' => 'This serial number already exists',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category is invalid',
            'purchase_date.date' => 'Please enter a valid purchase date',
            'warranty_until.date' => 'Please enter a valid warranty date',
            'condition.in' => 'Invalid condition selected',
        ];
    }
}