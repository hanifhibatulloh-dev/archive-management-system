<?php

$pageTitle = 'Jenis Arsip';

require_once 'includes/header.php';

$db = getDB();



// ==========================
// PROSES DATA
// ==========================


if($_SERVER['REQUEST_METHOD'] === 'POST'){


    $action = $_POST['action'] ?? '';



    // TAMBAH

    if($action === 'create' && (hasAccess('Operator') || hasAccess('Admin'))){


        $stmt = $db->prepare("

            INSERT INTO Jenis_Arsip

            (
                Nama_Jenis_Arsip,
                Deskripsi
            )

            VALUES (?,?)

        ");



        $stmt->execute([

            sanitize($_POST['nama_jenis_arsip']),

            sanitize($_POST['deskripsi'])

        ]);



        alert(
            'Jenis arsip berhasil ditambahkan!',
            'success'
        );


        header('Location: jenis_arsip.php');

        exit();


    }






    // UPDATE


    if($action === 'update' && (hasAccess('Operator') || hasAccess('Admin'))){


        $stmt = $db->prepare("

            UPDATE Jenis_Arsip SET

                Nama_Jenis_Arsip=?,

                Deskripsi=?

            WHERE ID_Jenis_Arsip=?

        ");



        $stmt->execute([

            sanitize($_POST['nama_jenis_arsip']),

            sanitize($_POST['deskripsi']),

            $_POST['id_jenis_arsip']

        ]);



        alert(
            'Jenis arsip berhasil diperbarui!',
            'success'
        );


        header('Location: jenis_arsip.php');

        exit();


    }







    // DELETE


    if($action === 'delete' && hasAccess('Admin')){


        try{


            $stmt=$db->prepare("

                DELETE FROM Jenis_Arsip

                WHERE ID_Jenis_Arsip=?

            ");



            $stmt->execute([

                $_POST['id_jenis_arsip']

            ]);



            alert(
                'Jenis arsip berhasil dihapus!',
                'success'
            );



        }catch(PDOException $e){



            alert(
                'Jenis arsip masih digunakan oleh data arsip!',
                'danger'
            );


        }



        header('Location: jenis_arsip.php');

        exit();


    }


}





// ==========================
// AMBIL DATA
// ==========================


$dataJenis = $db->query("


SELECT


    j.*,


    (

        SELECT COUNT(*)

        FROM Arsip a

        WHERE a.ID_Jenis_Arsip = j.ID_Jenis_Arsip

    ) AS total_arsip



FROM Jenis_Arsip j



ORDER BY j.Nama_Jenis_Arsip ASC



")->fetchAll(PDO::FETCH_ASSOC);





require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';


?>

<div class="page-header d-flex justify-content-between align-items-center">


<div>

<h1 class="page-title">

Jenis Arsip

</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">


<li class="breadcrumb-item">

<a href="index.php">

Dashboard

</a>

</li>


<li class="breadcrumb-item active">

Jenis Arsip

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

Tambah Jenis Arsip


</button>


<?php endif; ?>


</div>







<div class="card">


<div class="card-header">


<i class="fas fa-list me-2"></i>

Daftar Jenis Arsip


</div>




<div class="card-body">


<div class="table-responsive">


<table class="table table-hover data-table">


<thead>


<tr>


<th>No</th>

<th>Nama Jenis Arsip</th>

<th>Deskripsi</th>

<th>Jumlah Arsip</th>

<th>Aksi</th>


</tr>


</thead>




<tbody>



<?php 

$no = 1;

foreach($dataJenis as $row):

?>



<tr>



<td>

<?= $no++ ?>

</td>





<td>

<strong>

<?= htmlspecialchars($row['Nama_Jenis_Arsip']) ?>

</strong>

</td>






<td>

<?= htmlspecialchars($row['Deskripsi'] ?? '-') ?>

</td>






<td>


<?php if($row['total_arsip'] > 0): ?>


<span class="badge bg-primary">

<?= $row['total_arsip'] ?> Arsip

</span>


<?php else: ?>


<span class="badge bg-secondary">

Kosong

</span>


<?php endif; ?>


</td>






<td>



<?php if(hasAccess('Operator') || hasAccess('Admin')): ?>


<button

class="btn btn-sm btn-warning"

onclick='editJenis(<?= json_encode($row) ?>)'>


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

name="id_jenis_arsip"

value="<?= $row['ID_Jenis_Arsip'] ?>">



<button

type="submit"

class="btn btn-sm btn-danger"

onclick="return confirm('Hapus jenis arsip ini?')">


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


<input

type="hidden"

name="action"

value="create">





<div class="modal-header">


<h5 class="modal-title">

Tambah Jenis Arsip

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

Nama Jenis Arsip

</label>



<input

type="text"

name="nama_jenis_arsip"

class="form-control"

placeholder="Contoh: Keuangan"

required>


</div>






<div class="mb-3">


<label class="form-label">

Deskripsi

</label>


<textarea

name="deskripsi"

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









<!-- MODAL EDIT -->


<div class="modal fade" id="editModal">


<div class="modal-dialog">


<div class="modal-content">



<form method="POST">


<input

type="hidden"

name="action"

value="update">



<input

type="hidden"

name="id_jenis_arsip"

id="edit_id_jenis_arsip">






<div class="modal-header">


<h5 class="modal-title">

Edit Jenis Arsip

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

Nama Jenis Arsip

</label>


<input

type="text"

name="nama_jenis_arsip"

id="edit_nama_jenis_arsip"

class="form-control"

required>


</div>






<div class="mb-3">


<label class="form-label">

Deskripsi

</label>



<textarea

name="deskripsi"

id="edit_deskripsi"

class="form-control"

rows="3"></textarea>


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


function editJenis(data){


document.getElementById('edit_id_jenis_arsip').value =

data.ID_Jenis_Arsip;



document.getElementById('edit_nama_jenis_arsip').value =

data.Nama_Jenis_Arsip;



document.getElementById('edit_deskripsi').value =

data.Deskripsi ?? '';




new bootstrap.Modal(

document.getElementById('editModal')

).show();


}


</script>





<?php require_once 'includes/footer.php'; ?>