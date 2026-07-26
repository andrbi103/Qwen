<?php $this->layout('layouts/main'); ?>

<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">
                <i class="fas fa-cube"></i> <?= __('messages.welcome') ?>
            </h1>
            <p class="lead text-muted"><?= __('messages.welcome_description') ?></p>
            <div class="mt-4">
                <?php if (!is_logged_in()): ?>
                    <a href="/register" class="btn btn-primary btn-lg ms-2">
                        <i class="fas fa-user-plus"></i> <?= __('messages.get_started') ?>
                    </a>
                    <a href="/login" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-sign-in-alt"></i> <?= __('messages.login') ?>
                    </a>
                <?php else: ?>
                    <a href="/dashboard" class="btn btn-primary btn-lg">
                        <i class="fas fa-tachometer-alt"></i> <?= __('messages.dashboard') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Active Modules -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="border-bottom pb-2 mb-4"><?= __('messages.active_modules') ?></h3>
        </div>
        
        <?php foreach (($modules ?? []) as $moduleName => $module): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body text-center">
                    <?php 
                    $icon = 'fa-cube';
                    if ($moduleName === 'Blog') $icon = 'fa-newspaper';
                    elseif ($moduleName === 'Shop') $icon = 'fa-shopping-cart';
                    elseif ($moduleName === 'Forum') $icon = 'fa-comments';
                    ?>
                    <div class="mb-3">
                        <i class="fas <?= $icon ?> fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title"><?= e($module['name']) ?></h5>
                    <p class="card-text text-muted"><?= e($module['description'] ?? '') ?></p>
                    <a href="<?= '/' . strtolower($moduleName) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left"></i> <?= __('messages.view') ?>
                    </a>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <small class="text-muted">v<?= e($module['version']) ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Features -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="border-bottom pb-2 mb-4"><?= __('messages.features') ?></h3>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="text-center">
                <i class="fas fa-plug fa-2x text-success mb-2"></i>
                <h6><?= __('messages.modular_system') ?></h6>
                <p class="small text-muted"><?= __('messages.modular_desc') ?></p>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="text-center">
                <i class="fas fa-shield-alt fa-2x text-info mb-2"></i>
                <h6><?= __('messages.secure') ?></h6>
                <p class="small text-muted"><?= __('messages.secure_desc') ?></p>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="text-center">
                <i class="fas fa-globe fa-2x text-warning mb-2"></i>
                <h6><?= __('messages.multilingual') ?></h6>
                <p class="small text-muted"><?= __('messages.multilingual_desc') ?></p>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="text-center">
                <i class="fas fa-bolt fa-2x text-danger mb-2"></i>
                <h6><?= __('messages.fast') ?></h6>
                <p class="small text-muted"><?= __('messages.fast_desc') ?></p>
            </div>
        </div>
    </div>

    <!-- Statistics (if logged in as admin) -->
    <?php if (is_admin()): ?>
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="border-bottom pb-2 mb-4"><?= __('messages.quick_stats') ?></h3>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h2><i class="fas fa-users"></i> <?= $stats['total_users'] ?? 0 ?></h2>
                    <p><?= __('messages.total_users') ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h2><i class="fas fa-file-alt"></i> <?= $stats['total_posts'] ?? 0 ?></h2>
                    <p><?= __('messages.total_posts') ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h2><i class="fas fa-puzzle-piece"></i> <?= $stats['active_modules'] ?? 0 ?></h2>
                    <p><?= __('messages.active_modules') ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h2><i class="fas fa-extension"></i> <?= $stats['active_plugins'] ?? 0 ?></h2>
                    <p><?= __('messages.active_plugins') ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Add hover effect to module cards
    $('.card').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
});
</script>
<?= $this->endSection(); ?>
