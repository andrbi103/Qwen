<?php
/**
 * Core Module Initialization
 * Main module for the application
 */

return [
    'name' => 'Core',
    'version' => '1.0.0',
    'description' => 'Core system module providing essential functionality',
    'author' => 'OmniCMS Team',
    'routes' => [
        'web' => Routes/web.php,
        'admin' => Routes/admin.php
    ],
    'middleware' => [
        'auth' => Middleware\AuthMiddleware::class,
        'admin' => Middleware\AdminMiddleware::class
    ],
    'models' => [
        Models\User::class
    ],
    'migrations' => [
        Migrations\CreateUsersTable::class
    ],
    'seeders' => [
        Seeders\DefaultUsersSeeder::class
    ],
    'priority' => 1, // Load first
    'active' => true
];
