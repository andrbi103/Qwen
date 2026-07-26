# OmniCMS - سیستم مدیریت محتوای چند منظوره

## معرفی
OmniCMS یک سیستم مدیریت محتوای ماژولار و انعطاف‌پذیر است که با PHP خام توسعه یافته و قابلیت تبدیل به پلتفرم‌های مختلف از جمله فروشگاهی، وبلاگ، فروم و سازمانی را دارد.

## ویژگی‌ها

### هسته مرکزی
- ✅ معماری MVC/HMVC
- ✅ Event-Driven Architecture
- ✅ RESTful API Support
- ✅ Dependency Injection Container
- ✅ ORM اختصاصی
- ✅ سیستم Migration
- ✅ Multi-language (fa, en, ar, tr, fr)

### امنیت
- ✅ CSRF Protection
- ✅ XSS Prevention
- ✅ SQL Injection Protection (PDO)
- ✅ .htaccess Security Rules
- ✅ Password Hashing (bcrypt)

### ماژول‌ها
- 📝 Blog Module - سیستم وبلاگ‌دهی
- 🛒 Shop Module - فروشگاه اینترنتی
- 💬 Forum Module - سیستم انجمن

### پنل مدیریت
- 📊 Dashboard با نمودارهای Chart.js
- 👥 مدیریت کاربران
- 📦 مدیریت ماژول‌ها و افزونه‌ها
- ⚙️ تنظیمات سیستم
- 📈 آمار و گزارشات

## ساختار پروژه

```
/workspace
├── app/                    # کدهای اصلی برنامه
│   ├── Config/            # فایل‌های پیکربندی
│   ├── Controllers/       # کنترلرها
│   │   ├── Admin/        # کنترلرهای ادمین
│   │   └── Api/          # کنترلرهای API
│   ├── Core/             # هسته مرکزی
│   │   ├── Cache/        # موتور کش
│   │   ├── Database/     # لایه پایگاه داده
│   │   ├── Event/        # سیستم رویدادها
│   │   ├── Http/         # درخواست و پاسخ
│   │   ├── Log/          # لاگ‌گیری
│   │   └── Security/     # امنیت
│   ├── Functions/        # توابع کمکی
│   ├── Lang/            # فایل‌های زبانی
│   └── Views/           # قالب‌ها
├── Modules/              # ماژول‌ها
│   ├── Blog/            # ماژول وبلاگ
│   ├── Shop/            # ماژول فروشگاه
│   └── Forum/           # ماژول فروم
├── Plugins/             # افزونه‌ها
├── public/              # فایل‌های عمومی
│   ├── assets/         # CSS, JS, Images
│   └── uploads/        # فایل‌های آپلود شده
├── storage/            # فایل‌های ذخیره‌سازی
│   ├── cache/         # کش
│   ├── logs/          # لاگ‌ها
│   └── views/         # قالب‌های کامپایل شده
└── Track Changes/      # ثبت تغییرات
```

## نصب و راه‌اندازی

### پیش‌نیازها
- PHP 7.4 یا بالاتر
- MySQL 5.7 یا بالاتر / MariaDB
- Apache با mod_rewrite فعال
- Composer (اختیاری)

### مراحل نصب

1. **کپی فایل‌ها**
```bash
cp -r /workspace /var/www/html/omnicms
```

2. **تنظیم مجوزها**
```bash
chmod -R 755 /var/www/html/omnicms
chmod -R 777 /var/www/html/omnicms/storage
chmod -R 777 /var/www/html/omnicms/public/uploads
```

3. **پیکربندی پایگاه داده**
فایل `app/Config/config.php` را ویرایش کنید:
```php
'database' => [
    'host' => 'localhost',
    'name' => 'omnicms_db',
    'user' => 'root',
    'pass' => 'password',
    'charset' => 'utf8mb4'
]
```

4. **اجرای Migration**
```bash
php cli migrate
```

5. **تنظیم Document Root**
Document Root وب‌سرور را روی `/public` تنظیم کنید.

## استفاده

### ورود به پنل مدیریت
- آدرس: `/admin/dashboard`
- نام کاربری پیش‌فرض: admin
- رمز عبور پیش‌فرض: admin123

### فعال/غیرفعال کردن ماژول‌ها
از طریق پنل مدیریت > ماژول‌ها

## مستندات API

### احراز هویت
```
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
```

### کاربران
```
GET    /api/users
POST   /api/users
GET    /api/users/{id}
PUT    /api/users/{id}
DELETE /api/users/{id}
```

### مطالب
```
GET    /api/posts
POST   /api/posts
GET    /api/posts/{id}
PUT    /api/posts/{id}
DELETE /api/posts/{id}
```

## توسعه ماژول جدید

1. ایجاد پوشه در `Modules/ModuleName`
2. ایجاد فایل `__init__.php`
3. ایجاد کنترلرها، مدل‌ها و ویوها
4. تعریف routes در `Config/routes.php`

## مجوزها
- MIT License

## پشتیبانی
برای گزارش مشکلات و درخواست امکانات جدید از بخش Issues استفاده کنید.

---
نسخه: 1.0.0
توسعه‌دهنده: OmniCMS Team
