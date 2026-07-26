# ماژول وبلاگ (Blog Module)

## توضیحات
سیستم مدیریت وبلاگ کامل با قابلیت‌های:
- ایجاد و مدیریت پست‌ها
- دسته‌بندی و برچسب‌گذاری
- سیستم نظرات
- آرشیو بر اساس تاریخ
- فید RSS
- اشتراک‌گذاری اجتماعی

## نسخه
1.0.0

## نصب
این ماژول به صورت پیش‌فرض نصب است. برای فعال/غیرفعال کردن، فایل `__init__.php` را ویرایش کنید.

## ساختار پوشه‌ها
```
Blog/
├── __init__.php          # فایل پیکربندی ماژول
├── Config/               # تنظیمات ماژول
├── Controllers/          # کنترلرهای ماژول
│   ├── BlogController.php
│   ├── BlogAdminController.php
│   ├── BlogCategoryController.php
│   └── BlogTagController.php
├── Models/               # مدل‌های ماژول
│   ├── Post.php
│   ├── Category.php
│   ├── Tag.php
│   └── Comment.php
├── Views/                # قالب‌های ماژول
│   ├── index.php
│   ├── show.php
│   ├── category.php
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
- posts (مطالب)
- categories (دسته‌بندی‌ها)
- tags (برچسب‌ها)
- post_tag (رابطه پست و برچسب)
- comments (نظرات)

## رویدادها
- blog.post.created - هنگام ایجاد پست جدید
- blog.post.updated - هنگام ویرایش پست
- blog.post.deleted - هنگام حذف پست
- blog.comment.created - هنگام ثبت نظر جدید

## دسترسی‌ها
- blog.view - مشاهده مطالب
- blog.create - ایجاد مطلب
- blog.edit - ویرایش مطلب
- blog.delete - حذف مطلب
- blog.categories - مدیریت دسته‌بندی‌ها
- blog.tags - مدیریت برچسب‌ها
- blog.comments - مدیریت نظرات

## تنظیمات قابل تغییر
- تعداد پست در هر صفحه
- فعال/غیرفعال کردن نظرات
- نیاز به تایید نظرات
- فعال/غیرفعال کردن RSS
- فعال/غیرفعال کردن اشتراک‌گذاری اجتماعی

## توسعه‌دهنده
OmniCMS Team

## لایسنس
MIT
