# تغییرات ماژول Core

## نسخه 1.0.0 (2026-07-27)

### اضافه شده
- ✅ سیستم احراز هویت کامل (ورود، ثبت‌نام، خروج)
- ✅ مدل User با قابلیت‌های پیشرفته
- ✅ کنترلر AuthController برای مدیریت احراز هویت
- ✅ Middlewareهای Auth و Admin برای کنترل دسترسی
- ✅ Migration جدول users
- ✅ Seeder برای ایجاد کاربران پیش‌فرض
- ✅ مسیرهای عمومی (Frontend):
  - `/` - صفحه اصلی
  - `/about` - درباره ما
  - `/contact` - تماس با ما
  - `/auth/login` - ورود
  - `/auth/register` - ثبت‌نام
  - `/auth/logout` - خروج
  - `/profile` - پروفایل کاربری
- ✅ مسیرهای مدیریتی (Backend/Admin):
  - `/admin/dashboard` - داشبورد
  - `/admin/modules` - مدیریت ماژول‌ها
  - `/admin/settings` - تنظیمات
  - `/admin/profile` - پروفایل ادمین
- ✅ ویوهای login و register
- ✅ فایل README مستندات
- ✅ فایل CHANGELOG تاریخچه تغییرات

### تغییر یافته
- به‌روزرسانی مسیرهای admin.php با نام‌گذاری صحیح
- به‌روزرسانی web.php با مسیرهای احراز هویت
- بهبود ساختار ماژولار Core

### کاربران پیش‌فرض
- **admin** / admin123 (super_admin - دسترسی کامل)
- **user** / user123 (user - دسترسی عمومی)

---

## راهنمای استفاده

### اجرای Migration
```bash
php cli.php migrate
```

### اجرای Seeder
```bash
php cli.php seed
```

### تست مسیرها
- صفحه اصلی: http://localhost:6500/
- ورود: http://localhost:6500/auth/login
- ثبت‌نام: http://localhost:6500/auth/register
- داشبورد: http://localhost:6500/admin/dashboard
