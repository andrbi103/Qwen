<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت‌نام حساب کاربری</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 40px 20px; }
        .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #555; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        input:focus { outline: none; border-color: #4CAF50; }
        .btn { width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #45a049; }
        .error { background: #ffebee; color: #c62828; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #4CAF50; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>ثبت‌نام حساب کاربری</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/auth/register">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label for="username">نام کاربری:</label>
                <input type="text" id="username" name="username" required minlength="3" maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="email">ایمیل:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="first_name">نام:</label>
                <input type="text" id="first_name" name="first_name">
            </div>
            
            <div class="form-group">
                <label for="last_name">نام خانوادگی:</label>
                <input type="text" id="last_name" name="last_name">
            </div>
            
            <div class="form-group">
                <label for="password">رمز عبور:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">تکرار رمز عبور:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6">
            </div>
            
            <button type="submit" class="btn">ثبت‌نام</button>
        </form>
        
        <div class="links">
            <p>قبلاً ثبت‌نام کرده‌اید؟ <a href="/auth/login">وارد شوید</a></p>
            <p><a href="/">بازگشت به صفحه اصلی</a></p>
        </div>
    </div>
</body>
</html>
