<?php
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

$db = getDB();


// 1. Statistik Arsip
$statsArsip = $db->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN Status_Arsip = 'Aktif' THEN 1 ELSE 0 END) as aktif,
        SUM(CASE WHEN Status_Arsip = 'Inaktif' THEN 1 ELSE 0 END) as inaktif,
        SUM(CASE WHEN Status_Arsip = 'Terarsipkan' THEN 1 ELSE 0 END) as terarsipkan,
        SUM(CASE WHEN Status_Arsip = 'Musnah' THEN 1 ELSE 0 END) as musnah
    FROM Arsip
")->fetch();


// 2. Total Pegawai
$totalPegawai = $db->query("
    SELECT COUNT(*) as total 
    FROM Pegawai
")->fetch()['total'] ?? 0;


// 3. Total User
$totalPengguna = $db->query("
    SELECT COUNT(*) as total 
    FROM Users
")->fetch()['total'] ?? 0;


// 4. Arsip Terbaru
$recentArsip = $db->query("
    SELECT 
        a.*,
        j.Nama_Jenis_Arsip
    FROM Arsip a
    LEFT JOIN Jenis_Arsip j 
        ON a.ID_Jenis_Arsip = j.ID_Jenis_Arsip
    ORDER BY a.Tanggal_Masuk DESC
    LIMIT 5
")->fetchAll();


// 5. Statistik Arsip Berdasarkan Jenis
$arsipByJenis = $db->query("
    SELECT 
        j.Nama_Jenis_Arsip,
        COUNT(a.ID_Arsip) as total
    FROM Jenis_Arsip j
    LEFT JOIN Arsip a 
        ON j.ID_Jenis_Arsip = a.ID_Jenis_Arsip
    GROUP BY 
        j.ID_Jenis_Arsip,
        j.Nama_Jenis_Arsip
    ORDER BY total DESC
    LIMIT 5
")->fetchAll();


require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';
?>


<div class="page-header">

    <h1 class="page-title">
        Dashboard
    </h1>

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item active">
                Selamat datang, 
                <?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User' ?>!
            </li>

        </ol>

    </nav>

</div>



<div class="row g-4 mb-4">


<div class="col-xl-3 col-md-6">

<div class="stat-card primary">

<i class="fas fa-folder-open stat-icon"></i>

<div class="stat-value">
<?= number_format($statsArsip['total'] ?? 0) ?>
</div>

<div class="stat-label">
Total Arsip
</div>

</div>

</div>



<div class="col-xl-3 col-md-6">

<div class="stat-card success">

<i class="fas fa-check-circle stat-icon"></i>

<div class="stat-value">
<?= number_format($statsArsip['aktif'] ?? 0) ?>
</div>

<div class="stat-label">
Arsip Aktif
</div>

</div>

</div>



<div class="col-xl-3 col-md-6">

<div class="stat-card warning">

<i class="fas fa-users stat-icon"></i>

<div class="stat-value">
<?= number_format($totalPegawai) ?>
</div>

<div class="stat-label">
Pegawai
</div>

</div>

</div>



<div class="col-xl-3 col-md-6">

<div class="stat-card info">

<i class="fas fa-user-friends stat-icon"></i>

<div class="stat-value">
<?= number_format($totalPengguna) ?>
</div>

<div class="stat-label">
Total User System
</div>

</div>

</div>


</div>





<div class="row g-4">


<div class="col-lg-8">


<div class="card">


<div class="card-header d-flex justify-content-between align-items-center">

<span>
<i class="fas fa-clock me-2"></i>
Arsip Terbaru
</span>


<a href="arsip.php" class="btn btn-sm btn-primary">
Lihat Semua
</a>


</div>




<div class="card-body p-0">


<div class="table-responsive">


<table class="table table-hover mb-0">


<thead>

<tr>

<th>No. Arsip</th>

<th>Nama Arsip</th>

<th>Jenis</th>

<th>Status</th>

<th>Tanggal</th>

</tr>

</thead>



<tbody>


<?php if(empty($recentArsip)): ?>


<tr>

<td colspan="5" class="text-center py-4 text-muted">

<i class="fas fa-inbox fa-2x mb-2 d-block"></i>

Belum ada data arsip

</td>

</tr>


<?php else: ?>


<?php foreach($recentArsip as $arsip): ?>


<?php 
$statusBadge = strtolower($arsip['Status_Arsip']);
?>


<tr>


<td>
<strong>
<?= htmlspecialchars($arsip['Nomor_Arsip'] ?? '-') ?>
</strong>
</td>



<td>
<?= htmlspecialchars($arsip['Nama_Arsip'] ?? '-') ?>
</td>



<td>

<span class="badge bg-secondary">

<?= htmlspecialchars($arsip['Nama_Jenis_Arsip'] ?? '-') ?>

</span>

</td>



<td>

<span class="badge status-<?= $statusBadge ?>">

<?= htmlspecialchars($arsip['Status_Arsip']) ?>

</span>

</td>



<td>

<?= isset($arsip['Tanggal_Arsip']) 
? date('d/m/Y', strtotime($arsip['Tanggal_Arsip'])) 
: '-' ?>

</td>



</tr>


<?php endforeach; ?>


<?php endif; ?>


</tbody>


</table>


</div>


</div>


</div>


</div>





<div class="col-lg-4">


<div class="card">


<div class="card-header">

<i class="fas fa-chart-pie me-2"></i>

Arsip per Jenis

</div>




<div class="card-body">


<?php if(empty($arsipByJenis)): ?>


<div class="text-center py-4 text-muted">

Belum ada data

</div>


<?php else: ?>


<ul class="list-group list-group-flush">


<?php foreach($arsipByJenis as $item): ?>


<li class="list-group-item d-flex justify-content-between align-items-center">


<?= htmlspecialchars($item['Nama_Jenis_Arsip']) ?>


<span class="badge bg-primary rounded-pill">

<?= $item['total'] ?>

</span>


</li>


<?php endforeach; ?>


</ul>


<?php endif; ?>


</div>


</div>





<div class="card">


<div class="card-header">

<i class="fas fa-info-circle me-2"></i>

Status Arsip

</div>



<div class="card-body">


<div class="d-flex justify-content-between mb-3">

<span>Aktif</span>

<strong class="text-success">

<?= $statsArsip['aktif'] ?? 0 ?>

</strong>

</div>



<div class="d-flex justify-content-between mb-3">

<span>Inaktif</span>

<strong class="text-warning">

<?= $statsArsip['inaktif'] ?? 0 ?>

</strong>

</div>



<div class="d-flex justify-content-between mb-3">

<span>Terarsipkan</span>

<strong class="text-primary">

<?= $statsArsip['terarsipkan'] ?? 0 ?>

</strong>

</div>



<div class="d-flex justify-content-between">

<span>Musnah</span>

<strong class="text-danger">

<?= $statsArsip['musnah'] ?? 0 ?>

</strong>

</div>


</div>


</div>


</div>


</div>



<?php require_once 'includes/footer.php'; ?>