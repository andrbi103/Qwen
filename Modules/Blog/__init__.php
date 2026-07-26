<?php
/**
 * Blog Module - ماژول وبلاگ
 * 
 * @package Modules\Blog
 * @version 1.0.0
 */

namespace Modules\Blog;

// Module configuration
return [
    'name' => 'Blog',
    'version' => '1.0.0',
    'description' => 'سیستم مدیریت وبلاگ با قابلیت‌های کامل',
    'author' => 'OmniCMS Team',
    'enabled' => true,
    
    'routes' => [
        'GET /blog' => 'BlogController@index',
        'GET /blog/post/{id}' => 'BlogController@show',
        'GET /blog/category/{slug}' => 'BlogController@category',
        'GET /blog/tag/{slug}' => 'BlogController@tag',
        'GET /blog/archive/{year}/{month?}' => 'BlogController@archive',
        'POST /blog/comment' => 'BlogController@comment',
        
        // Admin routes
        'GET /admin/blog' => 'BlogAdminController@index',
        'GET /admin/blog/posts' => 'BlogAdminController@posts',
        'GET /admin/blog/posts/create' => 'BlogAdminController@create',
        'POST /admin/blog/posts' => 'BlogAdminController@store',
        'GET /admin/blog/posts/{id}/edit' => 'BlogAdminController@edit',
        'PUT /admin/blog/posts/{id}' => 'BlogAdminController@update',
        'DELETE /admin/blog/posts/{id}' => 'BlogAdminController@destroy',
        
        'GET /admin/blog/categories' => 'BlogCategoryController@index',
        'POST /admin/blog/categories' => 'BlogCategoryController@store',
        'PUT /admin/blog/categories/{id}' => 'BlogCategoryController@update',
        'DELETE /admin/blog/categories/{id}' => 'BlogCategoryController@destroy',
        
        'GET /admin/blog/tags' => 'BlogTagController@index',
        'POST /admin/blog/tags' => 'BlogTagController@store',
        'DELETE /admin/blog/tags/{id}' => 'BlogTagController@destroy',
    ],
    
    'permissions' => [
        'blog.view' => 'مشاهده مطالب وبلاگ',
        'blog.create' => 'ایجاد مطلب جدید',
        'blog.edit' => 'ویرایش مطالب',
        'blog.delete' => 'حذف مطالب',
        'blog.categories' => 'مدیریت دسته‌بندی‌ها',
        'blog.tags' => 'مدیریت برچسب‌ها',
        'blog.comments' => 'مدیریت نظرات',
    ],
    
    'events' => [
        'blog.post.created' => ['Modules\Blog\Events\PostCreatedListener'],
        'blog.post.updated' => ['Modules\Blog\Events\PostUpdatedListener'],
        'blog.post.deleted' => ['Modules\Blog\Events\PostDeletedListener'],
        'blog.comment.created' => ['Modules\Blog\Events\CommentCreatedListener'],
    ],
    
    'widgets' => [
        'recent_posts' => 'آخرین مطالب',
        'categories' => 'دسته‌بندی‌ها',
        'tags_cloud' => 'ابر برچسب‌ها',
        'archive' => 'آرشیو',
    ],
    
    'settings' => [
        'posts_per_page' => 10,
        'comments_enabled' => true,
        'comments_moderation' => false,
        'rss_enabled' => true,
        'social_sharing' => true,
    ]
];
