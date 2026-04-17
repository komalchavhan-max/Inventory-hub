<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize(){
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules() {
        $id = $this->route('id');
        
        return [
            'name' => 'required|unique:categories,name,' . $id . '|min:2|max:50',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ];
    }
}