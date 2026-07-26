<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'OmniCMS') ?> - <?= e(config('site.name', 'OmniCMS')) ?></title>
    
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Vazir Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <?= $head ?? '' ?>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="/">
                    <i class="fas fa-cube"></i> OmniCMS
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/"><?= __('messages.home') ?></a>
                        </li>
                        <?php if (isset($active_modules['Blog'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog"><?= __('messages.blog') ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (isset($active_modules['Shop'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/shop"><?= __('messages.shop') ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (isset($active_modules['Forum'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/forum"><?= __('messages.forum') ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <?php if (is_logged_in()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> <?= e($_SESSION['username'] ?? 'User') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="/dashboard"><i class="fas fa-tachometer-alt"></i> <?= __('messages.dashboard') ?></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> <?= __('messages.logout') ?></a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/login"><?= __('messages.login') ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm ms-2" href="/register"><?= __('messages.register') ?></a>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Language Switcher -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-globe"></i> <?= strtoupper($_SESSION['lang'] ?? 'FA') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="?lang=fa">فارسی</a></li>
                                <li><a class="dropdown-item" href="?lang=en">English</a></li>
                                <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                                <li><a class="dropdown-item" href="?lang=tr">Türkçe</a></li>
                                <li><a class="dropdown-item" href="?lang=fr">Français</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Flash Messages -->
    <?php if (session_has('success')): ?>
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session_get('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (session_has('error')): ?>
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session_get('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-4">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-cube"></i> OmniCMS</h5>
                    <p><?= __('messages.cms_description') ?></p>
                </div>
                <div class="col-md-4">
                    <h5><?= __('messages.quick_links') ?></h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-light text-decoration-none"><?= __('messages.home') ?></a></li>
                        <li><a href="/about" class="text-light text-decoration-none"><?= __('messages.about') ?></a></li>
                        <li><a href="/contact" class="text-light text-decoration-none"><?= __('messages.contact') ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5><?= __('messages.modules') ?></h5>
                    <ul class="list-unstyled">
                        <?php foreach (($active_modules ?? []) as $moduleName => $module): ?>
                        <li><a href="#" class="text-light text-decoration-none"><?= e($module['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> OmniCMS v<?= VERSION ?>. <?= __('messages.all_rights_reserved') ?></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>
    
    <?= $scripts ?? '' ?>
</body>
</html>
