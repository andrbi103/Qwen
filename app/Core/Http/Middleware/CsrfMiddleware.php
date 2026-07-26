<?php
/**
 * CSRF Middleware - محافظت در برابر حملات CSRF
 */

namespace OmniCMS\Core\Http\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;
use OmniCMS\Core\Security\Csrf;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Handle the request
     */
    public function handle($request, callable $next)
    {
        // Only check on POST, PUT, DELETE requests
        $method = $request->getMethod();
        
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $token = $request->getPost('_csrf_token') ?? $request->getHeader('X-CSRF-TOKEN');
            
            if (!$token || !Csrf::validateToken($token)) {
                $response = new Response();
                http_response_code(403);
                return $response->setContent('<h1>CSRF Token Invalid</h1><p>لطفاً صفحه را رفرش کرده و دوباره تلاش کنید.</p>');
            }
        }
        
        // Add CSRF token to response for forms
        if ($method === 'GET') {
            $_SESSION['csrf_token'] = Csrf::generateToken();
        }
        
        return $next($request);
    }
}
