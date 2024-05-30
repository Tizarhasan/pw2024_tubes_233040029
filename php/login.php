<?php 
session_start();
require 'functions.php';

// Cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // Ambil username berdasarkan id
    $result = mysqli_query($conn, "SELECT id_user, username, role FROM user WHERE id_user = $id");
    $row = mysqli_fetch_assoc($result);

    // Cek cookie dan username
    if ($key === hash('sha256', $row['username'])) {
        $_SESSION['submit'] = true;
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['id_user'];
    }
}

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    // Cek username
    if (mysqli_num_rows($result) === 1) {
        // Cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            // Set session
            $_SESSION["submit"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $row['role'];
            $_SESSION["user_id"] = $row['id_user'];

            // Set cookie untuk kalimat selamat datang di halaman admin
            setcookie("username", $username, time() + 3600);

            // Cek remember me
            if (isset($_POST['remember'])) {
                // Buat cookie
                setcookie('id', $row['id_user'], time() + 60 * 60 * 24 * 30); // Cookie berlaku 30 hari
                setcookie('key', hash('sha256', $row['username']), time() + 60 * 60 * 24 * 30);
            }

            // Redirect ke halaman sesuai peran
            if ($row['role'] === 'admin') {
                header("Location: DashboardTim.php");
                exit;
            } else if ($row['role'] === 'user') {
                header("Location: ../index.php");
                exit;
            } else {
                echo "Anda tidak memiliki akses.";
            }
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="background-color: #19191D">
    <section class="container">
        <div class="row content d-flex justify-content-center tengah">
            <div class="col-md-5">
                <div class="turun box shadow bg-white p-4 borius">
                    <div class="btn-cancel kanan21" style="margin-left: auto !important;
                        font-size: 28px !important;
                        margin-right: 10px !important;">
                        <a href="../index.php" onclick="return confirm('Apakah anda yakin ingin kembali?')"><i class="fas fa-times"></i></a>
                    </div>
                    <h3 class="mb-4 text-center fs-1">Login</h3>
                    <form class="mb-3" action="" method="post">
                        <?php if (isset($error)) : ?>
                            <p style="color: red; font-style:italic;">Username atau Password salah</p>
                        <?php endif; ?>
                        <div class="form-floating mb-3">
                            <input class="form-control rounded-0 px-5" type="text" name="username" id="username" placeholder="password" autocomplete="off" required autofocus>
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control rounded-0 px-5" type="password" name="password" id="password" placeholder="password" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="true" name="remember">
                            <label for="" class="form-check-label">Ingat Saya</label>
                        </div>
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" name="submit" class="btn btn-dark btn-lg border-0 rounded-0">Login</button>
                        </div>
                        <div class="Registrasi mb-3 mt-2">
                            <p>Belum memiliki akun? <a href="registrasi.php" class="text-decoration-none">Daftar</a> </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Script  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/6dd84d01cb.js" crossorigin="anonymous"></script>
</body>

</html>
