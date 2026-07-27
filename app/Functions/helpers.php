<?php
/**
 * Helper Functions
 * 
 * @package OmniCMS\Functions
 */

/**
 * Get translation string
 * 
 * @param string $key Translation key
 * @param string $lang Language code (default: current session language)
 * @return string Translated string
 */
function __($key, $lang = null)
{
    if ($lang === null) {
        $lang = $_SESSION['lang'] ?? 'fa';
    }
    
    static $translations = [];
    
    if (!isset($translations[$lang])) {
        $langFile = APP_PATH . DS . 'Lang' . DS . $lang . DS . 'messages.php';
        if (file_exists($langFile)) {
            $translations[$lang] = require $langFile;
        } else {
            $translations[$lang] = [];
        }
    }
    
    return isset($translations[$lang][$key]) ? $translations[$lang][$key] : $key;
}

/**
 * Redirect to URL
 * 
 * @param string $url URL to redirect to
 * @param int $statusCode HTTP status code
 */
function redirect($url, $statusCode = 302)
{
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Get asset URL
 * 
 * @param string $path Asset path
 * @return string Full asset URL
 */
function asset($path)
{
    return '/public/assets/' . ltrim($path, '/');
}

/**
 * Get uploaded file URL
 * 
 * @param string $path File path
 * @return string Full file URL
 */
function upload($path)
{
    return '/public/uploads/' . ltrim($path, '/');
}

/**
 * CSRF token generation
 * 
 * @return string Token
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF field HTML
 * 
 * @return string Hidden input field
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if valid
 */
function verify_csrf($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize output
 * 
 * @param string $string String to sanitize
 * @return string Sanitized string
 */
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID
 * 
 * @return int|null User ID or null
 */
function get_current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 * 
 * @return string|null User role or null
 */
function get_current_user_role()
{
    return $_SESSION['user_role'] ?? null;
}

/**
 * Check if user has role
 * 
 * @param string|array $roles Role or array of roles
 * @return bool
 */
function has_role($roles)
{
    $userRole = get_current_user_role();
    
    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }
    
    return $userRole === $roles;
}

/**
 * Load module routes
 */
function loadModuleRoutes()
{
    if (!is_dir(MODULES_PATH)) {
        return;
    }
    
    $modules = scandir(MODULES_PATH);
    
    // Separate Core module from others
    $coreModule = null;
    $otherModules = [];
    
    foreach ($modules as $module) {
        if ($module === '.' || $module === '..' || !is_dir(MODULES_PATH . DS . $module)) {
            continue;
        }
        
        if ($module === 'Core') {
            $coreModule = $module;
        } else {
            $otherModules[] = $module;
        }
    }
    
    // Load Core module first (if exists)
    if ($coreModule) {
        loadModuleRouteFile($coreModule);
    }
    
    // Load other modules
    foreach ($otherModules as $module) {
        loadModuleRouteFile($module);
    }
}

/**
 * Load route file for a specific module
 */
function loadModuleRouteFile($module)
{
    // Check for Config/routes.php first, then Routes/web.php
    $routeFile = MODULES_PATH . DS . $module . DS . 'Config' . DS . 'routes.php';
    if (!file_exists($routeFile)) {
        $routeFile = MODULES_PATH . DS . $module . DS . 'Routes' . DS . 'web.php';
    }
    
    if (file_exists($routeFile)) {
        require_once $routeFile;
    }
}

/**
 * Load plugin routes
 */
function loadPluginRoutes()
{
    if (!is_dir(PLUGINS_PATH)) {
        return;
    }
    
    $plugins = scandir(PLUGINS_PATH);
    foreach ($plugins as $plugin) {
        if ($plugin === '.' || $plugin === '..' || !is_dir(PLUGINS_PATH . DS . $plugin)) {
            continue;
        }
        
        $routeFile = PLUGINS_PATH . DS . $plugin . DS . 'Config' . DS . 'routes.php';
        if (file_exists($routeFile)) {
            require_once $routeFile;
        }
    }
}

/**
 * Format date
 * 
 * @param string $date Date string
 * @param string $format Format string
 * @return string Formatted date
 */
function format_date($date, $format = 'Y-m-d H:i:s')
{
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

/**
 * Generate UUID
 * 
 * @return string UUID
 */
function generate_uuid()
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Get configuration value
 * 
 * @param string $key Configuration key (dot notation supported)
 * @param mixed $default Default value
 * @return mixed Configuration value
 */
function config($key, $default = null)
{
    static $config = null;
    
    if ($config === null) {
        $configFile = APP_PATH . DS . 'Config' . DS . 'config.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
        } else {
            $config = [];
        }
    }
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (is_array($value) && isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $default;
        }
    }
    
    return $value;
}

/**
 * Log message
 * 
 * @param string $level Log level
 * @param string $message Message
 * @param array $context Context data
 */
function log_message($level, $message, array $context = [])
{
    \OmniCMS\Core\Log\Logger::log($level, $message, $context);
}

/**
 * Fire event
 * 
 * @param string $eventName Event name
 * @param mixed $data Event data
 * @return array Event responses
 */
function fire_event($eventName, $data = null)
{
    $dispatcher = \OmniCMS\Core\DependencyInjection\Container::getInstance()->get('event_dispatcher');
    return $dispatcher->dispatch($eventName, $data);
}

/**
 * Get database connection
 * 
 * @return \OmniCMS\Core\Database\Connection
 */
function db()
{
    return \OmniCMS\Core\DependencyInjection\Container::getInstance()->get('db');
}

/**
 * Render view
 * 
 * @param string $view View name
 * @param array $data Data to pass to view
 * @return string Rendered HTML
 */
function render_view($view, array $data = [])
{
    $renderer = new \OmniCMS\Core\Http\ViewRenderer();
    return $renderer->render($view, $data);
}

/**
 * JSON response
 * 
 * @param mixed $data Data to encode
 * @param int $statusCode HTTP status code
 * @return \OmniCMS\Core\Http\JsonResponse
 */
function json_response($data, $statusCode = 200)
{
    $response = new \OmniCMS\Core\Http\JsonResponse($data);
    $response->setStatusCode($statusCode);
    return $response;
}

/**
 * Module is active check
 * 
 * @param string $moduleName Module name
 * @return bool
 */
function is_module_active($moduleName)
{
    $activeModules = config('modules.active', []);
    return in_array($moduleName, $activeModules);
}

/**
 * Get module config
 * 
 * @param string $moduleName Module name
 * @return array|null Module configuration
 */
function get_module_config($moduleName)
{
    $configFile = MODULES_PATH . DS . $moduleName . DS . 'Config' . DS . 'config.php';
    if (file_exists($configFile)) {
        return require $configFile;
    }
    return null;
}
