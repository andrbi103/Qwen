<?php
/**
 * Core Module - Web Routes
 * Base routes for the application
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

// Home and base routes (no prefix - root level)
$router->get('/', 'Modules\Core\Controllers\HomeController@index')->name('home');
$router->get('/about', 'Modules\Core\Controllers\HomeController@about')->name('about');
$router->get('/contact', 'Modules\Core\Controllers\HomeController@contact')->name('contact');

// Contact form POST
$router->post('/contact', 'Modules\Core\Controllers\HomeController@submitContact')->name('contact.submit');

// Authentication routes
$router->group(['prefix' => 'auth'], function($router) {
    $router->get('/login', 'Modules\Core\Controllers\Auth\AuthController@showLogin')->name('auth.login');
    $router->post('/login', 'Modules\Core\Controllers\Auth\AuthController@login')->name('auth.login.post');
    $router->get('/register', 'Modules\Core\Controllers\Auth\AuthController@showRegister')->name('auth.register');
    $router->post('/register', 'Modules\Core\Controllers\Auth\AuthController@register')->name('auth.register.post');
    $router->get('/logout', 'Modules\Core\Controllers\Auth\AuthController@logout')->name('auth.logout');
});

// User profile routes (requires auth)
$router->group(['middleware' => ['auth']], function($router) {
    $router->get('/profile', 'Modules\Core\Controllers\Admin\ProfileController@index')->name('profile');
    $router->post('/profile/update', 'Modules\Core\Controllers\Admin\ProfileController@update')->name('profile.update');
});
