<?php
/**
 * HTTP Router - Declarative Routing Engine
 * 
 * @package OmniCMS\Core\Http
 */

namespace OmniCMS\Core\Http;

class Router
{
    /**
     * @var array Routes collection
     */
    private $routes = [];

    /**
     * @var array Route groups
     */
    private $groups = [];

    /**
     * @var array Middleware stack
     */
    private $middleware = [];

    /**
     * @var string Current group prefix
     */
    private $groupPrefix = '';

    /**
     * @var array Group middleware
     */
    private $groupMiddleware = [];

    /**
     * Register GET route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler (closure or controller@method)
     * @return Router
     */
    public function get($path, $handler)
    {
        return $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register POST route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler (closure or controller@method)
     * @return Router
     */
    public function post($path, $handler)
    {
        return $this->addRoute('POST', $path, $handler);
    }

    /**
     * Register PUT route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler (closure or controller@method)
     * @return Router
     */
    public function put($path, $handler)
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Register DELETE route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler (closure or controller@method)
     * @return Router
     */
    public function delete($path, $handler)
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Register PATCH route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler (closure or controller@method)
     * @return Router
     */
    public function patch($path, $handler)
    {
        return $this->addRoute('PATCH', $path, $handler);
    }

    /**
     * Register ANY route (all methods)
     * 
     * @param string $path Route path
     * @param mixed $handler Handler (closure or controller@method)
     * @return Router
     */
    public function any($path, $handler)
    {
        return $this->addRoute(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], $path, $handler);
    }

    /**
     * Add route to collection
     * 
     * @param string|array $methods HTTP methods
     * @param string $path Route path
     * @param mixed $handler Handler
     * @return Router
     */
    private function addRoute($methods, $path, $handler)
    {
        $methods = (array) $methods;
        $fullPath = $this->groupPrefix . $path;
        
        foreach ($methods as $method) {
            $this->routes[] = [
                'method' => strtoupper($method),
                'path' => $fullPath,
                'handler' => $handler,
                'middleware' => $this->groupMiddleware,
                'name' => null
            ];
        }
        
        return $this;
    }

    /**
     * Create route group
     * 
     * @param array $attributes Group attributes (prefix, middleware, etc.)
     * @param callable $callback Routes callback
     */
    public function group(array $attributes, callable $callback)
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;
        
        if (isset($attributes['prefix'])) {
            $this->groupPrefix .= rtrim($attributes['prefix'], '/');
        }
        
        if (isset($attributes['middleware'])) {
            $this->groupMiddleware = array_merge(
                $this->groupMiddleware,
                (array) $attributes['middleware']
            );
        }
        
        call_user_func($callback, $this);
        
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    /**
     * Add middleware to route
     * 
     * @param string|array $middleware Middleware name(s)
     * @return Router
     */
    public function middleware($middleware)
    {
        $this->middleware = array_merge($this->middleware, (array) $middleware);
        return $this;
    }

    /**
     * Name the route
     * 
     * @param string $name Route name
     * @return Router
     */
    public function name($name)
    {
        $lastRouteIndex = count($this->routes) - 1;
        if ($lastRouteIndex >= 0) {
            $this->routes[$lastRouteIndex]['name'] = $name;
        }
        return $this;
    }

    /**
     * Dispatch request to appropriate handler
     * 
     * @param Request $request Request object
     * @return Response Response object
     */
    public function dispatch(Request $request)
    {
        $route = $this->matchRoute($request);
        
        if (!$route) {
            return new Response('404 Not Found', 404);
        }
        
        // Execute middleware
        foreach ($route['middleware'] as $middleware) {
            $response = $this->executeMiddleware($middleware, $request);
            if ($response instanceof Response) {
                return $response;
            }
        }
        
        // Execute handler
        try {
            $response = $this->executeHandler($route['handler'], $request);
            return $response;
        } catch (\Exception $e) {
            \OmniCMS\Core\Log\Logger::error('Route handler error: ' . $e->getMessage());
            return new Response('500 Internal Server Error', 500);
        }
    }

    /**
     * Match request to route
     * 
     * @param Request $request Request object
     * @return array|null Matched route or null
     */
    private function matchRoute(Request $request)
    {
        $path = $request->getPath();
        $method = $request->getMethod();
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = $this->convertToRegex($route['path']);
            
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match
                
                // Store parameters in request
                $params = $this->extractParameters($route['path'], $path);
                $request->routeParams = $params;
                
                return $route;
            }
        }
        
        return null;
    }

    /**
     * Convert route path to regex pattern
     * 
     * @param string $path Route path
     * @return string Regex pattern
     */
    private function convertToRegex($path)
    {
        // Escape special characters
        $pattern = preg_quote($path, '#');
        
        // Replace {param} with capture group
        $pattern = preg_replace('/\\\{([a-zA-Z_]+)\\\}/', '(?P<$1>[^/]+)', $pattern);
        
        // Replace {param?} with optional capture group
        $pattern = preg_replace('/\\\{([a-zA-Z_]+)\?\\\}/', '(?P<$1>[^/]*)?', $pattern);
        
        return '#^' . $pattern . '$#';
    }

    /**
     * Extract parameters from matched path
     * 
     * @param string $path Route path
     * @param string $matchedPath Actual matched path
     * @return array Parameters
     */
    private function extractParameters($path, $matchedPath)
    {
        $params = [];
        
        preg_match_all('/\{([a-zA-Z_]+)\??\}/', $path, $matches);
        
        $pattern = $this->convertToRegex($path);
        if (preg_match($pattern, $matchedPath, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
        }
        
        return $params;
    }

    /**
     * Execute route handler
     * 
     * @param mixed $handler Handler
     * @param Request $request Request object
     * @return Response Response object
     */
    private function executeHandler($handler, Request $request)
    {
        if ($handler instanceof \Closure) {
            return call_user_func($handler, $request);
        }
        
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controller, $method) = explode('@', $handler);
            
            if (!class_exists($controller)) {
                throw new \Exception("Controller not found: {$controller}");
            }
            
            $instance = new $controller();
            
            if (!method_exists($instance, $method)) {
                throw new \Exception("Method not found: {$method}");
            }
            
            return call_user_func([$instance, $method], $request);
        }
        
        throw new \Exception("Invalid handler type");
    }

    /**
     * Execute middleware
     * 
     * @param string $middleware Middleware name
     * @param Request $request Request object
     * @return Response|null Response or null to continue
     */
    private function executeMiddleware($middleware, Request $request)
    {
        $className = '\\OmniCMS\\Core\\Http\\Middleware\\' . ucfirst($middleware) . 'Middleware';
        
        if (!class_exists($className)) {
            throw new \Exception("Middleware not found: {$middleware}");
        }
        
        $instance = new $className();
        return $instance->handle($request);
    }

    /**
     * Get all registered routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Generate URL for named route
     * 
     * @param string $name Route name
     * @param array $parameters Route parameters
     * @return string URL
     */
    public function route($name, array $parameters = [])
    {
        foreach ($this->routes as $route) {
            if ($route['name'] === $name) {
                $path = $route['path'];
                
                foreach ($parameters as $key => $value) {
                    $path = str_replace('{' . $key . '}', $value, $path);
                    $path = str_replace('{' . $key . '?}', $value, $path);
                }
                
                // Remove remaining optional parameters
                $path = preg_replace('/\{[a-zA-Z_]+\?\}/', '', $path);
                
                return $path;
            }
        }
        
        throw new \Exception("Route not found: {$name}");
    }
}
