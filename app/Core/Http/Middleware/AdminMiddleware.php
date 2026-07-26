<?php
/**
 * Admin Middleware - بررسی دسترسی ادمین
 */

namespace OmniCMS\Core\Http\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class AdminMiddleware implements MiddlewareInterface
{
    /**
     * Handle the request
     */
    public function handle($request, callable $next)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $response = new Response();
            return $response->redirect('/login');
        }
        
        // Check if user has admin role
        $userRole = $_SESSION['user_role'] ?? '';
        
        if ($userRole !== 'admin' && $userRole !== 'super_admin') {
            $response = new Response();
            http_response_code(403);
            return $response->setContent('<h1>دسترسی غیرمجاز</h1><p>شما اجازه دسترسی به این بخش را ندارید.</p>');
        }
        
        // User has admin access, continue
        return $next($request);
    }
}
