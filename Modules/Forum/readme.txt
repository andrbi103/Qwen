# ماژول فروم (Forum Module)

## توضیحات
سیستم مدیریت انجمن گفتگو کامل با قابلیت‌های:
- ایجاد و مدیریت تالارهای گفتگو
- موضوعات و پاسخ‌ها
- سیستم امتیازدهی و رتبه‌بندی کاربران
- مدیران و ناظران برای هر تالار
- سیستم اعلان‌ها
- جستجوی پیشرفته
- ضمیمه کردن فایل
- نقل قول و ویرایش پاسخ‌ها

## نسخه
1.0.0

## نصب
این ماژول به صورت پیش‌فرض نصب است. برای فعال/غیرفعال کردن، فایل `__init__.php` را ویرایش کنید.

## ساختار پوشه‌ها
```
Forum/
├── __init__.php          # فایل پیکربندی ماژول
├── Config/               # تنظیمات ماژول
├── Controllers/          # کنترلرهای ماژول
│   ├── ForumController.php
│   ├── TopicController.php
│   ├── ReplyController.php
│   ├── CategoryController.php
│   └── Admin/
│       ├── ForumAdminController.php
│       ├── ModerationController.php
│       └── ReportController.php
├── Models/               # مدل‌های ماژول
│   ├── Forum.php
│   ├── Topic.php
│   ├── Reply.php
│   ├── Category.php
│   ├── Subscription.php
│   ├── Report.php
│   ├── Reputation.php
│   └── Poll.php
├── Views/                # قالب‌های ماژول
│   ├── index.php
│   ├── forum.php
│   ├── topic.php
│   ├── create.php
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
- forums (تالارهای گفتگو)
- forum_categories (دسته‌بندی‌های فروم)
- topics (موضوعات)
- replies (پاسخ‌ها)
- forum_subscriptions (اشتراک‌ها)
- forum_reports (گزارش‌ها)
- forum_reputations (امتیازات)
- forum_polls (نظرسنجی‌ها)
- forum_poll_votes (رای‌های نظرسنجی)
- forum_attachments (ضمیمه‌ها)
- forum_moderators (ناظران)

## رویدادها
- forum.topic.created - هنگام ایجاد موضوع جدید
- forum.topic.updated - هنگام ویرایش موضوع
- forum.topic.deleted - هنگام حذف موضوع
- forum.reply.created - هنگام ثبت پاسخ جدید
- forum.reply.updated - هنگام ویرایش پاسخ
- forum.report.created - هنگام ثبت گزارش
- forum.user.banned - هنگام مسدود کردن کاربر

## دسترسی‌ها
- forum.view - مشاهده تالارها
- forum.topic.create - ایجاد موضوع جدید
- forum.topic.edit - ویرایش موضوع
- forum.topic.delete - حذف موضوع
- forum.reply.create - ایجاد پاسخ
- forum.reply.edit - ویرایش پاسخ
- forum.reply.delete - حذف پاسخ
- forum.moderate - مدیریت و نظارت
- forum.report - گزارش محتوا
- forum.admin - مدیریت کامل فروم

## تنظیمات قابل تغییر
- تعداد موضوع در هر صفحه
- تعداد پاسخ در هر صفحه
- حداقل ارسال برای ایجاد امضا
- حداکثر اندازه ضمیمه‌ها
- انواع فایل‌های مجاز
- فعال/غیرفعال کردن نظرسنجی
- فعال/غیرفعال کردن امتیازدهی
- نیاز به تایید موضوعات جدید
- نیاز به تایید کاربران جدید
- زمان ویرایش پاسخ (دقیقه)

## سطوح کاربری
- کاربر عادی (Member)
- کاربر ارشد (Senior Member)
- ناظر (Moderator)
- مدیر کل (Administrator)
- مدیر ارشد (Super Administrator)

## امکانات ویژه
- سیستم امتیاز و نشان‌ها (Badges)
- لیست کاربران آنلاین
- آمار فروم (تعداد موضوعات، پاسخ‌ها، کاربران)
- آخرین فعالیت‌ها
- موضوعات داغ (Hot Topics)
- موضوعات چسبان (Sticky Topics)
- موضوعات قفل شده (Locked Topics)
- جستجو در فروم
- فیلتر بر اساس وضعیت (حل شده، باز، بسته)

## توسعه‌دهنده
OmniCMS Team

## لایسنس
MIT
