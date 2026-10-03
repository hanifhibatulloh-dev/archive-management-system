<?php
$pageTitle = 'Manajemen Pengguna (Users)';
require_once 'includes/header.php';
$db = getDB();

// ==========================================
// 1. LOGIKA HAPUS (Metode GET Link)
// ==========================================
if (isset($_GET['hapus_id'])) {
    if (hasAccess('Admin')) {
        try {
            $id = $_GET['hapus_id'];
            // Hapus dari tabel 'users'
            $stmt = $db->prepare("DELETE FROM users WHERE id_user = ?");
            $stmt->execute([$id]);
            
            echo "<script>alert('User berhasil dihapus!'); window.location='pengguna.php';</script>";
            exit();
        } catch (PDOException $e) {
            echo "<script>alert('Gagal menghapus! User ini mungkin terhubung dengan data Arsip.'); window.location='pengguna.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Akses Ditolak!'); window.location='pengguna.php';</script>";
        exit();
    }
}

// ==========================================
// 2. LOGIKA TAMBAH & EDIT
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // --- TAMBAH USER ---
    if ($action === 'create' && hasAccess('Admin')) {
        try {
            // Cek apakah username sudah ada
            $cek = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
            $cek->execute([$_POST['username']]);
            if ($cek->fetchColumn() > 0) {
                echo "<script>alert('Username sudah digunakan!'); window.location='pengguna.php';</script>";
                exit();
            }

            // Insert ke tabel 'users'
            // Password di-hash biar aman
            $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("INSERT INTO users (nama_lengkap, username, password, level, email, no_hp) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nama_lengkap'],
                $_POST['username'],
                $passwordHash,
                $_POST['level'],
                $_POST['email'],
                $_POST['no_hp']
            ]);

            echo "<script>alert('User berhasil ditambahkan!'); window.location='pengguna.php';</script>";
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }

    // --- EDIT USER ---
    if ($action === 'update' && hasAccess('Admin')) {
        try {
            $id = $_POST['id_user'];
            $nama = $_POST['nama_lengkap'];
            $user = $_POST['username'];
            $level = $_POST['level'];
            $email = $_POST['email'];
            $hp = $_POST['no_hp'];
            
            // Cek apakah password diganti?
            if (!empty($_POST['password'])) {
                $passHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE users SET nama_lengkap=?, username=?, password=?, level=?, email=?, no_hp=? WHERE id_user=?";
                $params = [$nama, $user, $passHash, $level, $email, $hp, $id];
            } else {
                // Kalau password kosong, jangan diupdate
                $sql = "UPDATE users SET nama_lengkap=?, username=?, level=?, email=?, no_hp=? WHERE id_user=?";
                $params = [$nama, $user, $level, $email, $hp, $id];
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            echo "<script>alert('User berhasil diperbarui!'); window.location='pengguna.php';</script>";
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}

// ==========================================
// 3. AMBIL DATA (Tabel users)
// ==========================================
// Menggunakan tabel 'users' sesuai index.php
$userList = $db->query("SELECT * FROM users ORDER BY nama_lengkap ASC")->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Manajemen Pengguna (Users)</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Pengguna</li>
            </ol>
        </nav>
    </div>
    <?php if (hasAccess('Admin')): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-user-plus me-2"></i>Tambah User
    </button>
    <?php endif; ?>
</div>

<div class="card mt-3">
    <div class="card-header"><i class="fas fa-users-cog me-2"></i>Daftar Users System</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table data-table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userList as $u): ?>
                    <?php 
                        // Deteksi nama kolom
                        $id = $u['id_user'] ?? $u['ID_User'];
                        $nama = $u['nama_lengkap'] ?? $u['Nama_Lengkap'];
                        $username = $u['username'] ?? $u['Username'];
                        $level = $u['level'] ?? $u['Level'] ?? 'Operator';
                        $email = $u['email'] ?? $u['Email'] ?? '-';
                        $hp = $u['no_hp'] ?? $u['No_HP'] ?? '-';
                        
                        $badgeClass = ($level === 'Admin') ? 'bg-danger' : 'bg-primary';
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($nama) ?></strong></td>
                        <td><?= htmlspecialchars($username) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($level) ?></span></td>
                        <td><?= htmlspecialchars($email) ?></td>
                        <td><?= htmlspecialchars($hp) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <?php if (hasAccess('Admin')): ?>
                                <button class="btn btn-warning" onclick='editUser(<?= json_encode($u) ?>)' title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <a href="pengguna.php?hapus_id=<?= $id ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus user ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Tambah User Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Username *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Level Akses</label>
                        <select name="level" class="form-select">
                            <option value="Operator">Operator</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_user" id="edit_id">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Username *</label>
                            <input type="text" name="username" id="edit_username" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password (Isi jika ingin ubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tetap">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Level Akses</label>
                        <select name="level" id="edit_level" class="form-select">
                            <option value="Operator">Operator</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>No. HP</label>
                        <input type="text" name="no_hp" id="edit_hp" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(data) {
    document.getElementById('edit_id').value = data.id_user || data.ID_User;
    document.getElementById('edit_nama').value = data.nama_lengkap || data.Nama_Lengkap || '';
    document.getElementById('edit_username').value = data.username || data.Username || '';
    document.getElementById('edit_level').value = data.level || data.Level || 'Operator';
    document.getElementById('edit_email').value = data.email || data.Email || '';
    document.getElementById('edit_hp').value = data.no_hp || data.No_HP || '';
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

<?php require_once 'includes/footer.php'; ?>