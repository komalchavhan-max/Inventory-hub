<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/dashboard';

     public function showResetForm($token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => request()->email]
        );
    }
}