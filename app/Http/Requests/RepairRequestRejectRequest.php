<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairRequestRejectRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'rejection_message' => 'required|string|min:5',
        ];
    }

    public function messages()
    {
        return [
            'rejection_message.required' => 'Please provide a reason for rejection',
            'rejection_message.min' => 'Rejection message must be at least 5 characters',
        ];
    }
}