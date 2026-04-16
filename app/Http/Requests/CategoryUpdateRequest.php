<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        $id = $this->route('category');
        
        return [
            'name' => 'required|unique:categories,name,' . $id . '|min:2|max:50',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.unique' => 'This category already exists',
            'name.min' => 'Category name must be at least 2 characters',
        ];
    }
}