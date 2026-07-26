<?php $this->layout('layouts/admin'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/dashboard">
                            <i class="fas fa-tachometer-alt"></i> <?= __('messages.dashboard') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">
                            <i class="fas fa-users"></i> <?= __('messages.users') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/posts">
                            <i class="fas fa-file-alt"></i> <?= __('messages.posts') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/modules">
                            <i class="fas fa-puzzle-piece"></i> <?= __('messages.modules') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/plugins">
                            <i class="fas fa-extension"></i> <?= __('messages.plugins') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/settings">
                            <i class="fas fa-cog"></i> <?= __('messages.settings') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2"><?= __('messages.admin_dashboard') ?></h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download"></i> <?= __('messages.export') ?>
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title"><?= __('messages.total_users') ?></h6>
                                    <h2 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h2>
                                </div>
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title"><?= __('messages.total_posts') ?></h6>
                                    <h2 class="mb-0"><?= $stats['total_posts'] ?? 0 ?></h2>
                                </div>
                                <i class="fas fa-file-alt fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title"><?= __('messages.active_modules') ?></h6>
                                    <h2 class="mb-0"><?= $stats['active_modules'] ?? 0 ?></h2>
                                </div>
                                <i class="fas fa-puzzle-piece fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title"><?= __('messages.active_plugins') ?></h6>
                                    <h2 class="mb-0"><?= $stats['active_plugins'] ?? 0 ?></h2>
                                </div>
                                <i class="fas fa-extension fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-line"></i> <?= __('messages.activity_chart') ?>
                        </div>
                        <div class="card-body">
                            <canvas id="activityChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-pie"></i> <?= __('messages.module_distribution') ?>
                        </div>
                        <div class="card-body">
                            <canvas id="moduleChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users"></i> <?= __('messages.recent_users') ?></span>
                            <a href="/admin/users" class="btn btn-sm btn-primary"><?= __('messages.view_all') ?></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th><?= __('messages.username') ?></th>
                                            <th><?= __('messages.email') ?></th>
                                            <th><?= __('messages.role') ?></th>
                                            <th><?= __('messages.created_at') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (($recentUsers ?? []) as $user): ?>
                                        <tr>
                                            <td><?= $user['id'] ?></td>
                                            <td><?= e($user['username']) ?></td>
                                            <td><?= e($user['email']) ?></td>
                                            <td><span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'secondary' ?>"><?= e($user['role']) ?></span></td>
                                            <td><?= e($user['created_at']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Posts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-alt"></i> <?= __('messages.recent_posts') ?></span>
                            <a href="/admin/posts" class="btn btn-sm btn-primary"><?= __('messages.view_all') ?></a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th><?= __('messages.title') ?></th>
                                            <th><?= __('messages.type') ?></th>
                                            <th><?= __('messages.status') ?></th>
                                            <th><?= __('messages.created_at') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (($recentPosts ?? []) as $post): ?>
                                        <tr>
                                            <td><?= $post['id'] ?></td>
                                            <td><?= e($post['title']) ?></td>
                                            <td><span class="badge bg-info"><?= e($post['type']) ?></span></td>
                                            <td><span class="badge bg-<?= $post['is_published'] ? 'success' : 'warning' ?>"><?= $post['is_published'] ? __('messages.published') : __('messages.draft') ?></span></td>
                                            <td><?= e($post['created_at']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Activity Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['<?= __('messages.jan') ?>', '<?= __('messages.feb') ?>', '<?= __('messages.mar') ?>', '<?= __('messages.apr') ?>', '<?= __('messages.may') ?>', '<?= __('messages.jun') ?>'],
            datasets: [{
                label: '<?= __('messages.users') ?>',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: '<?= __('messages.posts') ?>',
                data: [8, 15, 7, 10, 6, 9],
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Module Distribution Chart
    const moduleCtx = document.getElementById('moduleChart').getContext('2d');
    new Chart(moduleCtx, {
        type: 'doughnut',
        data: {
            labels: ['Blog', 'Shop', 'Forum'],
            datasets: [{
                data: [30, 20, 15],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
<?= $this->endSection(); ?>
