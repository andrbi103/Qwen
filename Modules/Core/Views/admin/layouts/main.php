<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'پنل مدیریت' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h2 { margin-bottom: 30px; font-size: 18px; text-align: center; }
        .sidebar nav a { display: block; color: #ecf0f1; text-decoration: none; padding: 12px; margin: 5px 0; border-radius: 4px; transition: background 0.3s; }
        .sidebar nav a:hover { background: #34495e; }
        .sidebar nav a.active { background: #3498db; }
        .main-content { flex: 1; padding: 30px; }
        .header { background: white; padding: 20px; margin-bottom: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .content { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
        .stat-card { background: #3498db; color: white; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-card h3 { font-size: 32px; margin-bottom: 10px; }
        .stat-card p { opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .status-active { color: #27ae60; font-weight: bold; }
        .status-inactive { color: #e74c3c; font-weight: bold; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <h2>پنل مدیریت</h2>
            <nav>
                <a href="/admin/dashboard" <?= (isset($currentPage) && $currentPage == 'dashboard') ? 'class="active"' : '' ?>>داشبورد</a>
                <a href="/admin/modules" <?= (isset($currentPage) && $currentPage == 'modules') ? 'class="active"' : '' ?>>ماژول‌ها</a>
                <a href="/admin/plugins" <?= (isset($currentPage) && $currentPage == 'plugins') ? 'class="active"' : '' ?>>افزونه‌ها</a>
                <a href="/admin/settings" <?= (isset($currentPage) && $currentPage == 'settings') ? 'class="active"' : '' ?>>تنظیمات</a>
                <a href="/admin/profile" <?= (isset($currentPage) && $currentPage == 'profile') ? 'class="active"' : '' ?>>پروفایل</a>
                <a href="/" style="margin-top: 30px; border-top: 1px solid #34495e; padding-top: 15px;">بازگشت به سایت</a>
            </nav>
        </aside>
        <main class="main-content">
            <div class="header">
                <h1><?= $title ?? 'پنل مدیریت' ?></h1>
            </div>
            <div class="content">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
</body>
</html>
