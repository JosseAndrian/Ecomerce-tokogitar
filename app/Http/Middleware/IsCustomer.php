<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    public function handle(Request $request, Closure $next)
    {
        // Izinkan jika user sudah login DAN (dia adalah customer ATAU dia adalah admin)
        if (auth()->check() && (auth()->user()->isCustomer() || auth()->user()->isAdmin())) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }
}
