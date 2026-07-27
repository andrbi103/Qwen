<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'تماس با ما') ?> - OmniCMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        header h1 { font-size: 2.5em; margin-bottom: 10px; }
        nav { background: white; padding: 15px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav ul { list-style: none; display: flex; gap: 20px; justify-content: center; }
        nav a { color: #667eea; text-decoration: none; font-weight: bold; }
        main { background: white; margin-top: 20px; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        footer { text-align: center; padding: 20px; margin-top: 30px; color: #666; }
        .btn { display: inline-block; background: #667eea; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; }
        .btn:hover { background: #5a6fd6; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 5px; font-family: inherit; font-size: 1em; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #667eea; }
        .form-group textarea { min-height: 150px; resize: vertical; }
        .error { color: #dc3545; font-size: 0.9em; margin-top: 5px; }
        .errors { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .contact-info { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .contact-info p { margin: 10px 0; }
    </style>
</head>
<body>
    <header>
        <h1>📧 تماس با ما</h1>
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
            <?php if (!empty($success)): ?>
                <div class="success"><?= e($success) ?></div>
            <?php endif; ?>
            
            <div class="contact-info">
                <h3>اطلاعات تماس</h3>
                <p>📍 آدرس: تهران، ایران</p>
                <p>📧 ایمیل: info@omnicms.ir</p>
                <p>📞 تلفن: ۰۲۱-۱۲۳۴۵۶۷۸</p>
            </div>
            
            <h3 style="margin-bottom: 20px;">ارسال پیام</h3>
            
            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <strong>خطاهای فرم:</strong>
                    <ul style="margin-top: 10px; margin-right: 20px;">
                        <?php foreach ($errors as $field => $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/contact">
                <div class="form-group">
                    <label for="name">نام و نام خانوادگی *</label>
                    <input type="text" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="error"><?= e($errors['name']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">ایمیل *</label>
                    <input type="email" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="error"><?= e($errors['email']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="message">پیام شما *</label>
                    <textarea id="message" name="message" required><?= e($old['message'] ?? '') ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <div class="error"><?= e($errors['message']) ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn">ارسال پیام</button>
            </form>
        </main>
        
        <footer>
            <p>© 2024 OmniCMS</p>
        </footer>
    </div>
</body>
</html>
