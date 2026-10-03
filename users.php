<?php

$pageTitle = 'Manajemen User';

require_once 'includes/header.php';


if (!hasAccess('Admin')) {

    header('Location: index.php');

    exit();

}


$db = getDB();




// ==========================
// CREATE UPDATE DELETE
// ==========================


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $action = $_POST['action'] ?? '';



    // CREATE USER

    if ($action === 'create') {


        $password = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );


        $stmt = $db->prepare("

            INSERT INTO Users

            (
                Username,
                Password,
                Nama_Lengkap,
                Email,
                Role,
                Status
            )

            VALUES (?,?,?,?,?,?)

        ");



        $stmt->execute([

            sanitize($_POST['username']),
            $password,
            sanitize($_POST['nama_lengkap']),
            sanitize($_POST['email']),
            $_POST['role'],
            $_POST['status']

        ]);



        alert(
            'User berhasil ditambahkan!',
            'success'
        );


        header('Location: users.php');

        exit();

    }






    // UPDATE USER


    if ($action === 'update') {



        if (!empty($_POST['password'])) {



            $password = password_hash(

                $_POST['password'],

                PASSWORD_DEFAULT

            );



            $stmt = $db->prepare("

                UPDATE Users SET

                    Username=?,
                    Password=?,
                    Nama_Lengkap=?,
                    Email=?,
                    Role=?,
                    Status=?

                WHERE ID_User=?

            ");



            $stmt->execute([

                sanitize($_POST['username']),
                $password,
                sanitize($_POST['nama_lengkap']),
                sanitize($_POST['email']),
                $_POST['role'],
                $_POST['status'],
                $_POST['id_user']

            ]);



        } else {



            $stmt = $db->prepare("

                UPDATE Users SET

                    Username=?,
                    Nama_Lengkap=?,
                    Email=?,
                    Role=?,
                    Status=?

                WHERE ID_User=?

            ");



            $stmt->execute([

                sanitize($_POST['username']),
                sanitize($_POST['nama_lengkap']),
                sanitize($_POST['email']),
                $_POST['role'],
                $_POST['status'],
                $_POST['id_user']

            ]);

        }




        alert(
            'User berhasil diperbarui!',
            'success'
        );


        header('Location: users.php');

        exit();

    }







    // DELETE USER


    if ($action === 'delete') {



        if ($_POST['id_user'] != $_SESSION['user_id']) {



            $stmt = $db->prepare("

                DELETE FROM Users

                WHERE ID_User=?

            ");



            $stmt->execute([

                $_POST['id_user']

            ]);



            alert(
                'User berhasil dihapus!',
                'success'
            );



        } else {



            alert(
                'Tidak dapat menghapus akun sendiri!',
                'error'
            );

        }



        header('Location: users.php');

        exit();

    }



}






// DATA USER


$userList = $db->query("

    SELECT *

    FROM Users

    ORDER BY ID_User DESC

")->fetchAll();





require_once 'includes/sidebar.php';

require_once 'includes/topbar.php';

?>
<div class="page-header d-flex justify-content-between align-items-center">

<div>

<h1 class="page-title">
Manajemen User
</h1>


<nav aria-label="breadcrumb">

<ol class="breadcrumb">

<li class="breadcrumb-item">

<a href="index.php">

Dashboard

</a>

</li>


<li class="breadcrumb-item active">

Users

</li>


</ol>

</nav>


</div>



<button

class="btn btn-primary"

data-bs-toggle="modal"

data-bs-target="#addModal">

<i class="fas fa-plus me-2"></i>

Tambah User

</button>


</div>







<div class="card">


<div class="card-header">

<i class="fas fa-user-shield me-2"></i>

Daftar User

</div>




<div class="card-body">


<div class="table-responsive">


<table class="table data-table table-hover">


<thead>


<tr>

<th>Username</th>

<th>Nama Lengkap</th>

<th>Email</th>

<th>Role</th>

<th>Status</th>

<th>Aksi</th>

</tr>


</thead>




<tbody>


<?php foreach($userList as $user): ?>


<tr>


<td>

<strong>

<?= htmlspecialchars($user['Username']) ?>

</strong>

</td>




<td>

<?= htmlspecialchars($user['Nama_Lengkap']) ?>

</td>




<td>

<?= htmlspecialchars($user['Email'] ?? '-') ?>

</td>




<td>


<span class="badge

<?=

$user['Role']=='Admin'

?

'bg-danger'

:

($user['Role']=='Operator'

?

'bg-warning'

:

'bg-secondary')

?>">


<?= htmlspecialchars($user['Role']) ?>


</span>


</td>




<td>


<span class="badge

<?=

$user['Status']=='Aktif'

?

'bg-success'

:

'bg-secondary'

?>">


<?= htmlspecialchars($user['Status']) ?>


</span>


</td>




<td>


<button

class="btn btn-sm btn-warning"

onclick='editUser(<?= json_encode($user) ?>)'>


<i class="fas fa-edit"></i>


</button>





<?php if($user['ID_User'] != $_SESSION['user_id']): ?>


<form method="POST" style="display:inline">


<input

type="hidden"

name="action"

value="delete">


<input

type="hidden"

name="id_user"

value="<?= $user['ID_User'] ?>">



<button

type="submit"

class="btn btn-sm btn-danger"

onclick="return confirm('Hapus user ini?')">


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

Tambah User

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

Username

</label>


<input

type="text"

name="username"

class="form-control"

required>


</div>




<div class="mb-3">


<label class="form-label">

Password

</label>


<input

type="password"

name="password"

class="form-control"

required>


</div>




<div class="mb-3">


<label class="form-label">

Nama Lengkap

</label>


<input

type="text"

name="nama_lengkap"

class="form-control"

required>


</div>




<div class="mb-3">


<label class="form-label">

Email

</label>


<input

type="email"

name="email"

class="form-control">


</div>




<div class="mb-3">


<label class="form-label">

Role

</label>


<select

name="role"

class="form-select">


<option value="Viewer">

Viewer

</option>


<option value="Operator">

Operator

</option>


<option value="Admin">

Admin

</option>


</select>


</div>




<div class="mb-3">


<label class="form-label">

Status

</label>


<select

name="status"

class="form-select">


<option value="Aktif">

Aktif

</option>


<option value="Nonaktif">

Nonaktif

</option>


</select>


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

name="id_user"

id="edit_id_user">





<div class="modal-header">


<h5 class="modal-title">

Edit User

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

Username

</label>


<input

type="text"

name="username"

id="edit_username"

class="form-control">


</div>




<div class="mb-3">


<label>

Password

</label>


<input

type="password"

name="password"

class="form-control"

placeholder="Kosongkan jika tidak diganti">


</div>




<div class="mb-3">


<label>

Nama Lengkap

</label>


<input

type="text"

name="nama_lengkap"

id="edit_nama"

class="form-control">


</div>




<div class="mb-3">


<label>

Email

</label>


<input

type="email"

name="email"

id="edit_email"

class="form-control">


</div>




<div class="mb-3">


<label>

Role

</label>


<select

name="role"

id="edit_role"

class="form-select">


<option value="Viewer">

Viewer

</option>


<option value="Operator">

Operator

</option>


<option value="Admin">

Admin

</option>


</select>


</div>




<div class="mb-3">


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


<option value="Nonaktif">

Nonaktif

</option>


</select>


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

function editUser(user){


document.getElementById('edit_id_user').value =
user.ID_User;



document.getElementById('edit_username').value =
user.Username || '';



document.getElementById('edit_nama').value =
user.Nama_Lengkap || '';



document.getElementById('edit_email').value =
user.Email || '';



document.getElementById('edit_role').value =
user.Role || 'Viewer';



document.getElementById('edit_status').value =
user.Status || 'Aktif';



new bootstrap.Modal(

document.getElementById('editModal')

).show();


}

</script>





<?php require_once 'includes/footer.php'; ?>