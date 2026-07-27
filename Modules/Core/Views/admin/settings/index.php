<form method="POST" action="/admin/settings/update">
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">نام سایت:</label>
        <input type="text" name="site_name" value="<?= $settings['site_name'] ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">آدرس سایت:</label>
        <input type="url" name="site_url" value="<?= $settings['site_url'] ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">ایمیل مدیریت:</label>
        <input type="email" name="admin_email" value="<?= $settings['admin_email'] ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">زبان:</label>
        <select name="language" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="fa" <?= $settings['language'] == 'fa' ? 'selected' : '' ?>>فارسی</option>
            <option value="en" <?= $settings['language'] == 'en' ? 'selected' : '' ?>>English</option>
        </select>
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: bold;">منطقه زمانی:</label>
        <select name="timezone" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            <option value="Asia/Tehran" <?= $settings['timezone'] == 'Asia/Tehran' ? 'selected' : '' ?>>Asia/Tehran</option>
            <option value="UTC" <?= $settings['timezone'] == 'UTC' ? 'selected' : '' ?>>UTC</option>
        </select>
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: flex; align-items: center;">
            <input type="checkbox" name="maintenance_mode" <?= $settings['maintenance_mode'] ? 'checked' : '' ?> style="margin-left: 10px;">
            حالت تعمیر و نگهداری
        </label>
    </div>
    <button type="submit" class="btn btn-primary">ذخیره تنظیمات</button>
</form>
