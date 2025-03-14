<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            \Log::info('User is authenticated.');
            if (Auth::user()->is_admin) {
                \Log::info('User is admin.');
                return $next($request);
            } else {
                \Log::warning('User is not admin.');
            }
        }
        
        \Log::warning('Access denied or user is not authenticated.');
        return redirect()->route('home')->with('error', 'Доступ запрещен. У вас нет прав администратора для просмотра этой страницы.');
    }
}
