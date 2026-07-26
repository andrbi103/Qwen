<?php
/**
 * Forum Module - ماژول انجمن گفتگو
 *
 * @package Modules\Forum
 * @version 1.0.0
 */

namespace Modules\Forum;

// Module configuration
return [
    'name' => 'Forum',
    'version' => '1.0.0',
    'description' => 'سیستم انجمن گفتگو با قابلیت‌های کامل مدیریت بحث و گفتگو',
    'author' => 'OmniCMS Team',
    'enabled' => true,

    'routes' => [
        // Public routes
        'GET /forum' => 'ForumController@index',
        'GET /forum/category/{slug}' => 'ForumController@category',
        'GET /forum/topic/{id}' => 'ForumController@show',
        'GET /forum/topic/{id}/{slug}' => 'ForumController@show',
        'POST /forum/topic/create' => 'TopicController@store',
        'POST /forum/reply' => 'ReplyController@store',
        'GET /forum/search' => 'ForumController@search',
        'GET /forum/unread' => 'ForumController@unread',
        'GET /forum/popular' => 'ForumController@popular',
        'GET /forum/recent' => 'ForumController@recent',

        // User routes
        'GET /forum/my-topics' => 'UserController@myTopics',
        'GET /forum/my-replies' => 'UserController@myReplies',
        'GET /forum/favorites' => 'UserController@favorites',
        'POST /forum/topic/{id}/favorite' => 'UserController@toggleFavorite',
        'POST /forum/topic/{id}/subscribe' => 'UserController@subscribe',

        // Admin routes
        'GET /admin/forum' => 'ForumAdminController@index',
        'GET /admin/forum/categories' => 'CategoryController@index',
        'POST /admin/forum/categories' => 'CategoryController@store',
        'PUT /admin/forum/categories/{id}' => 'CategoryController@update',
        'DELETE /admin/forum/categories/{id}' => 'CategoryController@destroy',

        'GET /admin/forum/forums' => 'ForumController@adminIndex',
        'POST /admin/forum/forums' => 'ForumController@adminStore',
        'PUT /admin/forum/forums/{id}' => 'ForumController@adminUpdate',
        'DELETE /admin/forum/forums/{id}' => 'ForumController@adminDestroy',

        'GET /admin/forum/topics' => 'TopicController@adminIndex',
        'GET /admin/forum/topics/{id}' => 'TopicController@adminShow',
        'PUT /admin/forum/topics/{id}/pin' => 'TopicController@togglePin',
        'PUT /admin/forum/topics/{id}/lock' => 'TopicController@toggleLock',
        'DELETE /admin/forum/topics/{id}' => 'TopicController@adminDestroy',

        'GET /admin/forum/replies' => 'ReplyController@adminIndex',
        'DELETE /admin/forum/replies/{id}' => 'ReplyController@adminDestroy',

        'GET /admin/forum/users' => 'UserController@adminIndex',
        'PUT /admin/forum/users/{id}/ban' => 'UserController@ban',
        'PUT /admin/forum/users/{id}/unban' => 'UserController@unban',
        'PUT /admin/forum/users/{id}/role' => 'UserController@setRole',
    ],

    'permissions' => [
        'forum.view' => 'مشاهده انجمن',
        'forum.topics.view' => 'مشاهده موضوعات',
        'forum.topics.create' => 'ایجاد موضوع جدید',
        'forum.topics.edit' => 'ویرایش موضوعات',
        'forum.topics.delete' => 'حذف موضوعات',
        'forum.replies.create' => 'ایجاد پاسخ',
        'forum.replies.edit' => 'ویرایش پاسخ',
        'forum.replies.delete' => 'حذف پاسخ',
        'forum.categories' => 'مدیریت دسته‌بندی‌ها',
        'forum.moderate' => 'مدیریت و نظارت',
        'forum.users' => 'مدیریت کاربران',
        'forum.settings' => 'تنظیمات انجمن',
    ],

    'events' => [
        'forum.topic.created' => ['Modules\\Forum\\Events\\TopicCreatedListener'],
        'forum.topic.updated' => ['Modules\\Forum\\Events\\TopicUpdatedListener'],
        'forum.topic.deleted' => ['Modules\\Forum\\Events\\TopicDeletedListener'],
        'forum.reply.created' => ['Modules\\Forum\\Events\\ReplyCreatedListener'],
        'forum.reply.updated' => ['Modules\\Forum\\Events\\ReplyUpdatedListener'],
        'forum.user.banned' => ['Modules\\Forum\\Events\\UserBannedListener'],
    ],

    'widgets' => [
        'recent_topics' => 'آخرین موضوعات',
        'hot_topics' => 'موضوعات داغ',
        'active_users' => 'کاربران فعال',
        'statistics' => 'آمار انجمن',
        'online_users' => 'کاربران آنلاین',
        'top_contributors' => 'برترین مشارکت‌کنندگان',
    ],

    'settings' => [
        'topics_per_page' => 20,
        'replies_per_page' => 15,
        'max_title_length' => 200,
        'max_post_length' => 50000,
        'min_post_length' => 10,
        'enable_signatures' => true,
        'max_signature_length' => 500,
        'enable_avatars' => true,
        'enable_reputation' => true,
        'enable_reports' => true,
        'flood_control_interval' => 30, // seconds
        'edit_time_limit' => 3600, // seconds (1 hour)
        'show_user_online_status' => true,
    ],

    'user_groups' => [
        'admin' => 'مدیر',
        'moderator' => 'ناظر',
        'vip' => 'کاربر ویژه',
        'member' => 'عضو',
        'newbie' => 'تازه وارد',
        'banned' => 'مسدود شده',
    ],

    'topic_types' => [
        'normal' => 'عادی',
        'sticky' => 'سنجاق شده',
        'announcement' => 'اطلاعیه',
        'locked' => 'قفل شده',
    ],
];
