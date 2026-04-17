<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ReturnRequestStoreRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'equipment_id' => 'required|exists:equipment,id',
            'return_reason' => 'required|in:Leaving Company,Exchange,Broken,Upgrade,Other',
            'equipment_condition' => 'required|string',
            'missing_parts' => 'nullable|string',
        ];
    }

    public function messages(){
        return [
            'equipment_id.required' => 'Please select equipment to return',
            'return_reason.required' => 'Please select return reason',
            'equipment_condition.required' => 'Please describe equipment condition',
        ];
    }
}