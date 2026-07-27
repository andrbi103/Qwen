<?php
/**
 * Shop Module - Web Routes
 */

// Get router from global scope
$router = $GLOBALS['router'] ?? null;

if (!$router) {
    return; // Exit if router is not available
}

$router->group(['prefix' => 'shop', 'middleware' => ['web']], function($router) {
    
    // Public routes
    $router->get('/', 'Modules\Shop\Controllers\ProductController@index')->name('shop.products.index');
    $router->get('/product/{slug}', 'Modules\Shop\Controllers\ProductController@show')->name('shop.products.show');
    $router->get('/category/{slug}', 'Modules\Shop\Controllers\ProductController@category')->name('shop.categories.show');
    
    // Cart routes
    $router->get('/cart', 'Modules\Shop\Controllers\CartController@index')->name('shop.cart.index');
    $router->post('/cart/add/{id}', 'Modules\Shop\Controllers\CartController@add')->name('shop.cart.add');
    $router->post('/cart/update', 'Modules\Shop\Controllers\CartController@update')->name('shop.cart.update');
    $router->post('/cart/remove/{id}', 'Modules\Shop\Controllers\CartController@remove')->name('shop.cart.remove');
    
    // Checkout routes (require authentication)
    $router->group(['middleware' => ['auth']], function($router) {
        $router->get('/checkout', 'Modules\Shop\Controllers\CartController@checkout')->name('shop.checkout.index');
        $router->post('/checkout/process', 'Modules\Shop\Controllers\CartController@process')->name('shop.checkout.process');
        $router->get('/orders', 'Modules\Shop\Controllers\OrderController@index')->name('shop.orders.index');
        $router->get('/order/{id}', 'Modules\Shop\Controllers\OrderController@show')->name('shop.orders.show');
    });
});
