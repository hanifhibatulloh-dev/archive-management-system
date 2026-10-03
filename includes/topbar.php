        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <button class="toggle-sidebar" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h4><?= $pageTitle ?? 'Dashboard' ?></h4>
                </div>
                <div class="header-right">
                    <div class="user-info dropdown">
                        <div class="d-flex align-items-center" data-bs-toggle="dropdown" style="cursor: pointer;">
                            <div class="user-avatar">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="user-details ms-2">
                                <div class="name"><?= $_SESSION['user_name'] ?? 'User' ?></div>
                                <div class="role"><?= $_SESSION['user_role'] ?? 'Viewer' ?></div>
                            </div>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <div class="page-content">
                <?= showAlert() ?>
