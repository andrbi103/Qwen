<?php

namespace App\Core\Middleware;

use App\Core\Http\Request;
use App\Core\Logging\Logger;

class AuthMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Logger::info('AuthMiddleware checking authentication');
        
        // شبیه‌سازی بررسی احراز هویت
        $isAuthenticated = isset($_SESSION['user_id']) || true; // برای تست همیشه true
        
        if (!$isAuthenticated) {
            Logger::warning('User not authenticated', ['path' => $request->path()]);
            return redirect('/login');
        }

        Logger::debug('User authenticated', ['path' => $request->path()]);
        return $next($request);
    }
}

class AdminMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Logger::info('AdminMiddleware checking admin access');
        
        // شبیه‌سازی بررسی دسترسی ادمین
        $isAdmin = isset($_SESSION['is_admin']) || true; // برای تست همیشه true
        
        if (!$isAdmin) {
            Logger::warning('User not authorized for admin area', ['path' => $request->path()]);
            return redirect('/');
        }

        Logger::debug('Admin access granted', ['path' => $request->path()]);
        return $next($request);
    }
}

class FrontendMiddleware
{
    public function handle(Request $request, callable $next)
    {
        Logger::info('FrontendMiddleware processing request');
        return $next($request);
    }
}
