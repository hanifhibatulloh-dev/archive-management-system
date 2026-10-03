<?php

$pageTitle = 'Manajemen Pegawai';

require_once 'includes/header.php';

$db = getDB();


// ==========================
// DELETE
// ==========================

if(isset($_GET['hapus_id'])){

    if(hasAccess('Admin')){

        try{

            $stmt = $db->prepare("
                DELETE FROM Pegawai
                WHERE ID_Pegawai = ?
            ");

            $stmt->execute([
                $_GET['hapus_id']
            ]);


            echo "
            <script>
            alert('Data pegawai berhasil dihapus!');
            window.location='pegawai.php';
            </script>";

            exit();


        }catch(PDOException $e){

            echo "
            <script>
            alert('Data tidak dapat dihapus karena masih digunakan.');
            window.location='pegawai.php';
            </script>";

            exit();

        }

    }

}



// ==========================
// CREATE / UPDATE
// ==========================


if($_SERVER['REQUEST_METHOD']==='POST'){


    $action = $_POST['action'] ?? '';



    // TAMBAH

    if($action==='create' && (hasAccess('Operator') || hasAccess('Admin'))){


        $stmt = $db->prepare("

            INSERT INTO Pegawai

            (
                Nama_Pegawai,
                NIP,
                Jabatan,
                Tugas,
                Tanggal_Masuk,
                Email,
                Status
            )

            VALUES (?,?,?,?,?,?,?)

        ");



        $stmt->execute([

            $_POST['nama_pegawai'],
            $_POST['nip'],
            $_POST['jabatan'],
            $_POST['tugas'],
            $_POST['tanggal_masuk'],
            $_POST['email'],
            $_POST['status']

        ]);



        echo "
        <script>
        alert('Pegawai berhasil ditambahkan!');
        window.location='pegawai.php';
        </script>";

        exit();

    }






    // UPDATE


    if($action==='update' && (hasAccess('Operator') || hasAccess('Admin'))){


        $stmt=$db->prepare("

            UPDATE Pegawai SET

                Nama_Pegawai=?,
                NIP=?,
                Jabatan=?,
                Tugas=?,
                Tanggal_Masuk=?,
                Email=?,
                Status=?

            WHERE ID_Pegawai=?

        ");



        $stmt->execute([

            $_POST['nama_pegawai'],
            $_POST['nip'],
            $_POST['jabatan'],
            $_POST['tugas'],
            $_POST['tanggal_masuk'],
            $_POST['email'],
            $_POST['status'],
            $_POST['id_pegawai']

        ]);



        echo "
        <script>
        alert('Data pegawai berhasil diperbarui!');
        window.location='pegawai.php';
        </script>";

        exit();

    }

}




// ==========================
// DATA PEGAWAI
// ==========================


$pegawaiList=$db->query("

    SELECT *

    FROM Pegawai

    ORDER BY Nama_Pegawai ASC

")->fetchAll(PDO::FETCH_ASSOC);



require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';

?>
<div class="page-header d-flex justify-content-between align-items-center">

<div>

<h1 class="page-title">
Manajemen Pegawai
</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">

<li class="breadcrumb-item">

<a href="index.php">

Dashboard

</a>

</li>


<li class="breadcrumb-item active">

Pegawai

</li>


</ol>

</nav>

</div>



<?php if(hasAccess('Operator') || hasAccess('Admin')): ?>


<button

class="btn btn-primary"

data-bs-toggle="modal"

data-bs-target="#addModal">

<i class="fas fa-plus me-2"></i>

Tambah Pegawai

</button>


<?php endif; ?>


</div>






<div class="card mt-3">


<div class="card-header">

<i class="fas fa-users me-2"></i>

Daftar Pegawai

</div>




<div class="card-body">


<div class="table-responsive">


<table class="table data-table table-striped table-hover">


<thead>

<tr>

<th>NIP</th>

<th>Nama Pegawai</th>

<th>Jabatan</th>

<th>Tugas</th>

<th>Email</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>



<tbody>


<?php foreach($pegawaiList as $p): ?>


<tr>


<td>

<?= htmlspecialchars($p['NIP'] ?? '-') ?>

</td>



<td>

<strong>

<?= htmlspecialchars($p['Nama_Pegawai'] ?? '-') ?>

</strong>

</td>



<td>

<?= htmlspecialchars($p['Jabatan'] ?? '-') ?>

</td>



<td>

<?= htmlspecialchars($p['Tugas'] ?? '-') ?>

</td>



<td>

<?= htmlspecialchars($p['Email'] ?? '-') ?>

</td>



<td>


<span class="badge bg-info">

<?= htmlspecialchars($p['Status'] ?? '-') ?>

</span>


</td>



<td>


<?php if(hasAccess('Operator') || hasAccess('Admin')): ?>


<button

type="button"

class="btn btn-sm btn-warning"

onclick='editPegawai(<?= json_encode($p) ?>)'>

<i class="fas fa-edit"></i>

</button>


<?php endif; ?>




<?php if(hasAccess('Admin')): ?>


<a

href="pegawai.php?hapus_id=<?= $p['ID_Pegawai'] ?>"

class="btn btn-sm btn-danger"

onclick="return confirm('Yakin hapus pegawai ini?')">


<i class="fas fa-trash"></i>


</a>


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


<div class="modal-dialog modal-lg">


<div class="modal-content">


<form method="POST">


<input type="hidden" name="action" value="create">



<div class="modal-header">

<h5 class="modal-title">

Tambah Pegawai

</h5>


<button

type="button"

class="btn-close"

data-bs-dismiss="modal">

</button>


</div>




<div class="modal-body">


<div class="row g-3">



<div class="col-md-6">

<label class="form-label">

Nama Pegawai

</label>


<input

type="text"

name="nama_pegawai"

class="form-control"

required>


</div>




<div class="col-md-6">

<label class="form-label">

NIP

</label>


<input

type="text"

name="nip"

class="form-control">


</div>




<div class="col-md-6">

<label class="form-label">

Jabatan

</label>


<input

type="text"

name="jabatan"

class="form-control">


</div>




<div class="col-md-6">

<label class="form-label">

Tugas

</label>


<input

type="text"

name="tugas"

class="form-control">


</div>




<div class="col-md-6">

<label class="form-label">

Tanggal Masuk

</label>


<input

type="date"

name="tanggal_masuk"

class="form-control">


</div>




<div class="col-md-6">

<label class="form-label">

Email

</label>


<input

type="email"

name="email"

class="form-control">


</div>




<div class="col-md-6">

<label class="form-label">

Status

</label>


<select

name="status"

class="form-select">


<option value="Aktif">

Aktif

</option>


<option value="Tidak Aktif">

Tidak Aktif

</option>


</select>


</div>



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


<div class="modal-dialog modal-lg">


<div class="modal-content">


<form method="POST">


<input type="hidden" name="action" value="update">


<input

type="hidden"

name="id_pegawai"

id="edit_id">





<div class="modal-header">

<h5 class="modal-title">

Edit Pegawai

</h5>


<button

type="button"

class="btn-close"

data-bs-dismiss="modal">

</button>


</div>





<div class="modal-body">


<div class="row g-3">


<div class="col-md-6">

<label>

Nama Pegawai

</label>


<input

type="text"

name="nama_pegawai"

id="edit_nama"

class="form-control">


</div>




<div class="col-md-6">

<label>

NIP

</label>


<input

type="text"

name="nip"

id="edit_nip"

class="form-control">


</div>




<div class="col-md-6">

<label>

Jabatan

</label>


<input

type="text"

name="jabatan"

id="edit_jabatan"

class="form-control">


</div>




<div class="col-md-6">

<label>

Tugas

</label>


<input

type="text"

name="tugas"

id="edit_tugas"

class="form-control">


</div>




<div class="col-md-6">

<label>

Tanggal Masuk

</label>


<input

type="date"

name="tanggal_masuk"

id="edit_tanggal"

class="form-control">


</div>




<div class="col-md-6">

<label>

Email

</label>


<input

type="email"

name="email"

id="edit_email"

class="form-control">


</div>




<div class="col-md-6">

<label>

Status

</label>


<select

name="status"

id="edit_status"

class="form-select">


<option value="Aktif">

Aktif

</option>


<option value="Tidak Aktif">

Tidak Aktif

</option>


</select>


</div>



</div>


</div>





<div class="modal-footer">


<button

type="submit"

class="btn btn-primary">

Update

</button>


</div>


</form>


</div>


</div>


</div>







<script>

function editPegawai(data){


document.getElementById('edit_id').value =
data.ID_Pegawai;


document.getElementById('edit_nama').value =
data.Nama_Pegawai ?? '';



document.getElementById('edit_nip').value =
data.NIP ?? '';



document.getElementById('edit_jabatan').value =
data.Jabatan ?? '';



document.getElementById('edit_tugas').value =
data.Tugas ?? '';



document.getElementById('edit_tanggal').value =
data.Tanggal_Masuk ?? '';



document.getElementById('edit_email').value =
data.Email ?? '';



document.getElementById('edit_status').value =
data.Status ?? 'Aktif';



new bootstrap.Modal(

document.getElementById('editModal')

).show();


}

</script>




<?php require_once 'includes/footer.php'; ?>