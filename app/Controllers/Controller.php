<?php
/**
 * Base Controller Class
 * All controllers should extend this class
 * 
 * @package OmniCMS\Controllers
 */

namespace OmniCMS\Controllers;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class Controller
{
    /**
     * @var Request Request object
     */
    protected $request;

    /**
     * @var array Middleware to apply
     */
    protected $middleware = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->request = \OmniCMS\Core\DependencyInjection\Container::getInstance()->get('request') ?? \OmniCMS\Core\Http\Request::createFromGlobals();
    }

    /**
     * Render a view
     * 
     * @param string $view View name
     * @param array $data Data to pass to view
     * @return Response
     */
    protected function view($view, array $data = [])
    {
        $content = render_view($view, $data);
        return new Response($content);
    }

    /**
     * Return JSON response
     * 
     * @param mixed $data Data to encode
     * @param int $statusCode HTTP status code
     * @return Response
     */
    protected function json($data, $statusCode = 200)
    {
        return json_response($data, $statusCode);
    }

    /**
     * Redirect to URL
     * 
     * @param string $url URL or route name
     * @param array $parameters Route parameters
     * @return Response
     */
    protected function redirect($url, array $parameters = [])
    {
        // Check if it's a named route
        if (strpos($url, 'route:') === 0) {
            $routeName = substr($url, 6);
            $router = \OmniCMS\Core\DependencyInjection\Container::getInstance()->get('router');
            $url = $router->route($routeName, $parameters);
        }
        
        $response = new Response('', 302);
        $response->withHeader('Location', $url);
        return $response;
    }

    /**
     * Redirect back
     * 
     * @return Response
     */
    protected function back()
    {
        $referer = $this->request->referrer() ?? '/';
        return $this->redirect($referer);
    }

    /**
     * Set flash message
     * 
     * @param string $type Message type (success, error, warning, info)
     * @param string $message Message
     */
    protected function flash($type, $message)
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get flash message
     * 
     * @param string $type Message type
     * @return string|null
     */
    protected function getFlash($type = null)
    {
        if ($type === null) {
            $flash = $_SESSION['flash'] ?? [];
            unset($_SESSION['flash']);
            return $flash;
        }
        
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    /**
     * Validate request data
     * 
     * @param array $rules Validation rules
     * @return bool True if valid
     * @throws \Exception If validation fails
     */
    protected function validate(array $rules)
    {
        $validator = new \OmniCMS\Core\Validation\Validator($this->request->all(), $rules);
        
        if ($validator->fails()) {
            throw new \OmniCMS\Core\Validation\ValidationException($validator->errors());
        }
        
        return true;
    }

    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    protected function isAuthenticated()
    {
        return is_logged_in();
    }

    /**
     * Require authentication
     * 
     * @return Response|null Redirect if not authenticated
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            return $this->redirect('route:auth.login');
        }
        return null;
    }

    /**
     * Get current user ID
     * 
     * @return int|null
     */
    protected function userId()
    {
        return get_current_user_id();
    }

    /**
     * Get current user role
     * 
     * @return string|null
     */
    protected function userRole()
    {
        return get_current_user_role();
    }

    /**
     * Check if user has role
     * 
     * @param string|array $roles Roles to check
     * @return bool
     */
    protected function hasRole($roles)
    {
        return has_role($roles);
    }

    /**
     * Require specific role
     * 
     * @param string|array $roles Required roles
     * @return Response|null Redirect if unauthorized
     */
    protected function requireRole($roles)
    {
        if (!$this->hasRole($roles)) {
            return $this->redirect('/')->withHeader('X-Error', 'Unauthorized');
        }
        return null;
    }

    /**
     * Require admin role
     * 
     * @return Response|null Redirect if not admin
     */
    protected function requireAdmin()
    {
        return $this->requireRole(['admin', 'super_admin']);
    }

    /**
     * Apply middleware
     * 
     * @param string|array $middleware Middleware names
     */
    protected function middleware($middleware)
    {
        $this->middleware = array_merge($this->middleware, (array) $middleware);
    }

    /**
     * Get middleware stack
     * 
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }
}
