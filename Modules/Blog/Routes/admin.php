<?php
/**
 * Blog Module - Admin Routes
 * Administrative routes for blog management
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

$router->group(['prefix' => 'admin/blog', 'middleware' => ['auth', 'admin']], function($router) {
    // Posts Management
    $router->get('/posts', 'Modules\Blog\Controllers\Admin\PostController@index');
    $router->get('/posts/create', 'Modules\Blog\Controllers\Admin\PostController@create');
    $router->post('/posts/store', 'Modules\Blog\Controllers\Admin\PostController@store');
    $router->get('/posts/edit/{id}', 'Modules\Blog\Controllers\Admin\PostController@edit');
    $router->post('/posts/update/{id}', 'Modules\Blog\Controllers\Admin\PostController@update');
    $router->get('/posts/delete/{id}', 'Modules\Blog\Controllers\Admin\PostController@delete');
    
    // Categories Management
    $router->get('/categories', 'Modules\Blog\Controllers\Admin\CategoryController@index');
    $router->get('/categories/create', 'Modules\Blog\Controllers\Admin\CategoryController@create');
    $router->post('/categories/store', 'Modules\Blog\Controllers\Admin\CategoryController@store');
    $router->get('/categories/edit/{id}', 'Modules\Blog\Controllers\Admin\CategoryController@edit');
    $router->post('/categories/update/{id}', 'Modules\Blog\Controllers\Admin\CategoryController@update');
    $router->get('/categories/delete/{id}', 'Modules\Blog\Controllers\Admin\CategoryController@delete');
});
