<?php
/**
 * Application Configuration
 * 
 * @package OmniCMS\Config
 */

return [
    // Application settings
    'app' => [
        'name' => 'OmniCMS',
        'version' => '1.0.0',
        'debug' => true,
        'timezone' => 'Asia/Tehran',
        'locale' => 'fa',
        'url' => 'http://localhost'
    ],

    // Database configuration
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'omnicms',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => 'oc_'
    ],

    // Session configuration
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => false,
        'files' => STORAGE_PATH . DS . 'sessions',
        'table' => 'sessions',
        'cookie' => 'omnicms_session',
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'http_only' => true
    ],

    // Cache configuration
    'cache' => [
        'default' => 'file',
        'prefix' => 'omnicms_',
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => STORAGE_PATH . DS . 'cache'
            ],
            'memory' => [
                'driver' => 'array'
            ],
            'redis' => [
                'driver' => 'redis',
                'host' => '127.0.0.1',
                'port' => 6379,
                'database' => 0
            ]
        ]
    ],

    // Log configuration
    'log' => [
        'default' => 'file',
        'level' => 'debug',
        'immediate' => true,
        'path' => STORAGE_PATH . DS . 'logs',
        'channels' => ['app', 'error', 'security']
    ],

    // Security configuration
    'security' => [
        'csrf' => true,
        'xss_protection' => true,
        'frame_options' => 'DENY',
        'content_type_sniffing' => false,
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'csp' => [
            'enabled' => false,
            'directives' => [
                'default-src' => "'self'",
                'script-src' => "'self' 'unsafe-inline' 'unsafe-eval'",
                'style-src' => "'self' 'unsafe-inline'",
                'img-src' => "'self' data: https:",
                'font-src' => "'self' https://fonts.gstatic.com"
            ]
        ]
    ],

    // Module configuration
    'modules' => [
        'active' => ['Blog', 'Forum', 'Shop'],
        'path' => MODULES_PATH,
        'namespace' => 'Modules'
    ],

    // Plugin configuration
    'plugins' => [
        'path' => PLUGINS_PATH,
        'namespace' => 'Plugins'
    ],

    // View/Template configuration
    'view' => [
        'paths' => [
            APP_PATH . DS . 'Views',
            STORAGE_PATH . DS . 'views'
        ],
        'compiled' => STORAGE_PATH . DS . 'views' . DS . 'compiled',
        'cache' => true,
        'debug' => true
    ],

    // Upload configuration
    'upload' => [
        'path' => PUBLIC_PATH . DS . 'uploads',
        'url' => '/public/uploads',
        'max_size' => 10485760, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'image_sizes' => [
            'thumbnail' => [150, 150],
            'medium' => [300, 300],
            'large' => [800, 800]
        ]
    ],

    // Mail configuration
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => '',
        'password' => '',
        'from' => [
            'address' => 'noreply@example.com',
            'name' => 'OmniCMS'
        ]
    ],

    // API configuration
    'api' => [
        'prefix' => '/api',
        'version' => 'v1',
        'rate_limit' => 60, // requests per minute
        'auth' => 'bearer'
    ],

    // Environment
    'env' => getenv('APP_ENV') ?: 'development',

    // Supported languages
    'languages' => ['fa', 'en', 'ar', 'tr', 'fr'],

    // Default language
    'default_language' => 'fa'
];
