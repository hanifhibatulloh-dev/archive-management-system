<?php
$pageTitle = 'Profil Saya';
require_once 'includes/header.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // UPDATE PROFIL
    if ($action === 'update_profile') {
        // Sesuaikan nama tabel 'users' dan kolom
        $stmt = $db->prepare("UPDATE users SET nama_lengkap=?, email=? WHERE id_user=?");
        $stmt->execute([sanitize($_POST['nama_lengkap']), sanitize($_POST['email']), $_SESSION['user_id']]);
        
        // Update session nama agar tampilan di header langsung berubah
        $_SESSION['user_name'] = sanitize($_POST['nama_lengkap']);
        
        alert('Profil berhasil diperbarui!', 'success');
        header('Location: profile.php');
        exit();
    }
    
    // GANTI PASSWORD
    if ($action === 'change_password') {
        // Ambil password lama dari tabel users
        $stmt = $db->prepare("SELECT password FROM users WHERE id_user = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($_POST['current_password'], $user['password'])) {
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                
                // Update password baru
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id_user = ?");
                $stmt->execute([$newPassword, $_SESSION['user_id']]);
                
                alert('Password berhasil diubah!', 'success');
            } else {
                alert('Konfirmasi password tidak cocok!', 'error');
            }
        } else {
            alert('Password saat ini salah!', 'error');
        }
        header('Location: profile.php');
        exit();
    }
}

// AMBIL DATA USER (JOIN dengan Role untuk mendapatkan nama role)
$stmt = $db->prepare("
    SELECT u.*, r.nama_role 
    FROM users u 
    LEFT JOIN roles r ON u.id_role = r.id_role 
    WHERE u.id_user = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';
?>

<div class="page-header">
    <h1 class="page-title">Profil Saya</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Profil</li>
        </ol>
    </nav>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="user-avatar mx-auto mb-3" style="width: 100px; height: 100px; font-size: 40px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                </div>
                
                <h4><?= htmlspecialchars($user['nama_lengkap']) ?></h4>
                <p class="text-muted mb-3">@<?= htmlspecialchars($user['username']) ?></p>
                
                <span class="badge <?= $user['nama_role'] === 'Admin' ? 'bg-danger' : ($user['nama_role'] === 'Operator' ? 'bg-warning' : 'bg-secondary') ?> fs-6">
                    <?= htmlspecialchars($user['nama_role']) ?>
                </span>
            </div>
            <div class="card-footer bg-light">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>Terdaftar Sejak: <?= date('d M Y', strtotime($user['created_at'])) ?>
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-user me-2"></i>Informasi Profil</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['nama_role']) ?>" disabled>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header"><i class="fas fa-lock me-2"></i>Ubah Password</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Password Saat Ini *</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Password Baru *</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Konfirmasi Password *</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-key me-2"></i>Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>