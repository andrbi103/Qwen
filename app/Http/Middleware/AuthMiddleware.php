<?php
/**
 * Authentication Middleware
 * 
 * @package OmniCMS\Http\Middleware
 */

namespace OmniCMS\Http\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class AuthMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request Request object
     * @return Response|null Response or null to continue
     */
    public function handle(Request $request)
    {
        if (!is_logged_in()) {
            // Check if it's an API request
            if ($request->wantsJson() || strpos($request->getPath(), '/api/') !== false) {
                return json_response([
                    'success' => false,
                    'message' => 'Unauthorized. Please login.'
                ], 401);
            }
            
            // Redirect to login page
            $response = new Response('', 302);
            $response->withHeader('Location', '/auth/login');
            return $response;
        }
        
        return null; // Continue to next middleware/handler
    }
}
