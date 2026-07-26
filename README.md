# OmniCMS - Multi-Purpose Content Management System

## معرفی سیستم (Introduction)

OmniCMS یک سیستم مدیریت محتوای چند منظوره و سازمانی است که با استفاده از PHP خام و با حداقل وابستگی توسعه یافته است. این سیستم قابلیت تبدیل به پلتفرم‌های مختلف از جمله فروشگاهی، وبلاگ، فروم و سایر کاربردهای سازمانی را دارد.

OmniCMS is a multi-purpose organizational content management system developed using raw PHP with minimal dependencies. This system can be transformed into various platforms including e-commerce, blog, forum, and other enterprise applications.

## ویژگی‌های اصلی (Key Features)

### معماری‌های پیاده‌سازی شده (Implemented Architectures)
- **MVC** - Model-View-Controller برای سازماندهی کد
- **HMVC** - Hierarchical MVC برای ماژول‌های مستقل
- **Event Driven** - معماری مبتنی بر رویداد
- **Dependency Injection** - تزریق وابستگی پیشرفته
- **RESTful API** - API استاندارد

### موتورها (Engines)
1. موتور مسیریابی (Routing Engine)
2. موتور تزریق وابستگی (Dependency Injection Container)
3. موتور ORM و پایگاه داده (Database/ORM Engine)
4. موتور قالب‌سازی (Templating Engine)
5. موتور احراز هویت (Authentication Engine)
6. موتور میدل‌ور (Middleware Engine)
7. موتور کش (Caching Engine)
8. موتور اعتبارسنجی (Validation Engine)
9. موتور لاگ‌گیری (Logging Engine)
10. موتور مدیریت خطا (Error Handling Engine)
11. موتور بین‌المللی‌سازی (Internationalization Engine)
12. موتور امنیت (Security Engine)

### ماژول‌های پیش‌فرض (Default Modules)
- **Blog** - سیستم وبلاگ‌دهی با قابلیت زمانبندی
- **Forum** - سیستم انجمن و گفتگو
- **Shop** - سیستم فروشگاه اینترنتی

### زبان‌های پشتیبانی شده (Supported Languages)
- فارسی (Persian/Farsi) - زبان پایه
- انگلیسی (English)
- عربی (Arabic)
- ترکی (Turkish)
- فرانسوی (French)

## ساختار پوشه‌ها (Directory Structure)

```
/workspace
├── app/                          # هسته اصلی برنامه
│   ├── Config/                   # فایل‌های پیکربندی
│   │   ├── config.php           # تنظیمات اصلی
│   │   └── routes.php           # مسیرهای اصلی
│   ├── Controllers/              # کنترلرهای اصلی
│   │   ├── Controller.php       # کنترلر پایه
│   │   ├── Admin/               # کنترلرهای پنل مدیریت
│   │   └── Api/                 # کنترلرهای API
│   ├── Core/                     # هسته مرکزی
│   │   ├── Autoloader.php       # بارگذار خودکار
│   │   ├── DependencyInjection/  # کانتینر DI
│   │   ├── Database/            # اتصال به پایگاه داده
│   │   ├── Event/               # سیستم رویدادها
│   │   ├── Http/                # درخواست و پاسخ HTTP
│   │   └── Log/                 # سیستم لاگ‌گیری
│   ├── Functions/               # توابع کمکی
│   │   └── helpers.php          # توابع عمومی
│   ├── Http/                    # middleware های HTTP
│   │   └── Middleware/          # میدل ورها
│   └── Lang/                    # فایل‌های ترجمه
│       ├── fa/                  # فارسی
│       ├── en/                  # انگلیسی
│       ├── ar/                  # عربی
│       ├── tr/                  # ترکی
│       └── fr/                  # فرانسوی
├── Modules/                      # ماژول‌ها
│   ├── Blog/                    # ماژول وبلاگ
│   ├── Forum/                   # ماژول فروم
│   └── Shop/                    # ماژول فروشگاه
│       ├── Config/              # تنظیمات ماژول
│       ├── Controllers/         # کنترلرهای ماژول
│       ├── Models/              # مدل‌های ماژول
│       ├── Views/               # نمایش‌های ماژول
│       ├── Assets/              # فایل‌های استاتیک
│       ├── Migrations/          # مهاجرت‌های پایگاه داده
│       └── Plugins/             # افزونه‌های ماژول
├── Plugins/                      # افزونه‌های سراسری
├── public/                       # دسترسی عمومی
│   ├── index.php                # نقطه ورود اصلی
│   ├── .htaccess                # تنظیمات Apache
│   ├── assets/                  # فایل‌های استاتیک
│   │   ├── css/                 # استایل‌ها
│   │   ├── js/                  # اسکریپت‌ها
│   │   └── images/              # تصاویر
│   └── uploads/                 # فایل‌های آپلود شده
├── Storage/                      # ذخیره‌سازی داخلی
│   ├── cache/                   # کش
│   ├── logs/                    # گزارش‌ها
│   └── views/                   # ویو‌های کامپایل شده
└── Track Changes/                # ردیابی تغییرات
```

## نصب و راه‌اندازی (Installation)

### پیش‌نیازها (Requirements)
- PHP 7.4 یا بالاتر
- MySQL 5.7 یا بالاتر / MariaDB 10.3 یا بالاتر
- Apache با mod_rewrite فعال یا Nginx
- حداقل 128MB حافظه RAM

### مراحل نصب (Installation Steps)

1. **تنظیم پایگاه داده (Database Setup)**
```sql
CREATE DATABASE omnicms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **تنظیم فایل پیکربندی (Configuration)**
   - فایل `app/Config/config.php` را ویرایش کنید
   - اطلاعات پایگاه داده را وارد کنید

3. **تنظیم مجوزهای دسترسی (Permissions)**
```bash
chmod -R 755 /workspace
chmod -R 777 /workspace/Storage
chmod -R 777 /workspace/public/uploads
```

4. **اجرای Migrationها (Run Migrations)**
```bash
# از طریق CLI (در حال توسعه)
php cli.php migrate
```

5. **دسترسی به برنامه (Access Application)**
```
http://localhost/public/
```

## کاربران پیش‌فرض (Default Users)

پس از نصب اولیه، می‌توانید اولین کاربر مدیر را از طریق صفحه ثبت‌نام ایجاد کنید.

## امنیت (Security)

### ویژگی‌های امنیتی
- محافظت در برابر CSRF
- محافظت در برابر XSS
- محافظت در برابر SQL Injection (با استفاده از PDO Prepared Statements)
- هش کردن رمز عبور با الگوریتم bcrypt
- سیستم احراز هویت چند لایه
- محدودیت دسترسی بر اساس نقش‌ها

### فایل .htaccess
فایل `.htaccess` در پوشه `public` تنظیمات امنیتی زیر را اعمال می‌کند:
- جلوگیری از Directory Listing
- مسدود کردن دسترسی به فایل‌های حساس
- هدرهای امنیتی
- فشرده‌سازی GZIP
- کش مرورگر

## توسعه ماژول (Module Development)

### ساختار ماژول جدید (New Module Structure)

```
Modules/YourModule/
├── __init__                 # فایل شناسایی ماژول
├── readme.txt              # توضیحات ماژول
├── Config/
│   ├── config.php         # تنظیمات ماژول
│   └── routes.php         # مسیرهای ماژول
├── Controllers/
│   └── YourController.php
├── Models/
│   └── YourModel.php
├── Views/
│   └── your-view.php
├── Assets/
│   ├── css/
│   ├── js/
│   └── images/
├── Migrations/
│   └── create_your_table.php
└── Plugins/
    └── YourPlugin/
```

### فایل `__init__` نمونه (Sample __init__ file)
```php
<?php
return [
    'name' => 'YourModule',
    'version' => '1.0.0',
    'description' => 'Description of your module',
    'author' => 'Your Name',
    'dependencies' => [],
    'active' => true
];
```

## توسعه افزونه (Plugin Development)

افزونه‌ها برای تکمیل عملکرد ماژول‌ها استفاده می‌شوند و نیاز به رعایت پیش‌نیازها دارند.

## API Documentation

### Endpointهای اصلی (Main Endpoints)

```
GET  /api/v1/status          # وضعیت سیستم
GET  /api/v1/posts           # لیست مطالب
GET  /api/v1/posts/{id}      # جزئیات مطلب
GET  /api/v1/pages/{slug}    # جزئیات برگه
GET  /api/v1/categories      # لیست دسته‌بندی‌ها
```

### احراز هویت API (API Authentication)
برای دسترسی به endpointهای محافظت شده، از Bearer Token استفاده کنید:
```
Authorization: Bearer {token}
```

## سیستم رویدادها (Event System)

### رویدادهای موجود (Available Events)
- `user.login` - ورود کاربر
- `user.logout` - خروج کاربر
- `user.register` - ثبت نام کاربر
- `post.created` - ایجاد مطلب جدید
- `post.updated` - بروزرسانی مطلب
- `post.deleted` - حذف مطلب

### مثال استفاده (Usage Example)
```php
// Subscribe to event
$dispatcher->listen('post.created', function($data) {
    // Handle event
    log_message('info', 'New post created: ' . $data['title']);
});

// Dispatch event
fire_event('post.created', ['title' => 'My Post', 'id' => 1]);
```

## سیستم ترجمه (Translation System)

### استفاده از توابع ترجمه (Using Translation Functions)
```php
// در PHP
echo __('home'); // خروجی: خانه (fa) یا Home (en)

// تغییر زبان
$_SESSION['lang'] = 'en';
echo __('home'); // Output: Home
```

## عیب‌یابی (Troubleshooting)

### مشکلات رایج (Common Issues)

1. **خطای 404 در تمام صفحات**
   - بررسی فعال بودن mod_rewrite در Apache
   - بررسی فایل .htaccess
   - بررسی مسیر RewriteBase

2. **خطای اتصال به پایگاه داده**
   - بررسی اطلاعات اتصال در config.php
   - بررسی وجود پایگاه داده
   - بررسی دسترسی کاربر MySQL

3. **خطای Permission Denied**
   - بررسی مجوزهای پوشه Storage
   - بررسی مالکیت فایل‌ها

## لاگ‌ها (Logs)

فایل‌های لاگ در پوشه `Storage/logs` قرار دارند:
- `app.log` - لاگ‌های عمومی برنامه
- `error.log` - خطاهای PHP
- `security.log` - رویدادهای امنیتی

## لایسنس (License)

این پروژه تحت لایسنس اختصاصی توسعه یافته است.

## پشتیبانی (Support)

برای گزارش مشکلات و درخواست امکانات جدید، لطفاً از طریق سیستم تیکت اقدام نمایید.

---

**نسخه (Version):** 1.0.0  
**تاریخ به‌روزرسانی (Last Updated):** 2024  
**توسعه‌دهنده (Developer):** OmniCMS Team
