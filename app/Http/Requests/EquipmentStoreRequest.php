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
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'specifications' => 'nullable|string|max:5000',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'specifications' => 'nullable|json',
            'condition' => 'nullable|in:Good,Fair,Poor',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Equipment name is required',
            'name.min' => 'Equipment name must be at least 3 characters',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category is invalid',
            'purchase_date.date.before_or_equal' => 'Purchase date cannot be in the future',
            'warranty_until.date.after' => 'Warranty expiry must be after purchase date',
            'specifications.json' => 'Specifications must be valid JSON format',
            'condition.in' => 'Invalid condition selected',
        ];
    }
}