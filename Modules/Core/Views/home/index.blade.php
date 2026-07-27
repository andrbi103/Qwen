<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'OmniCMS') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        header h1 { font-size: 2.5em; margin-bottom: 10px; }
        header p { font-size: 1.2em; opacity: 0.9; }
        nav { background: white; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav ul { list-style: none; display: flex; gap: 20px; justify-content: center; }
        nav a { color: #667eea; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        main { background: white; margin-top: 20px; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .modules { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .module-card { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 2px solid #e9ecef; transition: transform 0.3s; }
        .module-card:hover { transform: translateY(-5px); border-color: #667eea; }
        .module-card h3 { color: #667eea; margin-bottom: 10px; }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .btn:hover { background: #5a6fd6; }
    </style>
</head>
<body>
    <header>
        <h1>🚀 OmniCMS</h1>
        <p>سیستم مدیریت محتوای چندمنظوره</p>
    </header>
    
    <nav>
        <ul>
            <li><a href="/">خانه</a></li>
            <li><a href="/about">درباره ما</a></li>
            <li><a href="/contact">تماس با ما</a></li>
            <li><a href="/blog">وبلاگ</a></li>
            <li><a href="/shop">فروشگاه</a></li>
            <li><a href="/forum">انجمن</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <main>
            <h2>خوش آمدید!</h2>
            <p>به سیستم مدیریت محتوای OmniCMS خوش آمدید. این یک پلتفرم قدرتمند و انعطاف‌پذیر برای مدیریت وبسایت شماست.</p>
            
            <div class="modules">
                <div class="module-card">
                    <h3>📝 وبلاگ (Blog)</h3>
                    <p>سیستم مدیریت مطالب وبلاگ با قابلیت دسته‌بندی، برچسب‌گذاری و نظرات</p>
                    <a href="/blog" class="btn">مشاهده وبلاگ</a>
                </div>
                
                <div class="module-card">
                    <h3>🛒 فروشگاه (Shop)</h3>
                    <p>سیستم فروشگاهی کامل با مدیریت محصولات، سبد خرید و سفارشات</p>
                    <a href="/shop" class="btn">مشاهده فروشگاه</a>
                </div>
                
                <div class="module-card">
                    <h3>💬 انجمن (Forum)</h3>
                    <p>سیستم انجمن و گفتگو با قابلیت ایجاد موضوعات و پاسخ‌ها</p>
                    <a href="/forum" class="btn">مشاهده انجمن</a>
                </div>
            </div>
        </main>
        
        <footer>
            <p>© 2024 OmniCMS - نسخه 1.0.0</p>
        </footer>
    </div>
</body>
</html>
