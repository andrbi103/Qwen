<?php
/**
 * OmniCMS - سیستم مدیریت محتوای چند منظوره
 * 
 * @author OmniCMS Team
 * @version 1.0.0
 * @license MIT
 */

// تعریف ثابت‌های اصلی
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . DS . 'app');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
define('STORAGE_PATH', ROOT_PATH . DS . 'storage');
define('MODULES_PATH', ROOT_PATH . DS . 'Modules');
define('PLUGINS_PATH', ROOT_PATH . DS . 'Plugins');

// تعریف نسخه
define('VERSION', '1.0.0');
define('VERSION_DATE', '2024-01-15');

// تنظیمات خطاگیری
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', STORAGE_PATH . DS . 'logs' . DS . 'error.log');

// منطقه زمانی
date_default_timezone_set('Asia/Tehran');

// شروع Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// بارگذاری Autoloader
require_once APP_PATH . DS . 'Core' . DS . 'Autoloader.php';
$autoloader = new Autoloader();
$autoloader->register();

// بارگذاری توابع کمکی
require_once APP_PATH . DS . 'Functions' . DS . 'helpers.php';

// بارگذاری پیکربندی
$config = require_once APP_PATH . DS . 'Config' . DS . 'config.php';

// ایجاد نمونه از Container
$container = new \Core\DI\Container();
$container->set('config', $config);

// ثبت سرویس‌های اصلی
$container->singleton('db', function() use ($config) {
    return new \Core\Database\Connection($config['database']);
});

$container->singleton('event', function() {
    return new \Core\Event\EventDispatcher();
});

$container->singleton('cache', function() use ($config) {
    return new \Core\Cache\CacheManager($config['cache']);
});

$container->singleton('logger', function() use ($config) {
    return new \Core\Log\Logger($config['logging']);
});

$container->singleton('request', function() {
    return new \Core\Http\Request();
});

$container->singleton('response', function() {
    return new \Core\Http\Response();
});

$container->singleton('router', function() use ($container) {
    return new \Core\Http\Router($container);
});

$container->singleton('auth', function() use ($container) {
    return new \Core\Security\Auth($container->get('db'), $container->get('session'));
});

// بارگذاری رویدادهای سراسری
require_once APP_PATH . DS . 'Events' . DS . 'global.php';

// بارگذاری مسیرهای اصلی
$router = $container->get('router');
require_once APP_PATH . DS . 'Routes' . DS . 'web.php';
require_once APP_PATH . DS . 'Routes' . DS . 'api.php';

// بارگذاری مسیرهای ماژول‌ها
loadModuleRoutes($router);

// پردازش درخواست
$request = $container->get('request');
$response = $container->get('response');
$router->dispatch($request, $response);

// ارسال پاسخ
$response->send();
