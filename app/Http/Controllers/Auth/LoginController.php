<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function redirectTo()
    {
        if (auth()->user()->role && auth()->user()->role->name === 'admin') {
            return '/admin/dashboard';
        }
        return '/employee/dashboard';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
}