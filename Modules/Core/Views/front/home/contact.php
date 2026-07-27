<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'تماس با ما' ?></title>
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
        .contact-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 30px; max-width: 600px; }
        .contact-card h2 { color: #2c3e50; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; }
        .form-group textarea { height: 150px; resize: vertical; }
        .btn { padding: 12px 24px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
        .btn:hover { background: #2980b9; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .error-text { color: #e74c3c; font-size: 14px; margin-top: 5px; }
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
                    <li><a href="/about">درباره ما</a></li>
                    <li><a href="/contact" style="background: #34495e;">تماس با ما</a></li>
                    <li><a href="/admin/dashboard" style="background: #e74c3c;">پنل مدیریت</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="content">
        <div class="container">
            <div class="contact-card">
                <h2>تماس با ما</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/contact">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="name">نام:</label>
                        <input type="text" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="error-text"><?= e($errors['name']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">ایمیل:</label>
                        <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="error-text"><?= e($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">پیام:</label>
                        <textarea id="message" name="message" required><?= e($old['message'] ?? '') ?></textarea>
                        <?php if (isset($errors['message'])): ?>
                            <div class="error-text"><?= e($errors['message']) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn">ارسال پیام</button>
                </form>
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
