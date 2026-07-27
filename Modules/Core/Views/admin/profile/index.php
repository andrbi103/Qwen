<div style="max-width: 600px;">
    <div style="margin-bottom: 20px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
        <h3 style="margin-bottom: 15px;">اطلاعات کاربری</h3>
        <p><strong>نام:</strong> <?= $user['name'] ?></p>
        <p><strong>ایمیل:</strong> <?= $user['email'] ?></p>
        <p><strong>نقش:</strong> <?= $user['role'] ?></p>
        <p><strong>تاریخ عضویت:</strong> <?= $user['created_at'] ?></p>
    </div>

    <form method="POST" action="/admin/profile/update">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">نام نمایشی:</label>
            <input type="text" name="name" value="<?= $user['name'] ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">ایمیل:</label>
            <input type="email" name="email" value="<?= $user['email'] ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">رمز عبور جدید:</label>
            <input type="password" name="password" placeholder="در صورت عدم تغییر خالی بگذارید" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <button type="submit" class="btn btn-primary">به‌روزرسانی پروفایل</button>
    </form>
</div>
