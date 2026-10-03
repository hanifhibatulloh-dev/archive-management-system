<?php
$pageTitle = 'Klasifikasi Arsip';

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
            INSERT INTO Klasifikasi_Arsip
            (
                Kode_Klasifikasi,
                Nama_Klasifikasi,
                Deskripsi_Klasifikasi
            )
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            sanitize($_POST['kode_klasifikasi']),
            sanitize($_POST['nama_klasifikasi']),
            sanitize($_POST['deskripsi_klasifikasi'])
        ]);

        alert('Klasifikasi berhasil ditambahkan!', 'success');

        header('Location: klasifikasi.php');
        exit();
    }


    // UPDATE
    if ($action === 'update' && hasAccess('Operator')) {

        $stmt = $db->prepare("
            UPDATE Klasifikasi_Arsip SET
                Kode_Klasifikasi = ?,
                Nama_Klasifikasi = ?,
                Deskripsi_Klasifikasi = ?
            WHERE ID_Klasifikasi_Arsip = ?
        ");

        $stmt->execute([
            sanitize($_POST['kode_klasifikasi']),
            sanitize($_POST['nama_klasifikasi']),
            sanitize($_POST['deskripsi_klasifikasi']),
            $_POST['id_klasifikasi_arsip']
        ]);


        alert('Klasifikasi berhasil diperbarui!', 'success');

        header('Location: klasifikasi.php');
        exit();

    }



    // DELETE
    if ($action === 'delete' && hasAccess('Admin')) {


        $stmt = $db->prepare("
            DELETE FROM Klasifikasi_Arsip
            WHERE ID_Klasifikasi_Arsip = ?
        ");


        $stmt->execute([
            $_POST['id_klasifikasi_arsip']
        ]);


        alert('Klasifikasi berhasil dihapus!', 'success');

        header('Location: klasifikasi.php');
        exit();

    }

}




// ==========================
// AMBIL DATA
// ==========================

$klasifikasiList = $db->query("

    SELECT 
        k.*,
        COUNT(a.ID_Arsip) AS total_arsip

    FROM Klasifikasi_Arsip k

    LEFT JOIN Arsip a
        ON a.ID_Klasifikasi_Arsip = k.ID_Klasifikasi_Arsip

    GROUP BY
        k.ID_Klasifikasi_Arsip,
        k.Kode_Klasifikasi,
        k.Nama_Klasifikasi,
        k.Deskripsi_Klasifikasi

    ORDER BY k.Kode_Klasifikasi

")->fetchAll();



require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';

?>



<div class="page-header d-flex justify-content-between align-items-center">

<div>

<h1 class="page-title">
Klasifikasi Arsip
</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">

<li class="breadcrumb-item">
<a href="index.php">
Dashboard
</a>
</li>


<li class="breadcrumb-item active">
Klasifikasi
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
Tambah Klasifikasi

</button>

<?php endif; ?>


</div>





<div class="card">


<div class="card-header">

<i class="fas fa-sitemap me-2"></i>

Daftar Klasifikasi

</div>



<div class="card-body">


<div class="table-responsive">


<table class="table data-table">


<thead>

<tr>

<th>Kode</th>

<th>Nama Klasifikasi</th>

<th>Deskripsi</th>

<th>Jumlah Arsip</th>

<th>Aksi</th>

</tr>

</thead>



<tbody>



<?php foreach($klasifikasiList as $klas): ?>


<tr>


<td>

<span class="badge bg-info">

<?= htmlspecialchars($klas['Kode_Klasifikasi'] ?? '-') ?>

</span>

</td>



<td>

<strong>

<?= htmlspecialchars($klas['Nama_Klasifikasi']) ?>

</strong>

</td>



<td>

<?= htmlspecialchars($klas['Deskripsi_Klasifikasi'] ?? '-') ?>

</td>



<td>

<span class="badge bg-primary">

<?= $klas['total_arsip'] ?>

</span>

</td>



<td>


<?php if(hasAccess('Operator')): ?>


<button

class="btn btn-sm btn-warning"

onclick='editKlasifikasi(<?= json_encode($klas) ?>)'>

<i class="fas fa-edit"></i>

</button>


<?php endif; ?>



<?php if(hasAccess('Admin')): ?>


<form method="POST" style="display:inline">


<input type="hidden" name="action" value="delete">


<input type="hidden" 
name="id_klasifikasi_arsip"
value="<?= $klas['ID_Klasifikasi_Arsip'] ?>">



<button

type="submit"

class="btn btn-sm btn-danger"

onclick="return confirm('Hapus klasifikasi ini?')">

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






<!-- TAMBAH -->


<div class="modal fade" id="addModal">


<div class="modal-dialog">


<div class="modal-content">


<form method="POST">


<input type="hidden" name="action" value="create">


<div class="modal-header">


<h5 class="modal-title">

Tambah Klasifikasi

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
Kode Klasifikasi
</label>


<input

type="text"

name="kode_klasifikasi"

class="form-control"

placeholder="Contoh: 000">

</div>




<div class="mb-3">

<label class="form-label">
Nama Klasifikasi *
</label>


<input

type="text"

name="nama_klasifikasi"

class="form-control"

required>

</div>




<div class="mb-3">

<label class="form-label">
Deskripsi
</label>


<textarea

name="deskripsi_klasifikasi"

class="form-control"

rows="3"></textarea>

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







<!-- EDIT -->


<div class="modal fade" id="editModal">


<div class="modal-dialog">


<div class="modal-content">


<form method="POST">


<input type="hidden" name="action" value="update">


<input 
type="hidden"
name="id_klasifikasi_arsip"
id="edit_id">



<div class="modal-header">


<h5 class="modal-title">

Edit Klasifikasi

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
Kode Klasifikasi
</label>


<input

type="text"

name="kode_klasifikasi"

id="edit_kode"

class="form-control">

</div>



<div class="mb-3">

<label>
Nama Klasifikasi
</label>


<input

type="text"

name="nama_klasifikasi"

id="edit_nama"

class="form-control"

required>

</div>



<div class="mb-3">

<label>
Deskripsi
</label>


<textarea

name="deskripsi_klasifikasi"

id="edit_deskripsi"

class="form-control"></textarea>


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

function editKlasifikasi(data){

    document.getElementById('edit_id').value =
        data.ID_Klasifikasi_Arsip;

    document.getElementById('edit_kode').value =
        data.Kode_Klasifikasi ?? '';

    document.getElementById('edit_nama').value =
        data.Nama_Klasifikasi ?? '';

    document.getElementById('edit_deskripsi').value =
        data.Deskripsi_Klasifikasi ?? '';


    new bootstrap.Modal(
        document.getElementById('editModal')
    ).show();

}

</script>



<?php require_once 'includes/footer.php'; ?>