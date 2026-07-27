<?php
/**
 * OmniCMS - Multi-Purpose Content Management System
 * Entry Point
 * 
 * @version 1.0.0
 * @author OmniCMS Team
 */

// Define application constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . DS . 'app');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', ROOT_PATH . DS . 'Storage');
define('MODULES_PATH', ROOT_PATH . DS . 'Modules');
define('PLUGINS_PATH', ROOT_PATH . DS . 'Plugins');

// Error reporting (configure based on environment)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', STORAGE_PATH . DS . 'logs' . DS . 'error.log');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load autoloader
require_once APP_PATH . DS . 'Core' . DS . 'Autoloader.php';
\OmniCMS\Core\Autoloader::register();

// Load helper functions
require_once APP_PATH . DS . 'Functions' . DS . 'helpers.php';

// Initialize the application
try {
    // Load configuration
    $config = require APP_PATH . DS . 'Config' . DS . 'config.php';
    
    // Initialize core services
    $container = new \OmniCMS\Core\DependencyInjection\Container();
    $container->set('config', $config);
    
    // Initialize database (only if PDO is available)
    try {
        $db = new \OmniCMS\Core\Database\Connection($config['database']);
        $container->set('db', $db);
    } catch (\PDOException $e) {
        // Database connection failed, continue without DB
        $container->set('db', null);
    }
    
    // Initialize event dispatcher
    $dispatcher = new \OmniCMS\Core\Event\Dispatcher();
    $container->set('event_dispatcher', $dispatcher);
    
    // Initialize router
    $router = new \OmniCMS\Core\Http\Router();
    $container->set('router', $router);
    
    // Load module routes
    loadModuleRoutes();
    
    // Process request
    $request = \OmniCMS\Core\Http\Request::createFromGlobals();
    $response = $router->dispatch($request);
    $response->send();
    
} catch (\Exception $e) {
    // Handle fatal errors
    if (class_exists('\\OmniCMS\\Core\\Log\\Logger')) {
        \\OmniCMS\Core\Log\Logger::emergency('Fatal error: ' . $e->getMessage());
    }
    
    if (isset($config) && $config['debug']) {
        echo '<h1>System Error</h1>';
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>Internal Server Error</h1>';
        echo '<p>Please try again later.</p>';
        if (isset($config) && $config['debug']) {
            echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
        }
    }
}
