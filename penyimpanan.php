<?php

$pageTitle = 'Lokasi Penyimpanan';

require_once 'includes/header.php';

$db = getDB();


// ==========================
// CREATE / UPDATE / DELETE
// ==========================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';



    // TAMBAH

    if ($action === 'create' && hasAccess('Operator')) {


        $stmt = $db->prepare("

            INSERT INTO Penyimpanan

            (
                Kode_Lokasi,
                Lokasi_Penyimpanan,
                Kapasitas_Penyimpanan,
                Jenis_Penyimpanan
            )

            VALUES (?,?,?,?)

        ");



        $stmt->execute([

            sanitize($_POST['kode_lokasi']),
            sanitize($_POST['lokasi_penyimpanan']),
            intval($_POST['kapasitas_penyimpanan']),
            sanitize($_POST['jenis_penyimpanan'])

        ]);



        alert('Lokasi penyimpanan berhasil ditambahkan!', 'success');

        header('Location: penyimpanan.php');
        exit();

    }




    // UPDATE

    if ($action === 'update' && hasAccess('Operator')) {


        $stmt = $db->prepare("

            UPDATE Penyimpanan SET

                Kode_Lokasi=?,
                Lokasi_Penyimpanan=?,
                Kapasitas_Penyimpanan=?,
                Jenis_Penyimpanan=?

            WHERE ID_Penyimpanan=?

        ");



        $stmt->execute([

            sanitize($_POST['kode_lokasi']),
            sanitize($_POST['lokasi_penyimpanan']),
            intval($_POST['kapasitas_penyimpanan']),
            sanitize($_POST['jenis_penyimpanan']),
            $_POST['id_penyimpanan']

        ]);



        alert('Lokasi penyimpanan berhasil diperbarui!', 'success');

        header('Location: penyimpanan.php');
        exit();

    }




    // DELETE

    if ($action === 'delete' && hasAccess('Admin')) {


        $stmt = $db->prepare("

            DELETE FROM Penyimpanan

            WHERE ID_Penyimpanan=?

        ");



        $stmt->execute([

            $_POST['id_penyimpanan']

        ]);



        alert('Lokasi penyimpanan berhasil dihapus!', 'success');

        header('Location: penyimpanan.php');
        exit();

    }

}




// ==========================
// DATA PENYIMPANAN
// ==========================


$penyimpananList = $db->query("

    SELECT

        p.*,

        COUNT(a.ID_Arsip) AS total_arsip


    FROM Penyimpanan p


    LEFT JOIN Arsip a

        ON a.ID_Penyimpanan = p.ID_Penyimpanan


    GROUP BY

        p.ID_Penyimpanan,
        p.Kode_Lokasi,
        p.Lokasi_Penyimpanan,
        p.Kapasitas_Penyimpanan,
        p.Jenis_Penyimpanan


    ORDER BY p.ID_Penyimpanan ASC


")->fetchAll();




require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';

?>
<div class="page-header d-flex justify-content-between align-items-center">

<div>

<h1 class="page-title">
Lokasi Penyimpanan
</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">

<li class="breadcrumb-item">
<a href="index.php">
Dashboard
</a>
</li>

<li class="breadcrumb-item active">
Penyimpanan
</li>

</ol>

</nav>

</div>



<?php if(hasAccess('Operator')): ?>

<button 
class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#addModal">

<i class="fas fa-plus me-2"></i>
Tambah Lokasi

</button>

<?php endif; ?>


</div>





<div class="card">


<div class="card-header">

<i class="fas fa-warehouse me-2"></i>

Daftar Lokasi Penyimpanan

</div>




<div class="card-body">


<div class="table-responsive">


<table class="table data-table">


<thead>

<tr>

<th>Kode</th>

<th>Lokasi</th>

<th>Jenis</th>

<th>Kapasitas</th>

<th>Jumlah Arsip</th>

<th>Aksi</th>

</tr>

</thead>



<tbody>


<?php foreach($penyimpananList as $py): ?>


<tr>


<td>

<span class="badge bg-dark">

<?= htmlspecialchars($py['Kode_Lokasi'] ?? '-') ?>

</span>

</td>




<td>

<strong>

<?= htmlspecialchars($py['Lokasi_Penyimpanan']) ?>

</strong>

</td>




<td>


<span class="badge bg-info">


<?= htmlspecialchars($py['Jenis_Penyimpanan'] ?? '-') ?>


</span>


</td>




<td>

<?= number_format($py['Kapasitas_Penyimpanan'] ?? 0) ?>

</td>




<td>

<span class="badge bg-primary">

<?= $py['total_arsip'] ?>

</span>

</td>




<td>


<?php if(hasAccess('Operator')): ?>


<button

class="btn btn-sm btn-warning"

onclick='editPenyimpanan(<?= json_encode($py) ?>)'>

<i class="fas fa-edit"></i>

</button>


<?php endif; ?>




<?php if(hasAccess('Admin')): ?>


<form method="POST" style="display:inline;">


<input type="hidden" name="action" value="delete">


<input type="hidden" 
name="id_penyimpanan"
value="<?= $py['ID_Penyimpanan'] ?>">



<button

type="submit"

class="btn btn-sm btn-danger"

onclick="return confirm('Hapus lokasi penyimpanan ini?')">

<i class="fas fa-trash"></i>

</button>


</form>


<?php endif; ?>


</td>



</tr>


<?php endforeach; ?>



</tbody>


</table>


</div>


</div>


</div>







<!-- MODAL TAMBAH -->


<div class="modal fade" id="addModal">


<div class="modal-dialog">


<div class="modal-content">


<form method="POST">


<input type="hidden" name="action" value="create">


<div class="modal-header">

<h5 class="modal-title">

Tambah Lokasi Penyimpanan

</h5>


<button 
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>





<div class="modal-body">



<div class="mb-3">

<label class="form-label">
Kode Lokasi
</label>


<input

type="text"

name="kode_lokasi"

class="form-control"

placeholder="Contoh: RAK-001">

</div>





<div class="mb-3">

<label class="form-label">
Lokasi Penyimpanan *
</label>


<input

type="text"

name="lokasi_penyimpanan"

class="form-control"

required>

</div>





<div class="mb-3">

<label class="form-label">
Jenis Penyimpanan
</label>


<select

name="jenis_penyimpanan"

class="form-select">


<option value="Fisik">
Fisik
</option>


<option value="Digital">
Digital
</option>


<option value="Server">
Server
</option>


</select>

</div>





<div class="mb-3">

<label class="form-label">
Kapasitas Penyimpanan
</label>


<input

type="number"

name="kapasitas_penyimpanan"

class="form-control"

value="0">

</div>


</div>





<div class="modal-footer">


<button

type="submit"

class="btn btn-primary">

<i class="fas fa-save me-2"></i>

Simpan

</button>


</div>



</form>


</div>


</div>


</div>







<!-- MODAL EDIT -->


<div class="modal fade" id="editModal">


<div class="modal-dialog">


<div class="modal-content">


<form method="POST">


<input type="hidden" name="action" value="update">



<input

type="hidden"

name="id_penyimpanan"

id="edit_id_penyimpanan">





<div class="modal-header">


<h5 class="modal-title">

Edit Lokasi Penyimpanan

</h5>


<button 
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>


</div>





<div class="modal-body">



<div class="mb-3">

<label>
Kode Lokasi
</label>


<input

type="text"

name="kode_lokasi"

id="edit_kode_lokasi"

class="form-control">

</div>





<div class="mb-3">

<label>
Lokasi Penyimpanan
</label>


<input

type="text"

name="lokasi_penyimpanan"

id="edit_lokasi_penyimpanan"

class="form-control">

</div>





<div class="mb-3">

<label>
Jenis Penyimpanan
</label>


<select

name="jenis_penyimpanan"

id="edit_jenis_penyimpanan"

class="form-select">


<option value="Fisik">
Fisik
</option>


<option value="Digital">
Digital
</option>


<option value="Server">
Server
</option>


</select>


</div>





<div class="mb-3">

<label>
Kapasitas
</label>


<input

type="number"

name="kapasitas_penyimpanan"

id="edit_kapasitas"

class="form-control">

</div>


</div>





<div class="modal-footer">


<button

type="submit"

class="btn btn-primary">

Simpan Perubahan

</button>


</div>



</form>


</div>


</div>


</div>







<script>

function editPenyimpanan(data){


document.getElementById('edit_id_penyimpanan').value =
data.ID_Penyimpanan;


document.getElementById('edit_kode_lokasi').value =
data.Kode_Lokasi ?? '';



document.getElementById('edit_lokasi_penyimpanan').value =
data.Lokasi_Penyimpanan ?? '';



document.getElementById('edit_jenis_penyimpanan').value =
data.Jenis_Penyimpanan ?? 'Fisik';



document.getElementById('edit_kapasitas').value =
data.Kapasitas_Penyimpanan ?? 0;



new bootstrap.Modal(

document.getElementById('editModal')

).show();


}

</script>



<?php require_once 'includes/footer.php'; ?>