<?php
/**
 * Core Module - Admin Routes
 * Administrative routes for the application
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

$router->group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function($router) {
    // Dashboard
    $router->get('/dashboard', 'Modules\Core\Controllers\Admin\DashboardController@index');
    
    // Modules Management
    $router->get('/modules', 'Modules\Core\Controllers\Admin\ModulesController@index');
    $router->get('/modules/activate/{name}', 'Modules\Core\Controllers\Admin\ModulesController@activate');
    $router->get('/modules/deactivate/{name}', 'Modules\Core\Controllers\Admin\ModulesController@deactivate');
    
    // Settings
    $router->get('/settings', 'Modules\Core\Controllers\Admin\SettingsController@index');
    $router->post('/settings/update', 'Modules\Core\Controllers\Admin\SettingsController@update');
    
    // Profile
    $router->get('/profile', 'Modules\Core\Controllers\Admin\ProfileController@index');
    $router->post('/profile/update', 'Modules\Core\Controllers\Admin\ProfileController@update');
});

// Redirect admin root to dashboard
$router->get('/admin', function() {
    return redirect('/admin/dashboard');
});
