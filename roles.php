<?php
$pageTitle = 'Roles & Hak Akses';
require_once 'includes/header.php';

if (!hasAccess('Admin')) {
    header('Location: index.php');
    exit();
}

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $stmt = $db->prepare("INSERT INTO Roles (Nama_Role, Deskripsi_Role, Hak_Akses) VALUES (?, ?, ?)");
        $stmt->execute([sanitize($_POST['nama_role']), sanitize($_POST['deskripsi_role']), sanitize($_POST['hak_akses'])]);
        alert('Role berhasil ditambahkan!', 'success');
        header('Location: roles.php');
        exit();
    }
    
    if ($action === 'update') {
        $stmt = $db->prepare("UPDATE Roles SET Nama_Role=?, Deskripsi_Role=?, Hak_Akses=? WHERE ID_Role=?");
        $stmt->execute([sanitize($_POST['nama_role']), sanitize($_POST['deskripsi_role']), sanitize($_POST['hak_akses']), $_POST['id_role']]);
        alert('Role berhasil diperbarui!', 'success');
        header('Location: roles.php');
        exit();
    }
    
    if ($action === 'delete') {
        $stmt = $db->prepare("DELETE FROM Roles WHERE ID_Role = ?");
        $stmt->execute([$_POST['id_role']]);
        alert('Role berhasil dihapus!', 'success');
        header('Location: roles.php');
        exit();
    }
}

$rolesList = $db->query("SELECT * FROM Roles ORDER BY Created_At DESC")->fetchAll();

require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Roles & Hak Akses</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fas fa-plus me-2"></i>Tambah Role
    </button>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-key me-2"></i>Daftar Roles</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>Nama Role</th>
                        <th>Deskripsi</th>
                        <th>Hak Akses</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rolesList as $role): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($role['Nama_Role']) ?></strong></td>
                        <td><?= htmlspecialchars($role['Deskripsi_Role'] ?? '-') ?></td>
                        <td><code><?= htmlspecialchars($role['Hak_Akses'] ?? '-') ?></code></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-warning" onclick="editRole(<?= htmlspecialchars(json_encode($role)) ?>)"><i class="fas fa-edit"></i></button>
                                <form id="delete-form-<?= $role['ID_Role'] ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_role" value="<?= $role['ID_Role'] ?>">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $role['ID_Role'] ?>, '<?= htmlspecialchars($role['Nama_Role']) ?>')"><i class="fas fa-trash"></i></button>
                                </form>
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
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Role *</label><input type="text" name="nama_role" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi_role" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="form-label">Hak Akses</label><input type="text" name="hak_akses" class="form-control" placeholder="Contoh: all, arsip,pegawai, view"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Role</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id_role" id="edit_id_role">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Role *</label><input type="text" name="nama_role" id="edit_nama_role" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="deskripsi_role" id="edit_deskripsi_role" class="form-control" rows="3"></textarea></div>
                    <div class="mb-3"><label class="form-label">Hak Akses</label><input type="text" name="hak_akses" id="edit_hak_akses" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function editRole(role) {
    document.getElementById('edit_id_role').value = role.ID_Role;
    document.getElementById('edit_nama_role').value = role.Nama_Role || '';
    document.getElementById('edit_deskripsi_role').value = role.Deskripsi_Role || '';
    document.getElementById('edit_hak_akses').value = role.Hak_Akses || '';
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
