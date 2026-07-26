<?php
/**
 * Shop Module - ماژول فروشگاه
 *
 * @package Modules\Shop
 * @version 1.0.0
 */

namespace Modules\Shop;

// Module configuration
return [
    'name' => 'Shop',
    'version' => '1.0.0',
    'description' => 'سیستم مدیریت فروشگاه اینترنتی با قابلیت‌های کامل فروشگاهی',
    'author' => 'OmniCMS Team',
    'enabled' => true,

    'routes' => [
        // Public routes
        'GET /shop' => 'ShopController@index',
        'GET /shop/product/{slug}' => 'ShopController@show',
        'GET /shop/category/{slug}' => 'ShopController@category',
        'GET /shop/brand/{slug}' => 'ShopController@brand',
        'GET /shop/search' => 'ShopController@search',
        'POST /shop/cart/add' => 'CartController@add',
        'POST /shop/cart/update' => 'CartController@update',
        'GET /shop/cart' => 'CartController@index',
        'POST /shop/cart/remove' => 'CartController@remove',
        'GET /shop/checkout' => 'CheckoutController@index',
        'POST /shop/checkout' => 'CheckoutController@store',
        'GET /shop/order/{id}' => 'OrderController@show',

        // Admin routes
        'GET /admin/shop' => 'ShopAdminController@index',
        'GET /admin/shop/products' => 'ProductController@index',
        'GET /admin/shop/products/create' => 'ProductController@create',
        'POST /admin/shop/products' => 'ProductController@store',
        'GET /admin/shop/products/{id}/edit' => 'ProductController@edit',
        'PUT /admin/shop/products/{id}' => 'ProductController@update',
        'DELETE /admin/shop/products/{id}' => 'ProductController@destroy',

        'GET /admin/shop/categories' => 'CategoryController@index',
        'POST /admin/shop/categories' => 'CategoryController@store',
        'PUT /admin/shop/categories/{id}' => 'CategoryController@update',
        'DELETE /admin/shop/categories/{id}' => 'CategoryController@destroy',

        'GET /admin/shop/orders' => 'OrderController@index',
        'GET /admin/shop/orders/{id}' => 'OrderController@show',
        'PUT /admin/shop/orders/{id}/status' => 'OrderController@updateStatus',

        'GET /admin/shop/customers' => 'CustomerController@index',
        'GET /admin/shop/coupons' => 'CouponController@index',
        'POST /admin/shop/coupons' => 'CouponController@store',
        'DELETE /admin/shop/coupons/{id}' => 'CouponController@destroy',

        'GET /admin/shop/vendors' => 'VendorController@index',
        'POST /admin/shop/vendors' => 'VendorController@store',
        'PUT /admin/shop/vendors/{id}' => 'VendorController@update',
        'DELETE /admin/shop/vendors/{id}' => 'VendorController@destroy',
    ],

    'permissions' => [
        'shop.view' => 'مشاهده فروشگاه',
        'shop.products.view' => 'مشاهده محصولات',
        'shop.products.create' => 'ایجاد محصول جدید',
        'shop.products.edit' => 'ویرایش محصولات',
        'shop.products.delete' => 'حذف محصولات',
        'shop.orders.view' => 'مشاهده سفارش‌ها',
        'shop.orders.manage' => 'مدیریت سفارش‌ها',
        'shop.categories' => 'مدیریت دسته‌بندی‌ها',
        'shop.coupons' => 'مدیریت کدهای تخفیف',
        'shop.customers' => 'مدیریت مشتریان',
        'shop.vendors' => 'مدیریت فروشندگان',
        'shop.reports' => 'گزارش‌گیری فروشگاه',
    ],

    'events' => [
        'shop.product.created' => ['Modules\\Shop\\Events\\ProductCreatedListener'],
        'shop.product.updated' => ['Modules\\Shop\\Events\\ProductUpdatedListener'],
        'shop.product.deleted' => ['Modules\\Shop\\Events\\ProductDeletedListener'],
        'shop.order.created' => ['Modules\\Shop\\Events\\OrderCreatedListener'],
        'shop.order.updated' => ['Modules\\Shop\\Events\\OrderUpdatedListener'],
        'shop.order.completed' => ['Modules\\Shop\\Events\\OrderCompletedListener'],
        'shop.cart.updated' => ['Modules\\Shop\\Events\\CartUpdatedListener'],
    ],

    'widgets' => [
        'featured_products' => 'محصولات ویژه',
        'recent_products' => 'آخرین محصولات',
        'best_sellers' => 'پرفروش‌ترین محصولات',
        'categories_tree' => 'درخت دسته‌بندی‌ها',
        'price_filter' => 'فیلتر قیمت',
        'brands' => 'برندها',
        'cart_summary' => 'خلاصه سبد خرید',
    ],

    'settings' => [
        'currency' => 'IRR',
        'currency_symbol' => 'ریال',
        'products_per_page' => 12,
        'cart_expiry' => 7, // days
        'low_stock_threshold' => 5,
        'enable_reviews' => true,
        'review_moderation' => true,
        'enable_coupons' => true,
        'tax_rate' => 9,
        'shipping_enabled' => true,
        'vendor_commission' => 10, // percentage
    ],

    'payment_gateways' => [
        'zarinpal' => 'زرین‌پال',
        'nextpay' => 'نکست‌پی',
        'idpay' => 'آیدی‌پی',
        'bank_mellat' => 'بانک ملت',
        'cod' => 'پرداخت در محل',
    ],

    'order_statuses' => [
        'pending' => 'در انتظار پرداخت',
        'paid' => 'پرداخت شده',
        'processing' => 'در حال پردازش',
        'shipped' => 'ارسال شده',
        'delivered' => 'تحویل داده شده',
        'cancelled' => 'لغو شده',
        'refunded' => 'بازپرداخت شده',
    ]
];
