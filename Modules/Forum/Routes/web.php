<?php
/**
 * Forum Module - Web Routes
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

$router->group(['prefix' => 'forum', 'middleware' => ['web']], function($router) {
    
    // Public routes
    $router->get('/', 'Modules\Forum\Controllers\ForumController@index')->name('forum.index');
    $router->get('/category/{id}', 'Modules\Forum\Controllers\ForumController@showCategory')->name('forum.category.show');
    $router->get('/topic/{id}', 'Modules\Forum\Controllers\ForumController@showTopic')->name('forum.topic.show');
    
    // Protected routes (require authentication)
    $router->group(['middleware' => ['auth']], function($router) {
        $router->post('/topic/create', 'Modules\Forum\Controllers\ForumController@createTopic')->name('forum.topic.create');
        $router->post('/reply/store', 'Modules\Forum\Controllers\ForumController@storeReply')->name('forum.reply.store');
    });
});
