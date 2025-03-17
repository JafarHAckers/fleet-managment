<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->email === 'admin@example.com') {
            return $next($request);
        }
        
        return redirect('/')->with('error', 'Unauthorized access. Admin permissions required.');
    }
}