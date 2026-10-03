        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-archive"></i>
                </div>
                <h3><?= APP_NAME ?></h3>
                <small><?= APP_FULL_NAME ?></small>
            </div>
            
            <nav class="sidebar-menu">
                <div class="menu-label">Menu Utama</div>
                <a href="index.php" class="menu-item <?= $currentPage === 'index' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="arsip.php" class="menu-item <?= $currentPage === 'arsip' ? 'active' : '' ?>">
                    <i class="fas fa-folder-open"></i>
                    <span>Manajemen Arsip</span>
                </a>
                
                <div class="menu-label">Master Data</div>
                <a href="pegawai.php" class="menu-item <?= $currentPage === 'pegawai' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span>Pegawai</span>
                </a>
                <a href="pengguna.php" class="menu-item <?= $currentPage === 'pengguna' ? 'active' : '' ?>">
                    <i class="fas fa-user-friends"></i>
                    <span>Pengguna</span>
                </a>
                <a href="jenis_arsip.php" class="menu-item <?= $currentPage === 'jenis_arsip' ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i>
                    <span>Jenis Arsip</span>
                </a>
                <a href="klasifikasi.php" class="menu-item <?= $currentPage === 'klasifikasi' ? 'active' : '' ?>">
                    <i class="fas fa-sitemap"></i>
                    <span>Klasifikasi Arsip</span>
                </a>
                <a href="penyimpanan.php" class="menu-item <?= $currentPage === 'penyimpanan' ? 'active' : '' ?>">
                    <i class="fas fa-warehouse"></i>
                    <span>Lokasi Penyimpanan</span>
                </a>
                
                <?php if (hasAccess('Admin')): ?>
                <div class="menu-label">Administrasi</div>
                <a href="users.php" class="menu-item <?= $currentPage === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-user-shield"></i>
                    <span>Manajemen User</span>
                </a>
                <a href="roles.php" class="menu-item <?= $currentPage === 'roles' ? 'active' : '' ?>">
                    <i class="fas fa-key"></i>
                    <span>Roles & Hak Akses</span>
                </a>
                <a href="audit_log.php" class="menu-item <?= $currentPage === 'audit_log' ? 'active' : '' ?>">
                    <i class="fas fa-history"></i>
                    <span>Audit Log</span>
                </a>
                <?php endif; ?>
                
                <div class="menu-label">Laporan</div>
                <a href="laporan.php" class="menu-item <?= $currentPage === 'laporan' ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan Arsip</span>
                </a>
            </nav>
        </aside>
