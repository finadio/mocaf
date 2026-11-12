<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Khusus Administrator.'], 403);
            }

            return redirect()->route('admin.login')->with('error', 'Silakan masuk sebagai admin terlebih dahulu.');
        }

        return $next($request);
    }
}

