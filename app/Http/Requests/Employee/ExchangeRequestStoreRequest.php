<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeRequestStoreRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'old_equipment_id' => 'required|exists:equipment,id',
            'requested_equipment_id' => 'required|exists:equipment,id',
            'exchange_reason' => 'required|min:10',
            'old_equipment_condition' => 'required|string',
            'has_damage' => 'boolean',
            'damage_description' => 'nullable|required_if:has_damage,1'
        ];
    }

    public function messages(){
        return [
            'old_equipment_id.required' => 'Please select equipment to exchange',
            'requested_equipment_id.required' => 'Please select requested equipment',
            'exchange_reason.required' => 'Please provide a reason for exchange',
            'exchange_reason.min' => 'Reason must be at least 10 characters',
            'old_equipment_condition.required' => 'Please describe equipment condition',
            'damage_description.required_if' => 'Please describe the damage',
        ];
    }
}