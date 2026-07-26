<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin Panel') ?> - OmniCMS</title>
    
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Vazir Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <!-- Admin CSS -->
    <link rel="stylesheet" href="/assets/css/admin.css">
    
    <?= $head ?? '' ?>
</head>
<body class="bg-light">
    <!-- Top Navbar -->
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/admin/dashboard">
            <i class="fas fa-cube"></i> OmniCMS Admin
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="w-100"></div>
        
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle"></i> <?= e($_SESSION['username'] ?? 'Admin') ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/"><i class="fas fa-home"></i> <?= __('messages.site_home') ?></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> <?= __('messages.logout') ?></a></li>
                </ul>
            </li>
        </ul>
    </header>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/dashboard*') ? 'active' : '' ?>" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt"></i> <?= __('messages.dashboard') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/users*') ? 'active' : '' ?>" href="/admin/users">
                                <i class="fas fa-users"></i> <?= __('messages.users') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/posts*') ? 'active' : '' ?>" href="/admin/posts">
                                <i class="fas fa-file-alt"></i> <?= __('messages.posts') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/categories*') ? 'active' : '' ?>" href="/admin/categories">
                                <i class="fas fa-folder"></i> <?= __('messages.categories') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/modules*') ? 'active' : '' ?>" href="/admin/modules">
                                <i class="fas fa-puzzle-piece"></i> <?= __('messages.modules') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/plugins*') ? 'active' : '' ?>" href="/admin/plugins">
                                <i class="fas fa-extension"></i> <?= __('messages.plugins') ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= request()->is('admin/settings*') ? 'active' : '' ?>" href="/admin/settings">
                                <i class="fas fa-cog"></i> <?= __('messages.settings') ?>
                            </a>
                        </li>
                    </ul>

                    <hr class="my-4">
                    
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span><?= __('messages.modules') ?></span>
                    </h6>
                    <ul class="nav flex-column">
                        <?php foreach (($GLOBALS['active_modules'] ?? []) as $moduleName => $module): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/<?= strtolower($moduleName) ?>">
                                <i class="fas fa-box"></i> <?= e($module['name']) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Admin JS -->
    <script src="/assets/js/admin.js"></script>
    
    <?= $scripts ?? '' ?>
</body>
</html>
