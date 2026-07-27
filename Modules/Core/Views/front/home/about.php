<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'درباره ما' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; line-height: 1.6; }
        .header { background: #2c3e50; color: white; padding: 20px 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .nav { display: flex; justify-content: space-between; align-items: center; }
        .nav h1 { font-size: 24px; }
        .nav ul { list-style: none; display: flex; gap: 20px; }
        .nav a { color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; transition: background 0.3s; }
        .nav a:hover { background: #34495e; }
        .content { padding: 60px 0; }
        .about-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 30px; }
        .about-card h2 { color: #2c3e50; margin-bottom: 20px; }
        .about-card p { color: #555; margin-bottom: 15px; }
        .footer { background: #2c3e50; color: white; text-align: center; padding: 30px 0; margin-top: 60px; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container nav">
            <h1>OmniCMS</h1>
            <nav>
                <ul>
                    <li><a href="/">صفحه اصلی</a></li>
                    <li><a href="/about" style="background: #34495e;">درباره ما</a></li>
                    <li><a href="/contact">تماس با ما</a></li>
                    <li><a href="/admin/dashboard" style="background: #e74c3c;">پنل مدیریت</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="content">
        <div class="container">
            <div class="about-card">
                <h2>درباره OmniCMS</h2>
                <p>OmniCMS یک سیستم مدیریت محتوای چندمنظوره است که برای ساخت و مدیریت وبسایت‌های مختلف طراحی شده است.</p>
                <p>این سیستم با استفاده از معماری ماژولار، امکان توسعه و گسترش آسان را فراهم می‌کند.</p>
                <p>نسخه فعلی: <?= e($version ?? '1.0.0') ?></p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> OmniCMS. تمامی حقوق محفوظ است.</p>
        </div>
    </footer>
</body>
</html>
