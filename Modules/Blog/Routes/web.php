<?php
/**
 * Blog Module - Routes
 */

$router->group(['prefix' => 'blog', 'middleware' => ['web']], function($router) {
    
    // Public routes
    $router->get('/', 'BlogPostController@index')->name('blog.posts.index');
    $router->get('/post/{slug}', 'BlogPostController@show')->name('blog.posts.show');
    $router->get('/category/{slug}', 'BlogCategoryController@show')->name('blog.categories.show');
    $router->get('/tag/{slug}', 'BlogTagController@show')->name('blog.tags.show');
    
    // Comment routes
    $router->post('/post/{id}/comment', 'BlogCommentController@store')->name('blog.comments.store');
    
    // Protected routes (require authentication)
    $router->group(['middleware' => ['auth']], function($router) {
        $router->get('/post/create', 'BlogPostController@create')->name('blog.posts.create');
        $router->post('/post/store', 'BlogPostController@store')->name('blog.posts.store');
        $router->get('/post/{id}/edit', 'BlogPostController@edit')->name('blog.posts.edit');
        $router->put('/post/{id}', 'BlogPostController@update')->name('blog.posts.update');
        $router->delete('/post/{id}', 'BlogPostController@destroy')->name('blog.posts.destroy');
        
        // Category management
        $router->get('/categories', 'BlogCategoryController@index')->name('blog.categories.index');
        $router->get('/category/create', 'BlogCategoryController@create')->name('blog.categories.create');
        $router->post('/category/store', 'BlogCategoryController@store')->name('blog.categories.store');
        $router->get('/category/{id}/edit', 'BlogCategoryController@edit')->name('blog.categories.edit');
        $router->put('/category/{id}', 'BlogCategoryController@update')->name('blog.categories.update');
        $router->delete('/category/{id}', 'BlogCategoryController@destroy')->name('blog.categories.destroy');
        
        // Comment moderation
        $router->post('/comment/{id}/approve', 'BlogCommentController@approve')->name('blog.comments.approve');
        $router->post('/comment/{id}/reject', 'BlogCommentController@reject')->name('blog.comments.reject');
        $router->delete('/comment/{id}', 'BlogCommentController@destroy')->name('blog.comments.destroy');
    });
    
    // Admin routes
    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function($router) {
        $router->get('/blog/dashboard', 'BlogAdminController@dashboard')->name('blog.admin.dashboard');
        $router->get('/blog/posts', 'BlogAdminController@posts')->name('blog.admin.posts');
        $router->get('/blog/categories', 'BlogAdminController@categories')->name('blog.admin.categories');
        $router->get('/blog/comments', 'BlogAdminController@comments')->name('blog.admin.comments');
        $router->get('/blog/settings', 'BlogAdminController@settings')->name('blog.admin.settings');
        $router->post('/blog/settings', 'BlogAdminController@updateSettings')->name('blog.admin.settings.update');
    });
});
