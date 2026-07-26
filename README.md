# OmniCMS - سیستم مدیریت محتوای چند منظوره

## معرفی
OmniCMS یک سیستم مدیریت محتوای ماژولار و انعطاف‌پذیر است که با PHP خام توسعه یافته و قابلیت تبدیل به پلتفرم‌های مختلف از جمله فروشگاهی، وبلاگ، فروم و سازمانی را دارد. این سیستم بر اساس معماری‌های پیشرفته HMVC، Event-Driven و RESTful API طراحی شده است.

## ویژگی‌ها

### هسته مرکزی
- ✅ معماری MVC/HMVC (Hierarchical Model-View-Controller)
- ✅ Event-Driven Architecture (معماری رویداد محور)
- ✅ RESTful API Support
- ✅ Dependency Injection Container (تزریق وابستگی پیشرفته)
- ✅ ORM اختصاصی با Repository Pattern
- ✅ سیستم Migration برای مدیریت پایگاه داده
- ✅ Multi-language (fa, en, ar, tr, fr)
- ✅ Declarative Routing (مسیریابی اعلانی)
- ✅ Middleware Pipeline
- ✅ Template Engine (Blade-like syntax)

### موتورها
| موتور | توضیحات |
|--------|---------|
| Routing Engine | تحلیل درخواست‌ها و مسیریابی اعلانی |
| Dependency Injection | مدیریت وابستگی‌ها با Singleton/Transient |
| ORM/Data Modeling | ارتباط با پایگاه داده، عملیات CRUD |
| Templating Engine | جداسازی منطق از نمایش |
| Authentication | مدیریت ورود/خروج، نقش‌های کاربری |
| Middleware Engine | اجرای کدهای قبل/بعد از درخواست |
| Caching Engine | کش چندلایه (File, Memory, Redis) |
| Validation Engine | اعتبارسنجی داده‌های ورودی |
| Logging Engine | لاگ‌گیری چندسطحی |
| Event Dispatcher | سیستم Pub/Sub رویدادها |
| Security Engine | محافظت در برابر CSRF, XSS, SQL Injection |
| Request/Response | مدیریت شیءگرای درخواست و پاسخ |
| Error Handling | مدیریت یکپارچه خطاها با Fallback |
| Configuration | پیکربندی متمرکز با فرمت‌های مختلف |
| Health Check | نظارت بر سلامت سرویس‌ها |
| Plugin/Module | مدیریت ماژولار افزونه‌ها |

### امنیت
- ✅ CSRF Protection (Token-based)
- ✅ XSS Prevention (Auto-escaping)
- ✅ SQL Injection Protection (PDO Prepared Statements)
- ✅ .htaccess Security Rules
- ✅ Password Hashing (bcrypt)
- ✅ Role-Based Access Control (RBAC)
- ✅ Capability-Based Security
- ✅ Audit Trail (ثبت تغییرات)

### ماژول‌ها
| ماژول | وضعیت | توضیحات |
|-------|-------|---------|
| 📝 Blog | ✅ کامل | سیستم وبلاگ‌دهی با دسته‌بندی، برچسب، نظرات |
| 🛒 Shop | ✅ کامل | فروشگاه اینترنتی با سبد خرید، پرداخت، سفارش |
| 💬 Forum | ✅ کامل | انجمن گفتگو با تالارها، موضوعات، امتیازدهی |

### پنل مدیریت
- 📊 Dashboard با نمودارهای Chart.js
- 👥 مدیریت کاربران با نقش‌های مختلف
- 📦 مدیریت ماژول‌ها و افزونه‌ها
- ⚙️ تنظیمات سیستم
- 📈 آمار و گزارشات تحلیلی
- 🔐 مدیریت دسترسی‌ها و مجوزها
- 🌐 تنظیمات چندزبانه
- 🎨 مدیریت قالب‌ها و پوسته‌ها

## ساختار پروژه

```
/workspace
├── app/                      # کدهای اصلی برنامه
│   ├── Config/              # فایل‌های پیکربندی
│   │   ├── config.php       # تنظیمات اصلی
│   │   └── routes.php       # مسیرهای عمومی
│   ├── Controllers/         # کنترلرها
│   │   ├── Admin/          # کنترلرهای ادمین
│   │   ├── Api/            # کنترلرهای API
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   └── Controller.php   # کنترلر پایه
│   ├── Core/               # هسته مرکزی
│   │   ├── Autoloader.php  # بارگذاری خودکار PSR-4
│   │   ├── Cache/          # موتور کش
│   │   │   ├── CacheEngine.php
│   │   │   ├── FileCache.php
│   │   │   ├── MemoryCache.php
│   │   │   └── RedisCache.php
│   │   ├── Database/       # لایه پایگاه داده
│   │   │   ├── Connection.php
│   │   │   ├── Model.php
│   │   │   ├── Blueprint.php
│   │   │   └── Migration.php
│   │   ├── DI/             # تزریق وابستگی
│   │   │   └── Container.php
│   │   ├── Event/          # سیستم رویدادها
│   │   │   ├── Event.php
│   │   │   ├── Dispatcher.php
│   │   │   └── ListenerInterface.php
│   │   ├── Http/           # درخواست و پاسخ
│   │   │   ├── Request.php
│   │   │   ├── Response.php
│   │   │   ├── Router.php
│   │   │   └── Middleware/
│   │   │       ├── MiddlewareInterface.php
│   │   │       ├── AuthMiddleware.php
│   │   │       ├── AdminMiddleware.php
│   │   │       └── CsrfMiddleware.php
│   │   ├── Log/            # لاگ‌گیری
│   │   │   └── Logger.php
│   │   └── Security/       # امنیت
│   │       └── Csrf.php
│   ├── Functions/          # توابع کمکی
│   │   └── helpers.php
│   ├── Lang/              # فایل‌های زبانی
│   │   ├── fa/            # فارسی
│   │   ├── en/            # انگلیسی
│   │   ├── ar/            # عربی
│   │   ├── tr/            # ترکی
│   │   └── fr/            # فرانسوی
│   └── Views/             # قالب‌ها
│       ├── layouts/       # Layouts اصلی
│       ├── partials/      # قطعات قابل استفاده مجدد
│       └── admin/         # قالب‌های ادمین
├── Modules/                # ماژول‌ها
│   ├── Blog/              # ماژول وبلاگ
│   │   ├── __init__.php   # پیکربندی ماژول
│   │   ├── readme.txt     # مستندات
│   │   ├── Config/        # تنظیمات
│   │   ├── Controllers/   # کنترلرها
│   │   ├── Models/        # مدل‌ها
│   │   ├── Views/         # قالب‌ها
│   │   ├── Events/        # شنونده‌های رویداد
│   │   ├── Migrations/    # مهاجرت‌ها
│   │   ├── Assets/        # فایل‌های استاتیک
│   │   └── Plugins/       # افزونه‌های ماژول
│   ├── Shop/              # ماژول فروشگاه
│   │   ├── __init__.php
│   │   ├── readme.txt
│   │   └── ...
│   └── Forum/             # ماژول فروم
│       ├── __init__.php
│       ├── readme.txt
│       └── ...
├── Plugins/                # افزونه‌ها
├── public/                 # فایل‌های عمومی
│   ├── index.php          # نقطه ورود
│   ├── .htaccess          # قوانین امنیتی و URL
│   ├── assets/            # CSS, JS, Images
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── uploads/           # فایل‌های آپلود شده
├── storage/                # فایل‌های ذخیره‌سازی
│   ├── cache/             # کش
│   ├── logs/              # لاگ‌ها
│   └── views/             # قالب‌های کامپایل شده
└── Track Changes/          # ثبت تغییرات
    └── README.md          # راهنمای نسخه‌بندی
```

## نصب و راه‌اندازی

### پیش‌نیازها
- PHP 7.4 یا بالاتر
- MySQL 5.7 یا بالاتر / MariaDB 10.3+
- Apache با mod_rewrite فعال یا Nginx
- PDO Extension فعال
- JSON Extension
- MBString Extension

### مراحل نصب

#### 1. کپی فایل‌ها
```bash
cp -r /workspace /var/www/html/omnicms
cd /var/www/html/omnicms
```

#### 2. تنظیم مجوزها
```bash
chmod -R 755 /var/www/html/omnicms
chmod -R 777 /var/www/html/omnicms/storage
chmod -R 777 /var/www/html/omnicms/public/uploads
```

#### 3. پیکربندی پایگاه داده
فایل `app/Config/config.php` را ویرایش کنید:
```php
'database' => [
    'host' => 'localhost',
    'name' => 'omnicms_db',
    'user' => 'root',
    'pass' => 'password',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => 'omni_'
],
'app' => [
    'url' => 'http://localhost/omnicms/public',
    'timezone' => 'Asia/Tehran',
    'locale' => 'fa',
    'debug' => true
]
```

#### 4. ایجاد پایگاه داده
```sql
CREATE DATABASE omnicms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 5. اجرای Migration
```bash
# از طریق CLI (در صورت وجود)
php cli migrate

# یا به صورت دستی با اجرای فایل‌های Migration
```

#### 6. تنظیم Document Root
Document Root وب‌سرور را روی پوشه `/public` تنظیم کنید.

#### 7. تکمیل نصب
مرورگر را باز کرده و به آدرس زیر بروید:
```
http://your-domain.com
```

## استفاده

### ورود به پنل مدیریت
- **آدرس:** `/admin/dashboard`
- **نام کاربری پیش‌فرض:** admin
- **رمز عبور پیش‌فرض:** admin123

⚠️ **توجه:** پس از اولین ورود، رمز عبور را تغییر دهید!

### فعال/غیرفعال کردن ماژول‌ها
از طریق پنل مدیریت > ماژول‌ها می‌توانید ماژول‌های مورد نیاز را فعال یا غیرفعال کنید.

### افزودن ماژول جدید
1. ایجاد پوشه در `Modules/ModuleName`
2. ایجاد فایل `__init__.php` با پیکربندی ماژول
3. ایجاد کنترلرها، مدل‌ها و ویوها
4. تعریف routes در فایل `__init__.php`
5. ایجاد Migration برای جداول پایگاه داده

## مستندات API

### احراز هویت
| متد | مسیر | توضیحات |
|-----|------|---------|
| POST | `/api/auth/login` | ورود کاربر |
| POST | `/api/auth/register` | ثبت‌نام کاربر |
| POST | `/api/auth/logout` | خروج کاربر |
| GET | `/api/auth/me` | دریافت اطلاعات کاربر فعلی |

### کاربران
| متد | مسیر | توضیحات |
|-----|------|---------|
| GET | `/api/users` | لیست کاربران |
| POST | `/api/users` | ایجاد کاربر جدید |
| GET | `/api/users/{id}` | دریافت کاربر |
| PUT | `/api/users/{id}` | بروزرسانی کاربر |
| DELETE | `/api/users/{id}` | حذف کاربر |

### مطالب (وبلاگ)
| متد | مسیر | توضیحات |
|-----|------|---------|
| GET | `/api/posts` | لیست مطالب |
| POST | `/api/posts` | ایجاد مطلب جدید |
| GET | `/api/posts/{id}` | دریافت مطلب |
| PUT | `/api/posts/{id}` | بروزرسانی مطلب |
| DELETE | `/api/posts/{id}` | حذف مطلب |

### محصولات (فروشگاه)
| متد | مسیر | توضیحات |
|-----|------|---------|
| GET | `/api/products` | لیست محصولات |
| POST | `/api/products` | ایجاد محصول جدید |
| GET | `/api/products/{id}` | دریافت محصول |
| PUT | `/api/products/{id}` | بروزرسانی محصول |
| DELETE | `/api/products/{id}` | حذف محصول |

### سفارش‌ها
| متد | مسیر | توضیحات |
|-----|------|---------|
| GET | `/api/orders` | لیست سفارش‌ها |
| POST | `/api/orders` | ثبت سفارش جدید |
| GET | `/api/orders/{id}` | دریافت سفارش |
| PUT | `/api/orders/{id}/status` | بروزرسانی وضعیت |

## توسعه‌دهندگان

### ایجاد افزونه
1. ایجاد پوشه در `Plugins/PluginName`
2. ایجاد فایل `__init__.php`
3. تعریف وابستگی‌ها به ماژول‌ها
4. پیاده‌سازی Hookها و Eventها

### ایجاد تمپلیت
```php
// در کنترلر
return view('layouts.main', [
    'title' => 'عنوان صفحه',
    'content' => view('partials.content', $data)
]);
```

### ایجاد Event Listener
```php
class PostCreatedListener implements ListenerInterface
{
    public function handle(Event $event)
    {
        $post = $event->getData();
        // انجام عملیات پس از ایجاد پست
    }
}
```

## Track Changes

سیستم Track Changes تمام تغییرات اعمال شده در پروژه را ثبت می‌کند:
- افزودن فایل‌های جدید
- بروزرسانی فایل‌های موجود
- حذف فایل‌ها
- تغییرات پایگاه داده

برای مشاهده تاریخچه تغییرات به پوشه `Track Changes/` مراجعه کنید.

## مجوزها
- **License:** MIT License
- **Copyright:** © 2024 OmniCMS Team

## پشتیبانی
برای گزارش مشکلات و درخواست امکانات جدید از بخش Issues استفاده کنید.

### کانال‌های ارتباطی
- 📧 Email: support@omnicms.com
- 🌐 Website: https://omnicms.com
- 💬 Forum: https://forum.omnicms.com

---

**نسخه:** 1.0.0  
**توسعه‌دهنده:** OmniCMS Team  
**آخرین بروزرسانی:** 2024
