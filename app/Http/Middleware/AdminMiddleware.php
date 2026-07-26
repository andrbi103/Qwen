<?php
/**
 * Admin Authorization Middleware
 * 
 * @package OmniCMS\Http\Middleware
 */

namespace OmniCMS\Http\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class AdminMiddleware
{
    /**
     * Handle the request
     * 
     * @param Request $request Request object
     * @return Response|null Response or null to continue
     */
    public function handle(Request $request)
    {
        // First check if user is authenticated
        if (!is_logged_in()) {
            return json_response([
                'success' => false,
                'message' => 'Unauthorized. Please login.'
            ], 401);
        }
        
        // Check if user has admin role
        $allowedRoles = ['admin', 'super_admin'];
        $userRole = get_current_user_role();
        
        if (!in_array($userRole, $allowedRoles)) {
            // Check if it's an API request
            if ($request->wantsJson() || strpos($request->getPath(), '/api/') !== false) {
                return json_response([
                    'success' => false,
                    'message' => 'Forbidden. Admin access required.'
                ], 403);
            }
            
            // Redirect to dashboard with error
            $response = new Response('', 302);
            $response->withHeader('Location', '/dashboard');
            $_SESSION['flash']['error'] = 'You do not have permission to access the admin panel.';
            return $response;
        }
        
        return null; // Continue to next middleware/handler
    }
}
