<?php

$pageTitle = 'Laporan Arsip';

require_once 'includes/header.php';

$db = getDB();


$filter_status = $_GET['status'] ?? '';
$filter_jenis  = $_GET['jenis'] ?? '';
$filter_from   = $_GET['from'] ?? '';
$filter_to     = $_GET['to'] ?? '';



// ==========================
// QUERY LAPORAN ARSIP
// ==========================

$sql = "

SELECT

    a.*,

    j.Nama_Jenis_Arsip,

    k.Nama_Klasifikasi,

    p.Lokasi_Penyimpanan


FROM Arsip a


LEFT JOIN Jenis_Arsip j

ON a.ID_Jenis_Arsip = j.ID_Jenis_Arsip



LEFT JOIN Klasifikasi_Arsip k

ON a.ID_Klasifikasi_Arsip = k.ID_Klasifikasi_Arsip



LEFT JOIN Penyimpanan p

ON a.ID_Penyimpanan = p.ID_Penyimpanan



WHERE 1=1

";



$params = [];



if($filter_status != ''){

    $sql .= "

    AND a.Status_Arsip = ?

    ";

    $params[] = $filter_status;

}



if($filter_jenis != ''){

    $sql .= "

    AND a.ID_Jenis_Arsip = ?

    ";

    $params[] = $filter_jenis;

}



if($filter_from != ''){

    $sql .= "

    AND a.Tanggal_Masuk >= ?

    ";

    $params[] = $filter_from;

}



if($filter_to != ''){

    $sql .= "

    AND a.Tanggal_Masuk <= ?

    ";

    $params[] = $filter_to;

}



$sql .= "

ORDER BY a.ID_Arsip DESC

";



$stmt = $db->prepare($sql);

$stmt->execute($params);


$arsipList = $stmt->fetchAll(PDO::FETCH_ASSOC);




// ==========================
// DATA JENIS ARSIP
// ==========================

$jenisArsip = $db->query("

SELECT *

FROM Jenis_Arsip

ORDER BY Nama_Jenis_Arsip

")->fetchAll(PDO::FETCH_ASSOC);





require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';

?>

<div class="page-header d-flex justify-content-between align-items-center">

<div>

<h1 class="page-title">
Laporan Arsip
</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">

<li class="breadcrumb-item">

<a href="index.php">

Dashboard

</a>

</li>


<li class="breadcrumb-item active">

Laporan

</li>


</ol>

</nav>


</div>



<button

class="btn btn-success"

onclick="window.print()">

<i class="fas fa-print me-2"></i>

Cetak Laporan

</button>


</div>







<div class="card mb-4 no-print">


<div class="card-header">

<i class="fas fa-filter me-2"></i>

Filter Laporan

</div>




<div class="card-body">


<form method="GET" class="row g-3">



<div class="col-md-3">


<label class="form-label">

Status Arsip

</label>


<select name="status" class="form-select">


<option value="">

Semua Status

</option>


<option value="Aktif"
<?= $filter_status=='Aktif'?'selected':'' ?>>

Aktif

</option>


<option value="Inaktif"
<?= $filter_status=='Inaktif'?'selected':'' ?>>

Inaktif

</option>


<option value="Statis"
<?= $filter_status=='Statis'?'selected':'' ?>>

Statis

</option>


<option value="Musnah"
<?= $filter_status=='Musnah'?'selected':'' ?>>

Musnah

</option>


</select>


</div>






<div class="col-md-3">


<label class="form-label">

Jenis Arsip

</label>



<select name="jenis" class="form-select">


<option value="">

Semua Jenis

</option>



<?php foreach($jenisArsip as $j): ?>


<option

value="<?= $j['ID_Jenis_Arsip'] ?>"

<?=

$filter_jenis == $j['ID_Jenis_Arsip']

?

'selected'

:

''

?>

>


<?= htmlspecialchars($j['Nama_Jenis_Arsip']) ?>


</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-2">


<label class="form-label">

Dari Tanggal

</label>


<input

type="date"

name="from"

class="form-control"

value="<?= htmlspecialchars($filter_from) ?>">


</div>






<div class="col-md-2">


<label class="form-label">

Sampai Tanggal

</label>


<input

type="date"

name="to"

class="form-control"

value="<?= htmlspecialchars($filter_to) ?>">


</div>






<div class="col-md-2 d-flex align-items-end gap-2">


<button

type="submit"

class="btn btn-primary w-100">

<i class="fas fa-search"></i>

</button>



<a

href="laporan.php"

class="btn btn-secondary">

<i class="fas fa-undo"></i>

</a>


</div>


</form>


</div>


</div>







<div class="card">


<div class="card-body">


<div class="table-responsive">


<table class="table table-bordered table-striped align-middle">


<thead class="table-light">


<tr>

<th>No Arsip</th>

<th>Judul Arsip</th>

<th>Jenis Arsip</th>

<th>Klasifikasi</th>

<th>Lokasi Penyimpanan</th>

<th>Status</th>

</tr>


</thead>



<tbody>



<?php foreach($arsipList as $arsip): ?>


<tr>


<td>

<strong>

<?= htmlspecialchars($arsip['Nomor_Arsip'] ?? '-') ?>

</strong>

</td>




<td>

<?= htmlspecialchars($arsip['Judul_Arsip'] ?? '-') ?>

</td>




<td>

<?= htmlspecialchars($arsip['Nama_Jenis_Arsip'] ?? '-') ?>

</td>




<td>

<?= htmlspecialchars($arsip['Nama_Klasifikasi'] ?? '-') ?>

</td>




<td>

<?= htmlspecialchars($arsip['Lokasi_Penyimpanan'] ?? '-') ?>

</td>




<td>


<?php

$status = $arsip['Status_Arsip'] ?? '-';


$badge = match($status){

    'Aktif' => 'success',

    'Inaktif' => 'warning',

    'Statis' => 'primary',

    'Musnah' => 'dark',

    default => 'secondary'

};

?>


<span class="badge bg-<?= $badge ?>">

<?= htmlspecialchars($status) ?>

</span>


</td>



</tr>


<?php endforeach; ?>





<?php if(empty($arsipList)): ?>


<tr>

<td colspan="6" class="text-center">

Belum ada data arsip

</td>

</tr>


<?php endif; ?>



</tbody>


</table>


</div>


</div>


</div>






<style>

@media print {


.no-print,
.sidebar,
.topbar,
.btn {

display:none!important;

}


.content-wrapper {

margin:0!important;

padding:0!important;

}



.table {

width:100%!important;

border-collapse:collapse!important;

}



.table th,
.table td {

border:1px solid #000!important;

font-size:9pt;

}


}

</style>





<?php require_once 'includes/footer.php'; ?>