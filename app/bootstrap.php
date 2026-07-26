<?php
/**
 * Main Application Bootstrap
 * Initializes the OmniCMS application
 */

// Error Reporting (Development Mode)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Define Constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . DS . 'app');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
define('MODULES_PATH', ROOT_PATH . DS . 'Modules');
define('PLUGINS_PATH', ROOT_PATH . DS . 'Plugins');
define('STORAGE_PATH', ROOT_PATH . DS . 'storage');
define('VERSION', '1.0.0');

// Load Autoloader
require_once APP_PATH . DS . 'Core' . DS . 'Autoloader.php';

// Register Autoloader
$autoloader = new OmniCMS\Core\Autoloader();
$autoloader->addNamespace('OmniCMS\\Core', APP_PATH . DS . 'Core');
$autoloader->addNamespace('OmniCMS\\App', APP_PATH);
$autoloader->addNamespace('Modules', MODULES_PATH);
$autoloader->register();

// Load Helper Functions
require_once APP_PATH . DS . 'Functions' . DS . 'helpers.php';

// Load Configuration
$config = require_once APP_PATH . DS . 'Config' . DS . 'config.php';

// Initialize Database Connection
use OmniCMS\Core\Database\Connection;
$db = Connection::getInstance();

// Initialize Event Dispatcher
use OmniCMS\Core\Event\Dispatcher;
$dispatcher = new Dispatcher();

// Initialize Dependency Injection Container
use OmniCMS\Core\DependencyInjection\Container;
$container = new Container();

// Register Core Services
$container->set('db', function() {
    return Connection::getInstance();
});

$container->set('event', function() {
    return new Dispatcher();
});

$container->set('request', function() {
    return new OmniCMS\Core\Http\Request();
});

$container->set('response', function() {
    return new OmniCMS\Core\Http\Response();
});

$container->set('logger', function() {
    return new OmniCMS\Core\Log\Logger();
});

// Load Active Modules
function loadModules() {
    $modulesPath = MODULES_PATH;
    $activeModules = [];
    
    if (is_dir($modulesPath)) {
        $dirs = scandir($modulesPath);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') continue;
            
            $moduleInit = $modulesPath . DS . $dir . DS . '__init__.php';
            if (file_exists($moduleInit)) {
                $moduleConfig = require_once $moduleInit;
                if (isset($moduleConfig['enabled']) && $moduleConfig['enabled']) {
                    $activeModules[$dir] = $moduleConfig;
                    
                    // Load module routes
                    $routesFile = $modulesPath . DS . $dir . DS . 'Config' . DS . 'routes.php';
                    if (file_exists($routesFile)) {
                        require_once $routesFile;
                    }
                }
            }
        }
    }
    
    return $activeModules;
}

// Load Active Plugins
function loadPlugins() {
    $pluginsPath = PLUGINS_PATH;
    $activePlugins = [];
    
    if (is_dir($pluginsPath)) {
        $dirs = scandir($pluginsPath);
        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') continue;
            
            $pluginInit = $pluginsPath . DS . $dir . DS . '__init__.php';
            if (file_exists($pluginInit)) {
                $pluginConfig = require_once $pluginInit;
                if (isset($pluginConfig['enabled']) && $pluginConfig['enabled']) {
                    $activePlugins[$dir] = $pluginConfig;
                }
            }
        }
    }
    
    return $activePlugins;
}

// Initialize Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set Timezone
date_default_timezone_set('Asia/Tehran');

// Load Language
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fa';
}

// Global Variables
$GLOBALS['container'] = $container;
$GLOBALS['dispatcher'] = $dispatcher;
$GLOBALS['config'] = $config;
$GLOBALS['active_modules'] = loadModules();
$GLOBALS['active_plugins'] = loadPlugins();

// Fire Application Boot Event
$dispatcher->dispatch('app.boot', ['container' => $container, 'config' => $config]);

return [
    'container' => $container,
    'dispatcher' => $dispatcher,
    'config' => $config,
    'db' => $db
];
