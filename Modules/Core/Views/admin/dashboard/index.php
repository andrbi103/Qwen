<div class="stats-grid">
    <div class="stat-card">
        <h3><?= $stats['modules'] ?></h3>
        <p>ماژول‌ها</p>
    </div>
    <div class="stat-card">
        <h3><?= $stats['plugins'] ?></h3>
        <p>افزونه‌ها</p>
    </div>
    <div class="stat-card">
        <h3><?= $stats['users'] ?></h3>
        <p>کاربران</p>
    </div>
    <div class="stat-card">
        <h3><?= $stats['settings'] ?></h3>
        <p>تنظیمات</p>
    </div>
</div>

<h2 style="margin-top: 40px; margin-bottom: 20px;">آخرین فعالیت‌ها</h2>
<table>
    <thead>
        <tr>
            <th>فعالیت</th>
            <th>تاریخ</th>
            <th>وضعیت</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ورود به پنل مدیریت</td>
            <td><?= date('Y-m-d H:i:s') ?></td>
            <td class="status-active">موفق</td>
        </tr>
        <tr>
            <td>بررسی ماژول‌ها</td>
            <td><?= date('Y-m-d H:i:s', strtotime('-5 minutes')) ?></td>
            <td class="status-active">موفق</td>
        </tr>
        <tr>
            <td>به‌روزرسانی تنظیمات</td>
            <td><?= date('Y-m-d H:i:s', strtotime('-1 hour')) ?></td>
            <td class="status-active">موفق</td>
        </tr>
    </tbody>
</table>
