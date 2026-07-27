<table>
    <thead>
        <tr>
            <th>نام ماژول</th>
            <th>نسخه</th>
            <th>توضیحات</th>
            <th>وضعیت</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($modules as $module): ?>
        <tr>
            <td><?= $module['name'] ?></td>
            <td><?= $module['version'] ?></td>
            <td><?= $module['description'] ?></td>
            <td>
                <span class="status-<?= $module['status'] ?>">
                    <?= $module['status'] == 'active' ? 'فعال' : 'غیرفعال' ?>
                </span>
            </td>
            <td>
                <?php if ($module['status'] == 'active'): ?>
                    <a href="/admin/modules/deactivate/<?= $module['name'] ?>" class="btn btn-danger">غیرفعال‌سازی</a>
                <?php else: ?>
                    <a href="/admin/modules/activate/<?= $module['name'] ?>" class="btn btn-success">فعال‌سازی</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
