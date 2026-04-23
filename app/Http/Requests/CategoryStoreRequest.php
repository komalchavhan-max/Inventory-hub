<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    public function authorize(){
        return auth()->user() && auth()->user()->isAdmin();
    }

    public function rules(){
        return [
            'name' => 'required|unique:categories|min:2|max:50',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Category name is required',
            'name.unique' => 'This category already exists',
            'name.min' => 'Category name must be at least 2 characters',
        ];
    }
}