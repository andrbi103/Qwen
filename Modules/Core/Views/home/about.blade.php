<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'درباره ما') ?> - OmniCMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        header h1 { font-size: 2.5em; margin-bottom: 10px; }
        nav { background: white; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav ul { list-style: none; display: flex; gap: 20px; justify-content: center; }
        nav a { color: #667eea; text-decoration: none; font-weight: bold; }
        main { background: white; margin-top: 20px; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .features { margin-top: 30px; }
        .feature { background: #f8f9fa; padding: 20px; margin: 15px 0; border-radius: 8px; border-right: 4px solid #667eea; }
    </style>
</head>
<body>
    <header>
        <h1>ℹ️ درباره OmniCMS</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="/">خانه</a></li>
            <li><a href="/about">درباره ما</a></li>
            <li><a href="/contact">تماس با ما</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <main>
            <h2>درباره سیستم مدیریت محتوای OmniCMS</h2>
            <p style="margin-top: 15px;">OmniCMS یک سیستم مدیریت محتوای مدرن و چندمنظوره است که با استفاده از PHP توسعه یافته است.</p>
            
            <div class="features">
                <div class="feature">
                    <h3>🏗️ معماری ماژولار</h3>
                    <p>ساختار مبتنی بر ماژول که امکان توسعه و گسترش آسان را فراهم می‌کند</p>
                </div>
                
                <div class="feature">
                    <h3>🔌 پشتیبانی از افزونه‌ها</h3>
                    <p>امکان نصب و استفاده از افزونه‌های مختلف برای گسترش قابلیت‌ها</p>
                </div>
                
                <div class="feature">
                    <h3>🎨 سیستم قالب‌دهی</h3>
                    <p>پشتیبانی از قالب‌های سفارشی و موتور نمایش Blade</p>
                </div>
                
                <div class="feature">
                    <h3>🌐 چندزبانه</h3>
                    <p>پشتیبانی از زبان‌های مختلف شامل فارسی، انگلیسی و عربی</p>
                </div>
                
                <div class="feature">
                    <h3>🔒 امنیت بالا</h3>
                    <p>رعایت اصول امنیتی و محافظت در برابر حملات رایج</p>
                </div>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <p><strong>نسخه فعلی:</strong> <?= e($version ?? '1.0.0') ?></p>
                <a href="/" class="btn">بازگشت به خانه</a>
            </div>
        </main>
        
        <footer>
            <p>© 2024 OmniCMS</p>
        </footer>
    </div>
</body>
</html>
