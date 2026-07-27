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

// Admin routes with prefix and middleware
$router->group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function($router) {
    // Dashboard
    $router->get('/dashboard', 'Modules\Core\Controllers\Admin\DashboardController@index')->name('admin.dashboard');
    
    // Modules Management
    $router->get('/modules', 'Modules\Core\Controllers\Admin\ModulesController@index')->name('admin.modules');
    $router->get('/modules/activate/{name}', 'Modules\Core\Controllers\Admin\ModulesController@activate')->name('admin.modules.activate');
    $router->get('/modules/deactivate/{name}', 'Modules\Core\Controllers\Admin\ModulesController@deactivate')->name('admin.modules.deactivate');
    
    // Settings
    $router->get('/settings', 'Modules\Core\Controllers\Admin\SettingsController@index')->name('admin.settings');
    $router->post('/settings/update', 'Modules\Core\Controllers\Admin\SettingsController@update')->name('admin.settings.update');
    
    // Profile (admin specific)
    $router->get('/profile', 'Modules\Core\Controllers\Admin\ProfileController@index')->name('admin.profile');
    $router->post('/profile/update', 'Modules\Core\Controllers\Admin\ProfileController@update')->name('admin.profile.update');
});

// Redirect admin root to dashboard
$router->get('/admin', function() {
    return redirect('/admin/dashboard');
})->name('admin');
