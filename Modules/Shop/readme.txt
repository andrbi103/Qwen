# ماژول فروشگاه (Shop Module)

## توضیحات
سیستم مدیریت فروشگاه اینترنتی کامل با قابلیت‌های:
- مدیریت محصولات با گالری تصاویر
- دسته‌بندی و برندها
- سبد خرید پیشرفته
- سیستم پرداخت چند درگاهه
- مدیریت سفارش‌ها و پیگیری
- مدیریت مشتریان و فروشندگان
- کد تخفیف و کمپین‌های تبلیغاتی
- روش‌های مختلف ارسال
- انبارداری و مدیریت موجودی
- گزارش‌گیری و آمار فروش

## نسخه
1.0.0

## نصب
این ماژول به صورت پیش‌فرض نصب است. برای فعال/غیرفعال کردن، فایل `__init__.php` را ویرایش کنید.

## ساختار پوشه‌ها
```
Shop/
├── __init__.php          # فایل پیکربندی ماژول
├── Config/               # تنظیمات ماژول
├── Controllers/          # کنترلرهای ماژول
│   ├── ShopController.php
│   ├── CartController.php
│   ├── CheckoutController.php
│   ├── OrderController.php
│   ├── ProductController.php (Admin)
│   ├── CategoryController.php (Admin)
│   ├── BrandController.php (Admin)
│   ├── OrderAdminController.php
│   ├── CustomerController.php (Admin)
│   ├── VendorController.php (Admin)
│   ├── DiscountController.php (Admin)
│   └── ShippingController.php (Admin)
├── Models/               # مدل‌های ماژول
│   ├── Product.php
│   ├── Category.php
│   ├── Brand.php
│   ├── Cart.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Customer.php
│   ├── Vendor.php
│   ├── Discount.php
│   ├── Coupon.php
│   ├── Shipping.php
│   ├── Payment.php
│   └── Review.php
├── Views/                # قالب‌های ماژول
│   ├── index.php
│   ├── product.php
│   ├── category.php
│   ├── cart.php
│   ├── checkout.php
│   ├── order.php
│   └── admin/
├── Events/               # شنونده‌های رویداد
├── Migrations/           # مهاجرت‌های پایگاه داده
├── Assets/               # فایل‌های استاتیک
│   ├── css/
│   ├── js/
│   └── images/
└── Plugins/              # افزونه‌های اختصاصی ماژول
```

## جداول پایگاه داده
- products (محصولات)
- categories (دسته‌بندی‌ها)
- brands (برندها)
- product_images (تصاویر محصول)
- carts (سبد خرید)
- cart_items (آیتم‌های سبد)
- orders (سفارش‌ها)
- order_items (آیتم‌های سفارش)
- customers (مشتریان)
- vendors (فروشندگان)
- discounts (تخفیف‌ها)
- coupons (کد تخفیف)
- shipping_methods (روش‌های ارسال)
- payments (پرداخت‌ها)
- reviews (نظرات کاربران)
- wishlists (علاقه‌مندی‌ها)

## رویدادها
- shop.product.created - هنگام ایجاد محصول جدید
- shop.product.updated - هنگام ویرایش محصول
- shop.product.deleted - هنگام حذف محصول
- shop.order.created - هنگام ثبت سفارش جدید
- shop.order.paid - هنگام پرداخت سفارش
- shop.order.shipped - هنگام ارسال سفارش
- shop.cart.updated - هنگام بروزرسانی سبد خرید

## دسترسی‌ها
- shop.view - مشاهده محصولات
- shop.cart - مدیریت سبد خرید
- shop.order - ثبت سفارش
- shop.products - مدیریت محصولات
- shop.categories - مدیریت دسته‌بندی‌ها
- shop.brands - مدیریت برندها
- shop.orders - مدیریت سفارش‌ها
- shop.customers - مدیریت مشتریان
- shop.vendors - مدیریت فروشندگان
- shop.discounts - مدیریت تخفیف‌ها
- shop.shipping - مدیریت روش‌های ارسال
- shop.reports - گزارش‌های فروش

## تنظیمات قابل تغییر
- واحد پول و نماد
- تعداد محصول در هر صفحه
- زمان انقضای سبد خرید
- حداقل و حداکثر مبلغ سفارش
- فعال/غیرفعال کردن نظرات
- فعال/غیرفعال کردن لیست علاقه‌مندی‌ها
- فعال/غیرفعال کردن مقایسه محصولات
- مدیریت موجودی انبار
- آستانه موجودی کم
- فعال/غیرفعال کردن کد تخفیف
- مالیات و نرخ آن

## درگاه‌های پرداخت
- زرین‌پال
- نکست‌پی
- آی‌دی‌پی
- بانک ملت
- بانک صادرات
- پرداخت در محل

## روش‌های ارسال
- پست پیشتاز
- تیپاکس
- باربران
- پیک موتوری

## توسعه‌دهنده
OmniCMS Team

## لایسنس
MIT
