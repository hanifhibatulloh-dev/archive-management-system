<?php
$pageTitle = 'Audit Log';
require_once 'includes/header.php';

if (!hasAccess('Admin')) {
    header('Location: index.php');
    exit();
}

$db = getDB();
$auditList = $db->query("SELECT * FROM Audit_Log ORDER BY Change_Date DESC LIMIT 500")->fetchAll();

require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';
?>

<div class="page-header">
    <h1 class="page-title">Audit Log</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Audit Log</li>
        </ol>
    </nav>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-history me-2"></i>Riwayat Perubahan Data</div>
    <div class="card-body">
        <?php if (empty($auditList)): ?>
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h5>Belum Ada Log</h5>
            <p>Riwayat perubahan data akan ditampilkan di sini.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tabel</th>
                        <th>Aksi</th>
                        <th>Record ID</th>
                        <th>Kolom</th>
                        <th>Nilai Lama</th>
                        <th>Nilai Baru</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auditList as $log): ?>
                    <tr>
                        <td><?= formatDateTime($log['Change_Date']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($log['Table_Name']) ?></span></td>
                        <td>
                            <span class="badge <?= $log['Action_Type'] === 'INSERT' ? 'bg-success' : ($log['Action_Type'] === 'DELETE' ? 'bg-danger' : 'bg-warning') ?>">
                                <?= $log['Action_Type'] ?>
                            </span>
                        </td>
                        <td><?= $log['Record_ID'] ?></td>
                        <td><?= htmlspecialchars($log['Column_Name'] ?? '-') ?></td>
                        <td><small><?= htmlspecialchars($log['Old_Value'] ?? '-') ?></small></td>
                        <td><small><?= htmlspecialchars($log['New_Value'] ?? '-') ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
