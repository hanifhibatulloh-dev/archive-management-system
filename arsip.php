<?php

$pageTitle = 'Manajemen Arsip';

require_once 'includes/header.php';

$db = getDB();



// ==========================
// TAMBAH / EDIT / DELETE
// ==========================


if($_SERVER['REQUEST_METHOD'] === 'POST'){


    $action = $_POST['action'] ?? '';



    // ======================
    // TAMBAH ARSIP
    // ======================

    if($action === 'create' && (hasAccess('Operator') || hasAccess('Admin'))){


        $stmt = $db->prepare("

            INSERT INTO Arsip

            (

                Nomor_Arsip,

                Nama_Arsip,

                Deskripsi_Arsip,

                Tanggal_Arsip,

                Tanggal_Masuk,

                Status_Arsip,

                ID_Jenis_Arsip,

                ID_Klasifikasi_Arsip,

                ID_Penyimpanan,

                ID_Pegawai,

                Tags,

                Keterangan

            )


            VALUES

            (?,?,?,?,?,?,?,?,?,?,?,?)

        ");



        $stmt->execute([


            sanitize($_POST['nomor_arsip']),

            sanitize($_POST['nama_arsip']),

            sanitize($_POST['deskripsi_arsip']),

            $_POST['tanggal_arsip'],

            $_POST['tanggal_masuk'],

            $_POST['status_arsip'],

            $_POST['id_jenis_arsip'],

            $_POST['id_klasifikasi_arsip'],

            $_POST['id_penyimpanan'],

            $_POST['id_pegawai'],

            sanitize($_POST['tags']),

            sanitize($_POST['keterangan'])


        ]);



        alert(
            'Arsip berhasil ditambahkan!',
            'success'
        );


        header('Location: arsip.php');

        exit();

    }







    // ======================
    // UPDATE ARSIP
    // ======================


    if($action === 'update' && (hasAccess('Operator') || hasAccess('Admin'))){



        $stmt = $db->prepare("


            UPDATE Arsip SET


                Nomor_Arsip=?,

                Nama_Arsip=?,

                Deskripsi_Arsip=?,

                Tanggal_Arsip=?,

                Tanggal_Masuk=?,

                Status_Arsip=?,

                ID_Jenis_Arsip=?,

                ID_Klasifikasi_Arsip=?,

                ID_Penyimpanan=?,

                ID_Pegawai=?,

                Tags=?,

                Keterangan=?


            WHERE ID_Arsip=?


        ");




        $stmt->execute([


            sanitize($_POST['nomor_arsip']),

            sanitize($_POST['nama_arsip']),

            sanitize($_POST['deskripsi_arsip']),

            $_POST['tanggal_arsip'],

            $_POST['tanggal_masuk'],

            $_POST['status_arsip'],

            $_POST['id_jenis_arsip'],

            $_POST['id_klasifikasi_arsip'],

            $_POST['id_penyimpanan'],

            $_POST['id_pegawai'],

            sanitize($_POST['tags']),

            sanitize($_POST['keterangan']),

            $_POST['id_arsip']


        ]);



        alert(
            'Arsip berhasil diperbarui!',
            'success'
        );



        header('Location: arsip.php');

        exit();


    }






    // ======================
    // DELETE ARSIP
    // ======================


    if($action === 'delete' && hasAccess('Admin')){


        $stmt=$db->prepare("


            DELETE FROM Arsip

            WHERE ID_Arsip=?


        ");



        $stmt->execute([

            $_POST['id_arsip']

        ]);



        alert(
            'Arsip berhasil dihapus!',
            'success'
        );



        header('Location: arsip.php');

        exit();


    }


}





// ==========================
// AMBIL DATA MASTER
// ==========================


$jenisArsip = $db->query("

SELECT *

FROM Jenis_Arsip

ORDER BY Nama_Jenis_Arsip

")->fetchAll(PDO::FETCH_ASSOC);





$klasifikasi = $db->query("

SELECT *

FROM Klasifikasi_Arsip

ORDER BY Nama_Klasifikasi

")->fetchAll(PDO::FETCH_ASSOC);





$penyimpanan = $db->query("

SELECT *

FROM Penyimpanan

ORDER BY Lokasi_Penyimpanan

")->fetchAll(PDO::FETCH_ASSOC);





$pegawai = $db->query("

SELECT *

FROM Pegawai

ORDER BY Nama_Pegawai

")->fetchAll(PDO::FETCH_ASSOC);





// ==========================
// DATA ARSIP
// ==========================


$arsipList = $db->query("


SELECT


a.*,


j.Nama_Jenis_Arsip,


k.Nama_Klasifikasi,


p.Lokasi_Penyimpanan,


pg.Nama_Pegawai



FROM Arsip a




LEFT JOIN Jenis_Arsip j

ON a.ID_Jenis_Arsip = j.ID_Jenis_Arsip




LEFT JOIN Klasifikasi_Arsip k

ON a.ID_Klasifikasi_Arsip = k.ID_Klasifikasi_Arsip




LEFT JOIN Penyimpanan p

ON a.ID_Penyimpanan = p.ID_Penyimpanan




LEFT JOIN Pegawai pg

ON a.ID_Pegawai = pg.ID_Pegawai




ORDER BY a.ID_Arsip DESC



")->fetchAll(PDO::FETCH_ASSOC);





require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';


?>
<!-- HEADER HALAMAN -->

<div class="page-header d-flex justify-content-between align-items-center">


<div>

<h1 class="page-title">

Manajemen Arsip

</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">


<li class="breadcrumb-item">

<a href="index.php">

Dashboard

</a>

</li>


<li class="breadcrumb-item active">

Arsip

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

Tambah Arsip


</button>


<?php endif; ?>


</div>







<!-- TABEL ARSIP -->


<div class="card">


<div class="card-header">


<i class="fas fa-folder-open me-2"></i>

Daftar Arsip


</div>





<div class="card-body">


<div class="table-responsive">


<table class="table table-hover table-striped data-table">


<thead>


<tr>


<th>No Arsip</th>

<th>Nama Arsip</th>

<th>Jenis</th>

<th>Klasifikasi</th>

<th>Penyimpanan</th>

<th>Pegawai</th>

<th>Status</th>

<th>Aksi</th>


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


<?= htmlspecialchars($arsip['Nama_Arsip'] ?? '-') ?>


<br>


<small class="text-muted">


<?= htmlspecialchars($arsip['Tanggal_Masuk'] ?? '-') ?>


</small>


</td>






<td>


<span class="badge bg-info">


<?= htmlspecialchars($arsip['Nama_Jenis_Arsip'] ?? '-') ?>


</span>


</td>







<td>


<?= htmlspecialchars($arsip['Nama_Klasifikasi'] ?? '-') ?>


</td>







<td>


<?= htmlspecialchars($arsip['Lokasi_Penyimpanan'] ?? '-') ?>


</td>







<td>


<?= htmlspecialchars($arsip['Nama_Pegawai'] ?? '-') ?>


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







<td>


<?php if(hasAccess('Operator') || hasAccess('Admin')): ?>


<button

class="btn btn-sm btn-warning"

onclick='editArsip(<?= json_encode($arsip) ?>)'>


<i class="fas fa-edit"></i>


</button>


<?php endif; ?>







<?php if(hasAccess('Admin')): ?>


<form method="POST" style="display:inline;">


<input

type="hidden"

name="action"

value="delete">


<input

type="hidden"

name="id_arsip"

value="<?= $arsip['ID_Arsip'] ?>">



<button

type="submit"

class="btn btn-sm btn-danger"

onclick="return confirm('Hapus arsip ini?')">


<i class="fas fa-trash"></i>


</button>


</form>


<?php endif; ?>



</td>






</tr>


<?php endforeach; ?>




<?php if(empty($arsipList)): ?>


<tr>

<td colspan="8" class="text-center">

Belum ada data arsip

</td>

</tr>


<?php endif; ?>



</tbody>


</table>


</div>


</div>


</div>









<!-- MODAL TAMBAH ARSIP -->


<div class="modal fade" id="addModal">


<div class="modal-dialog modal-lg">


<div class="modal-content">



<form method="POST">


<input

type="hidden"

name="action"

value="create">



<div class="modal-header">


<h5 class="modal-title">

Tambah Arsip

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

Nomor Arsip

</label>


<input

type="text"

name="nomor_arsip"

class="form-control"

required>


</div>






<div class="col-md-6">


<label class="form-label">

Nama Arsip

</label>


<input

type="text"

name="nama_arsip"

class="form-control"

required>


</div>







<div class="col-md-6">


<label class="form-label">

Jenis Arsip

</label>


<select

name="id_jenis_arsip"

class="form-select"

required>


<option value="">Pilih Jenis</option>


<?php foreach($jenisArsip as $j): ?>


<option value="<?= $j['ID_Jenis_Arsip'] ?>">


<?= htmlspecialchars($j['Nama_Jenis_Arsip']) ?>


</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-6">


<label class="form-label">

Klasifikasi

</label>


<select

name="id_klasifikasi_arsip"

class="form-select">


<option value="">Pilih Klasifikasi</option>


<?php foreach($klasifikasi as $k): ?>


<option value="<?= $k['ID_Klasifikasi_Arsip'] ?>">


<?= htmlspecialchars($k['Nama_Klasifikasi']) ?>


</option>


<?php endforeach; ?>


</select>


</div>






<div class="col-md-6">


<label class="form-label">

Penyimpanan

</label>


<select

name="id_penyimpanan"

class="form-select">


<option value="">Pilih Lokasi</option>


<?php foreach($penyimpanan as $p): ?>


<option value="<?= $p['ID_Penyimpanan'] ?>">


<?= htmlspecialchars($p['Lokasi_Penyimpanan']) ?>


</option>


<?php endforeach; ?>


</select>


</div>






<div class="col-md-6">


<label class="form-label">

Pegawai

</label>


<select

name="id_pegawai"

class="form-select">


<option value="">Pilih Pegawai</option>


<?php foreach($pegawai as $pg): ?>


<option value="<?= $pg['ID_Pegawai'] ?>">


<?= htmlspecialchars($pg['Nama_Pegawai']) ?>


</option>


<?php endforeach; ?>


</select>


</div>






<div class="col-md-6">


<label class="form-label">

Tanggal Arsip

</label>


<input

type="date"

name="tanggal_arsip"

class="form-control">


</div>






<div class="col-md-6">


<label class="form-label">

Tanggal Masuk

</label>


<input

type="date"

name="tanggal_masuk"

class="form-control"

required>


</div>






<div class="col-md-6">


<label class="form-label">

Status

</label>


<select

name="status_arsip"

class="form-select">


<option value="Aktif">Aktif</option>

<option value="Inaktif">Inaktif</option>

<option value="Statis">Statis</option>

<option value="Musnah">Musnah</option>


</select>


</div>


<div class="col-md-6">


<label class="form-label">

Tags

</label>


<input

type="text"

name="tags"

class="form-control">


</div>






<div class="col-12">


<label class="form-label">

Deskripsi Arsip

</label>


<textarea

name="deskripsi_arsip"

class="form-control"

rows="3"></textarea>


</div>






<div class="col-12">


<label class="form-label">

Keterangan

</label>


<textarea

name="keterangan"

class="form-control"

rows="3"></textarea>


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










<!-- MODAL EDIT ARSIP -->


<div class="modal fade" id="editModal">


<div class="modal-dialog modal-lg">


<div class="modal-content">



<form method="POST">


<input

type="hidden"

name="action"

value="update">



<input

type="hidden"

name="id_arsip"

id="edit_id_arsip">






<div class="modal-header">


<h5 class="modal-title">

Edit Arsip

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

Nomor Arsip

</label>


<input

type="text"

name="nomor_arsip"

id="edit_nomor_arsip"

class="form-control">


</div>






<div class="col-md-6">


<label class="form-label">

Nama Arsip

</label>


<input

type="text"

name="nama_arsip"

id="edit_nama_arsip"

class="form-control">


</div>







<div class="col-md-6">


<label>

Jenis Arsip

</label>


<select

name="id_jenis_arsip"

id="edit_jenis"

class="form-select">


<?php foreach($jenisArsip as $j): ?>


<option value="<?= $j['ID_Jenis_Arsip'] ?>">


<?= htmlspecialchars($j['Nama_Jenis_Arsip']) ?>


</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-6">


<label>

Klasifikasi

</label>


<select

name="id_klasifikasi_arsip"

id="edit_klasifikasi"

class="form-select">


<?php foreach($klasifikasi as $k): ?>


<option value="<?= $k['ID_Klasifikasi_Arsip'] ?>">


<?= htmlspecialchars($k['Nama_Klasifikasi']) ?>


</option>


<?php endforeach; ?>


</select>


</div>








<div class="col-md-6">


<label>

Penyimpanan

</label>


<select

name="id_penyimpanan"

id="edit_penyimpanan"

class="form-select">


<?php foreach($penyimpanan as $p): ?>


<option value="<?= $p['ID_Penyimpanan'] ?>">


<?= htmlspecialchars($p['Lokasi_Penyimpanan']) ?>


</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-6">


<label>

Pegawai

</label>


<select

name="id_pegawai"

id="edit_pegawai"

class="form-select">


<?php foreach($pegawai as $pg): ?>


<option value="<?= $pg['ID_Pegawai'] ?>">


<?= htmlspecialchars($pg['Nama_Pegawai']) ?>


</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-6">


<label>

Tanggal Arsip

</label>


<input

type="date"

name="tanggal_arsip"

id="edit_tanggal_arsip"

class="form-control">


</div>






<div class="col-md-6">


<label>

Tanggal Masuk

</label>


<input

type="date"

name="tanggal_masuk"

id="edit_tanggal_masuk"

class="form-control">


</div>







<div class="col-md-6">


<label>

Status

</label>


<select

name="status_arsip"

id="edit_status"

class="form-select">


<option value="Aktif">Aktif</option>

<option value="Inaktif">Inaktif</option>

<option value="Statis">Statis</option>

<option value="Musnah">Musnah</option>


</select>


</div>






<div class="col-md-6">


<label>

Tags

</label>


<input

type="text"

name="tags"

id="edit_tags"

class="form-control">


</div>






<div class="col-12">


<label>

Deskripsi

</label>


<textarea

name="deskripsi_arsip"

id="edit_deskripsi"

class="form-control"></textarea>


</div>






<div class="col-12">


<label>

Keterangan

</label>


<textarea

name="keterangan"

id="edit_keterangan"

class="form-control"></textarea>


</div>



</div>


</div>







<div class="modal-footer">


<button

type="submit"

class="btn btn-warning">


Update


</button>


</div>



</form>


</div>


</div>


</div>







<script>


function editArsip(data){



document.getElementById('edit_id_arsip').value =
data.ID_Arsip;



document.getElementById('edit_nomor_arsip').value =
data.Nomor_Arsip ?? '';



document.getElementById('edit_nama_arsip').value =
data.Nama_Arsip ?? '';



document.getElementById('edit_jenis').value =
data.ID_Jenis_Arsip ?? '';



document.getElementById('edit_klasifikasi').value =
data.ID_Klasifikasi_Arsip ?? '';



document.getElementById('edit_penyimpanan').value =
data.ID_Penyimpanan ?? '';



document.getElementById('edit_pegawai').value =
data.ID_Pegawai ?? '';



document.getElementById('edit_tanggal_arsip').value =
data.Tanggal_Arsip ?? '';



document.getElementById('edit_tanggal_masuk').value =
data.Tanggal_Masuk ?? '';



document.getElementById('edit_status').value =
data.Status_Arsip ?? 'Aktif';



document.getElementById('edit_tags').value =
data.Tags ?? '';



document.getElementById('edit_deskripsi').value =
data.Deskripsi_Arsip ?? '';



document.getElementById('edit_keterangan').value =
data.Keterangan ?? '';




new bootstrap.Modal(

document.getElementById('editModal')

).show();



}



</script>





<?php require_once 'includes/footer.php'; ?>
