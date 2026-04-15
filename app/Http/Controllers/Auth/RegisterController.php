<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\RegisterRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $this->create($validated);
        return redirect()->route('login')->with('success', 'Registration successful! Please login with your credentials.');
    }

    protected function create(array $data)
    {
        $employeeRole = \App\Models\Role::where('name', 'employee')->first();
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => $employeeRole ? $employeeRole->id : null,
        ]);
    }
    
    public function showRegistrationForm()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('employee.dashboard');
        }
        return view('auth.register');
    }
}