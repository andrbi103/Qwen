<?php
/**
 * Forum Module - Admin Routes
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

$router->group(['prefix' => 'admin/forum', 'middleware' => ['auth', 'admin']], function($router) {
    $router->get('/', 'Modules\Forum\Controllers\Admin\ForumAdminController@index')->name('forum.admin.index');
    $router->get('/categories', 'Modules\Forum\Controllers\Admin\ForumAdminController@categories')->name('forum.admin.categories');
    $router->get('/moderators', 'Modules\Forum\Controllers\Admin\ForumAdminController@moderators')->name('forum.admin.moderators');
    $router->get('/settings', 'Modules\Forum\Controllers\Admin\ForumAdminController@settings')->name('forum.admin.settings');
    $router->post('/settings/update', 'Modules\Forum\Controllers\Admin\ForumAdminController@updateSettings')->name('forum.admin.settings.update');
});
