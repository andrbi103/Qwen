<?php
/**
 * Main Application Routes
 * 
 * @package OmniCMS\Config
 */

use OmniCMS\Core\Http\Router;

$router = \OmniCMS\Core\DependencyInjection\Container::getInstance()->get('router');

// Home route
$router->get('/', 'OmniCMS\Controllers\HomeController@index')->name('home');

// Authentication routes
$router->group(['prefix' => '/auth'], function($router) {
    $router->get('/login', 'OmniCMS\Controllers\AuthController@showLogin')->name('auth.login');
    $router->post('/login', 'OmniCMS\Controllers\AuthController@login')->name('auth.login.post');
    $router->get('/register', 'OmniCMS\Controllers\AuthController@showRegister')->name('auth.register');
    $router->post('/register', 'OmniCMS\Controllers\AuthController@register')->name('auth.register.post');
    $router->get('/logout', 'OmniCMS\Controllers\AuthController@logout')->name('auth.logout');
    $router->get('/forgot-password', 'OmniCMS\Controllers\AuthController@showForgotPassword')->name('auth.forgot');
    $router->post('/forgot-password', 'OmniCMS\Controllers\AuthController@forgotPassword')->name('auth.forgot.post');
    $router->get('/reset-password/{token}', 'OmniCMS\Controllers\AuthController@showResetPassword')->name('auth.reset');
    $router->post('/reset-password', 'OmniCMS\Controllers\AuthController@resetPassword')->name('auth.reset.post');
});

// User dashboard
$router->group(['prefix' => '/dashboard', 'middleware' => ['auth']], function($router) {
    $router->get('/', 'OmniCMS\Controllers\DashboardController@index')->name('dashboard');
    $router->get('/profile', 'OmniCMS\Controllers\ProfileController@show')->name('profile.show');
    $router->put('/profile', 'OmniCMS\Controllers\ProfileController@update')->name('profile.update');
    $router->get('/settings', 'OmniCMS\Controllers\SettingsController@show')->name('settings.show');
    $router->post('/settings', 'OmniCMS\Controllers\SettingsController@update')->name('settings.update');
});

// Admin panel
$router->group(['prefix' => '/admin', 'middleware' => ['auth', 'admin']], function($router) {
    $router->get('/', 'OmniCMS\Controllers\AdminController@index')->name('admin.dashboard');
    
    // Content management
    $router->get('/posts', 'OmniCMS\Controllers\Admin\PostController@index')->name('admin.posts');
    $router->get('/posts/create', 'OmniCMS\Controllers\Admin\PostController@create')->name('admin.posts.create');
    $router->post('/posts', 'OmniCMS\Controllers\Admin\PostController@store')->name('admin.posts.store');
    $router->get('/posts/{id}/edit', 'OmniCMS\Controllers\Admin\PostController@edit')->name('admin.posts.edit');
    $router->put('/posts/{id}', 'OmniCMS\Controllers\Admin\PostController@update')->name('admin.posts.update');
    $router->delete('/posts/{id}', 'OmniCMS\Controllers\Admin\PostController@destroy')->name('admin.posts.delete');
    
    // Pages
    $router->get('/pages', 'OmniCMS\Controllers\Admin\PageController@index')->name('admin.pages');
    $router->get('/pages/create', 'OmniCMS\Controllers\Admin\PageController@create')->name('admin.pages.create');
    $router->post('/pages', 'OmniCMS\Controllers\Admin\PageController@store')->name('admin.pages.store');
    $router->get('/pages/{id}/edit', 'OmniCMS\Controllers\Admin\PageController@edit')->name('admin.pages.edit');
    $router->put('/pages/{id}', 'OmniCMS\Controllers\Admin\PageController@update')->name('admin.pages.update');
    $router->delete('/pages/{id}', 'OmniCMS\Controllers\Admin\PageController@destroy')->name('admin.pages.delete');
    
    // Categories
    $router->get('/categories', 'OmniCMS\Controllers\Admin\CategoryController@index')->name('admin.categories');
    $router->post('/categories', 'OmniCMS\Controllers\Admin\CategoryController@store')->name('admin.categories.store');
    $router->put('/categories/{id}', 'OmniCMS\Controllers\Admin\CategoryController@update')->name('admin.categories.update');
    $router->delete('/categories/{id}', 'OmniCMS\Controllers\Admin\CategoryController@destroy')->name('admin.categories.delete');
    
    // Tags
    $router->get('/tags', 'OmniCMS\Controllers\Admin\TagController@index')->name('admin.tags');
    $router->post('/tags', 'OmniCMS\Controllers\Admin\TagController@store')->name('admin.tags.store');
    $router->put('/tags/{id}', 'OmniCMS\Controllers\Admin\TagController@update')->name('admin.tags.update');
    $router->delete('/tags/{id}', 'OmniCMS\Controllers\Admin\TagController@destroy')->name('admin.tags.delete');
    
    // Media/Gallery
    $router->get('/media', 'OmniCMS\Controllers\Admin\MediaController@index')->name('admin.media');
    $router->post('/media/upload', 'OmniCMS\Controllers\Admin\MediaController@upload')->name('admin.media.upload');
    $router->delete('/media/{id}', 'OmniCMS\Controllers\Admin\MediaController@destroy')->name('admin.media.delete');
    
    // Users
    $router->get('/users', 'OmniCMS\Controllers\Admin\UserController@index')->name('admin.users');
    $router->get('/users/create', 'OmniCMS\Controllers\Admin\UserController@create')->name('admin.users.create');
    $router->post('/users', 'OmniCMS\Controllers\Admin\UserController@store')->name('admin.users.store');
    $router->get('/users/{id}/edit', 'OmniCMS\Controllers\Admin\UserController@edit')->name('admin.users.edit');
    $router->put('/users/{id}', 'OmniCMS\Controllers\Admin\UserController@update')->name('admin.users.update');
    $router->delete('/users/{id}', 'OmniCMS\Controllers\Admin\UserController@destroy')->name('admin.users.delete');
    
    // Roles & Permissions
    $router->get('/roles', 'OmniCMS\Controllers\Admin\RoleController@index')->name('admin.roles');
    $router->post('/roles', 'OmniCMS\Controllers\Admin\RoleController@store')->name('admin.roles.store');
    $router->put('/roles/{id}', 'OmniCMS\Controllers\Admin\RoleController@update')->name('admin.roles.update');
    $router->delete('/roles/{id}', 'OmniCMS\Controllers\Admin\RoleController@destroy')->name('admin.roles.delete');
    
    // Settings
    $router->get('/settings', 'OmniCMS\Controllers\Admin\SettingController@index')->name('admin.settings');
    $router->post('/settings', 'OmniCMS\Controllers\Admin\SettingController@update')->name('admin.settings.update');
    
    // Modules/Plugins
    $router->get('/modules', 'OmniCMS\Controllers\Admin\ModuleController@index')->name('admin.modules');
    $router->post('/modules/{name}/activate', 'OmniCMS\Controllers\Admin\ModuleController@activate')->name('admin.modules.activate');
    $router->post('/modules/{name}/deactivate', 'OmniCMS\Controllers\Admin\ModuleController@deactivate')->name('admin.modules.deactivate');
    
    $router->get('/plugins', 'OmniCMS\Controllers\Admin\PluginController@index')->name('admin.plugins');
    $router->post('/plugins/{name}/install', 'OmniCMS\Controllers\Admin\PluginController@install')->name('admin.plugins.install');
    $router->post('/plugins/{name}/uninstall', 'OmniCMS\Controllers\Admin\PluginController@uninstall')->name('admin.plugins.uninstall');
    
    // SEO
    $router->get('/seo', 'OmniCMS\Controllers\Admin\SeoController@index')->name('admin.seo');
    $router->post('/seo', 'OmniCMS\Controllers\Admin\SeoController@update')->name('admin.seo.update');
    
    // Analytics
    $router->get('/analytics', 'OmniCMS\Controllers\Admin\AnalyticsController@index')->name('admin.analytics');
});

// API routes
$router->group(['prefix' => '/api/v1'], function($router) {
    $router->get('/status', 'OmniCMS\Controllers\Api\StatusController@index')->name('api.status');
    
    // Public API
    $router->get('/posts', 'OmniCMS\Controllers\Api\PostController@index')->name('api.posts.index');
    $router->get('/posts/{id}', 'OmniCMS\Controllers\Api\PostController@show')->name('api.posts.show');
    
    $router->get('/pages/{slug}', 'OmniCMS\Controllers\Api\PageController@show')->name('api.pages.show');
    
    $router->get('/categories', 'OmniCMS\Controllers\Api\CategoryController@index')->name('api.categories.index');
    
    // Protected API (requires authentication)
    $router->group(['middleware' => ['api.auth']], function($router) {
        $router->get('/user', 'OmniCMS\Controllers\Api\UserController@show')->name('api.user.show');
        $router->put('/user', 'OmniCMS\Controllers\Api\UserController@update')->name('api.user.update');
    });
});

// Language switcher
$router->get('/lang/{locale}', 'OmniCMS\Controllers\LanguageController@switch')->name('lang.switch');

// Sitemap
$router->get('/sitemap.xml', 'OmniCMS\Controllers\SitemapController@index')->name('sitemap');

// Robots.txt
$router->get('/robots.txt', 'OmniCMS\Controllers\RobotsController@index')->name('robots');

// 404 handler
// This is handled in the Router when no route matches
