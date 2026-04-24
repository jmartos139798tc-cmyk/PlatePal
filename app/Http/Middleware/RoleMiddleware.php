<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            if ($role === 'caterer') {
                return redirect()->route('caterer.login')->with('error', 'Please sign in as a caterer.');
            }
            
            if ($role === 'admin') {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }

            return redirect()->route('login')->with('error', 'Please sign in to continue.');
        }

        return $next($request);
    }
}
