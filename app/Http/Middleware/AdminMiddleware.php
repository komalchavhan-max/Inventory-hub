<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check using new role relationship
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access. Admin only area.');
        }
        
        return $next($request);
    }
}