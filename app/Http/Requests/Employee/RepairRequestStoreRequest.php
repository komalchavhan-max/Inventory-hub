<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class RepairRequestStoreRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'equipment_id' => 'required|exists:equipment,id',
            'issue_description' => 'required|min:10',
            'urgency' => 'required|in:Critical,High,Medium,Low',
            'location' => 'nullable|string',
            'photos_available' => 'boolean'
        ];
    }

    public function messages(){
        return [
            'equipment_id.required' => 'Please select equipment',
            'issue_description.required' => 'Please describe the issue',
            'issue_description.min' => 'Issue description must be at least 10 characters',
            'urgency.required' => 'Please select urgency level',
            'urgency.in' => 'Invalid urgency selected',
        ];
    }
}