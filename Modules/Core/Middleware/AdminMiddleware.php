<?php
/**
 * Admin Middleware
 * Checks if user is admin
 */

namespace Modules\Core\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Http\MiddlewareInterface;

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (!is_logged_in()) {
            return redirect('/auth/login');
        }
        
        if (!has_role(['admin', 'super_admin'])) {
            http_response_code(403);
            echo '<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>';
            exit;
        }
        
        return $next($request);
    }
}
