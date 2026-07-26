<?php
/**
 * Auth Middleware - بررسی احراز هویت کاربر
 */

namespace OmniCMS\Core\Http\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Handle the request
     */
    public function handle($request, callable $next)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Store intended URL
            $_SESSION['intended_url'] = $request->getPath();
            
            // Redirect to login
            $response = new Response();
            return $response->redirect('/login');
        }
        
        // User is authenticated, continue
        return $next($request);
    }
}
