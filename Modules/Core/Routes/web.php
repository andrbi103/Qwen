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
