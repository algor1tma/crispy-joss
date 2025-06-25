<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminKaryawanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->roles === 'admin' || Auth::user()->roles === 'karyawan')) {
            return $next($request);
        }
        
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }
} 