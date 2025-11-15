<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AbsenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has(config('global.nama_lain'))) {
            return redirect('/')->with('fail', 'anda belum login');
        }
        return $next($request);
    }
}
