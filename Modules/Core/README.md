# Core Module

ماژول اصلی سیستم OmniCMS که وظایف پایه و اساسی را بر عهده دارد.

## ویژگی‌ها

- **سیستم احراز هویت**: ورود، ثبت‌نام و خروج کاربران
- **مدیریت کاربران**: مدل User با قابلیت‌های کامل
- **مسیرهای پایه**: صفحه اصلی، درباره ما، تماس با ما
- **پنل مدیریت**: داشبورد، مدیریت ماژول‌ها، تنظیمات
- **Middleware**: Auth و Admin برای کنترل دسترسی
- **Migration**: ایجاد جداول پایگاه داده
- **Seeder**: ایجاد کاربران پیش‌فرض

## ساختار

```
Core/
├── Controllers/
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── ModulesController.php
│   │   ├── SettingsController.php
│   │   └── ProfileController.php
│   ├── Auth/
│   │   └── AuthController.php
│   └── HomeController.php
├── Middleware/
│   ├── AuthMiddleware.php
│   └── AdminMiddleware.php
├── Models/
│   └── User.php
├── Migrations/
│   └── CreateUsersTable.php
├── Seeders/
│   └── DefaultUsersSeeder.php
├── Routes/
│   ├── web.php
│   └── admin.php
├── Views/
│   ├── front/
│   ├── admin/
│   └── auth/
├── __init__.php
├── README.md
└── CHANGELOG.md
```

## مسیرها

### عمومی (Frontend)
- `GET /` - صفحه اصلی
- `GET /about` - درباره ما
- `GET /contact` - تماس با ما
- `POST /contact` - ارسال فرم تماس
- `GET /auth/login` - فرم ورود
- `POST /auth/login` - پردازش ورود
- `GET /auth/register` - فرم ثبت‌نام
- `POST /auth/register` - پردازش ثبت‌نام
- `GET /auth/logout` - خروج
- `GET /profile` - پروفایل کاربری (نیازمند ورود)

### مدیریتی (Backend/Admin)
- `GET /admin` - هدایت به داشبورد
- `GET /admin/dashboard` - داشبورد مدیریت
- `GET /admin/modules` - مدیریت ماژول‌ها
- `GET /admin/settings` - تنظیمات
- `GET /admin/profile` - پروفایل ادمین

## کاربران پیش‌فرض

| نام کاربری | رمز عبور | نقش | دسترسی |
|------------|----------|-----|--------|
| admin | admin123 | super_admin | کامل |
| user | user123 | user | عمومی |

## نصب و راه‌اندازی

1. اجرای Migration:
```bash
php cli.php migrate
```

2. اجرای Seeder:
```bash
php cli.php seed
```

## تغییرات

برای مشاهده تاریخچه تغییرات، فایل [CHANGELOG.md](CHANGELOG.md) را مطالعه کنید.
