<?php
session_start();

require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {

        $error = 'Username dan password harus diisi!';

    } else {

        try {

            $db = getDB();

            $sql = "SELECT *
                    FROM Users
                    WHERE Username = ?
                    AND Status = 'Aktif'";

            $stmt = $db->prepare($sql);
            $stmt->execute([$username]);

            $user = $stmt->fetch();


            if ($user && password_verify($password, $user['Password'])) {


                $_SESSION['user_id'] = $user['ID_User'];
                $_SESSION['user_name'] = $user['Nama_Lengkap'];
                $_SESSION['user_role'] = $user['Role'];
                $_SESSION['username'] = $user['Username'];


                header('Location: index.php');
                exit();


            } else {

                $error = 'Username atau password salah!';

            }


        } catch (PDOException $e) {

            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();

        }

    }

}


if (isset($_SESSION['user_id'])) {

    header('Location: index.php');
    exit();

}

?>


<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Archive Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">

    <style>
    /* Perbesar keseluruhan kotak login */
    body.login-page {
    min-height: 100vh;

    display: flex;
    justify-content: center;
    align-items: center;

    background:
        linear-gradient(
            rgba(10, 63, 82, 0.78),
            rgba(16, 92, 116, 0.82)
        ),
        url('assets/images/background_login.jpg');

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;

    font-family: "Segoe UI", Arial, sans-serif;
}

    .login-box {
        width: 100%;
        max-width: 540px;
        min-height: 610px;
        margin: 30px auto;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 20px 55px rgba(0, 0, 0, 0.18);
    }

    /* Perbesar bagian header */
    .login-header {
        padding: 48px 50px 38px;
        text-align: center;
    }

    /* Perbesar logo */
    .login-header .logo {
        width: 92px;
        height: 92px;
        margin: 0 auto 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .login-header .logo i {
        font-size: 42px;
    }

    /* Judul lebih proporsional */
    .login-header h2 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .login-header p {
        font-size: 15px;
        margin-bottom: 0;
    }

    /* Area form lebih luas */
    .login-body {
        padding: 44px 50px 50px;
    }

    /* Jarak antar field */
    .login-body .mb-4 {
        margin-bottom: 26px !important;
    }

    /* Input lebih besar */
    .login-body .form-control {
        min-height: 54px;
        padding: 12px 16px;
        font-size: 15px;
        border-radius: 10px;
    }

    /* Label */
    .login-body .form-label {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 9px;
    }

    /* Tombol lihat password */
    .login-body .input-group .btn {
        min-width: 55px;
    }

    /* Tombol login lebih besar */
    .btn-login {
        width: 100%;
        min-height: 54px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 10px;
    }

    /* Tetap bagus di HP */
    @media (max-width: 600px) {
        .login-box {
            max-width: calc(100% - 30px);
            min-height: auto;
        }

        .login-header {
            padding: 38px 25px 30px;
        }

        .login-body {
            padding: 32px 25px 38px;
        }

        .login-header h2 {
            font-size: 22px;
        }
    }
    </style>
</head>


<body class="login-page">


<div class="login-box">


    <div class="login-header">

        <div class="logo">
            <i class="fas fa-archive"></i>
        </div>


        <h2>ARCHIVE MANAGEMENT SYSTEM</h2>

        <p>Software Engineering Academic Project</p>

    </div>



    <div class="login-body">


        <?php if ($error): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            <?= $error ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

        </div>

        <?php endif; ?>



        <form method="POST" action="">


            <div class="mb-4">

                <label class="form-label">

                    <i class="fas fa-user me-2"></i>
                    Username

                </label>


                <input 
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                    autofocus
                >

            </div>



            <div class="mb-4">


                <label class="form-label">

                    <i class="fas fa-lock me-2"></i>
                    Password

                </label>



                <div class="input-group">


                    <input 
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        required
                    >


                    <button 
                        type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword()"
                    >

                        <i class="fas fa-eye" id="toggleIcon"></i>

                    </button>


                </div>


            </div>



            <button type="submit" class="btn btn-primary btn-login">

                <i class="fas fa-sign-in-alt me-2"></i>

                Masuk

            </button>


        </form>


    </div>


</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script>

function togglePassword() {

    const passwordInput = document.getElementById('password');

    const toggleIcon = document.getElementById('toggleIcon');


    if (passwordInput.type === 'password') {

        passwordInput.type = 'text';

        toggleIcon.classList.remove('fa-eye');

        toggleIcon.classList.add('fa-eye-slash');


    } else {


        passwordInput.type = 'password';

        toggleIcon.classList.remove('fa-eye-slash');

        toggleIcon.classList.add('fa-eye');

    }

}

</script>


</body>

</html>

