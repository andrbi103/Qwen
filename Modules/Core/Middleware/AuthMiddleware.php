<?php
/**
 * Auth Middleware
 * Checks if user is logged in
 */

namespace Modules\Core\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Http\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (!is_logged_in()) {
            return redirect('/auth/login');
        }
        
        return $next($request);
    }
}
