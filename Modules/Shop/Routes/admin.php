<?php
/**
 * Shop Module - Admin Routes
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

$router->group(['prefix' => 'admin/shop', 'middleware' => ['auth', 'admin']], function($router) {
    $router->get('/', 'Modules\Shop\Controllers\Admin\ShopAdminController@index')->name('shop.admin.index');
    $router->get('/products', 'Modules\Shop\Controllers\Admin\ShopAdminController@products')->name('shop.admin.products');
    $router->get('/orders', 'Modules\Shop\Controllers\Admin\ShopAdminController@orders')->name('shop.admin.orders');
    $router->get('/categories', 'Modules\Shop\Controllers\Admin\ShopAdminController@categories')->name('shop.admin.categories');
    $router->get('/settings', 'Modules\Shop\Controllers\Admin\ShopAdminController@settings')->name('shop.admin.settings');
    $router->post('/settings/update', 'Modules\Shop\Controllers\Admin\ShopAdminController@updateSettings')->name('shop.admin.settings.update');
});
