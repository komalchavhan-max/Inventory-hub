<?php

namespace App\Http\Requests\API;

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
            'serial_number.required' => 'Serial number is required',
            'serial_number.unique' => 'This serial number already exists',
            'category_id.required' => 'Please select a category',
        ];
    }
}