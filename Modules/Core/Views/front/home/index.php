<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'OmniCMS' ?></title>
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
        .hero { background: linear-gradient(135deg, #3498db, #2c3e50); color: white; padding: 80px 0; text-align: center; }
        .hero h2 { font-size: 36px; margin-bottom: 20px; }
        .hero p { font-size: 18px; opacity: 0.9; }
        .modules { padding: 60px 0; }
        .modules h3 { text-align: center; margin-bottom: 40px; font-size: 28px; color: #2c3e50; }
        .modules-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .module-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; }
        .module-card:hover { transform: translateY(-5px); }
        .module-card h4 { color: #3498db; margin-bottom: 15px; font-size: 20px; }
        .footer { background: #2c3e50; color: white; text-align: center; padding: 30px 0; margin-top: 60px; }
        .btn { display: inline-block; padding: 12px 24px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; transition: background 0.3s; }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>
    <header class="header">
        <div class="container nav">
            <h1>OmniCMS</h1>
            <nav>
                <ul>
                    <li><a href="/">صفحه اصلی</a></li>
                    <li><a href="/about">درباره ما</a></li>
                    <li><a href="/contact">تماس با ما</a></li>
                    <li><a href="/admin/dashboard" style="background: #e74c3c;">پنل مدیریت</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2>سیستم مدیریت محتوای چندمنظوره</h2>
            <p>راهکاری کامل برای مدیریت وبسایت شما</p>
            <a href="/about" class="btn">بیشتر بدانید</a>
        </div>
    </section>

    <section class="modules">
        <div class="container">
            <h3>ماژول‌های موجود</h3>
            <div class="modules-grid">
                <?php foreach ($modules as $module): ?>
                <div class="module-card">
                    <h4><?= e($module) ?></h4>
                    <p>ماژول <?= e($module) ?> برای مدیریت <?= strtolower(e($module)) ?> سایت شما</p>
                </div>
                <?php endforeach; ?>
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
