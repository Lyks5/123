<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Проверка аутентификации
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Требуется авторизация');
        }

        // Получаем авторизованного пользователя
        $user = Auth::user();

        // Проверка статуса администратора
        if ($user->is_admin != 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden'
                ], 403);
            }
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}