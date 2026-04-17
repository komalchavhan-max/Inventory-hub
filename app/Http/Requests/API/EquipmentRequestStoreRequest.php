<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequestStoreRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'equipment_id' => 'required|exists:equipment,id',
            'priority' => 'required|in:Urgent,Normal,Low',
            'request_reason' => 'required|min:10',
        ];
    }

    public function messages(){
        return [
            'equipment_id.required' => 'Please select equipment',
            'priority.required' => 'Please select priority',
            'request_reason.required' => 'Please provide a reason',
            'request_reason.min' => 'Reason must be at least 10 characters',
        ];
    }
}