<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequestStoreRequest extends FormRequest
{
    public function authorize(){
        return true;              // Any authenticated user can submit
    }

    public function rules(){
        return [
            'equipment_id' => 'required|exists:equipment,id',
            'priority' => 'required|in:Urgent,Normal,Low',
            'request_reason' => 'required|min:10|max:1000',
        ];
    }

    public function messages(){
        return [
            'equipment_id.required' => 'Please select equipment',
            'equipment_id.exists' => 'Selected equipment is invalid',
            'priority.required' => 'Please select priority',
            'priority.in' => 'Invalid priority selected',
            'request_reason.required' => 'Please provide a reason',
            'request_reason.min' => 'Reason must be at least 10 characters',
            'request_reason.max' => 'Reason cannot exceed 1000 characters',
        ];
    }
}