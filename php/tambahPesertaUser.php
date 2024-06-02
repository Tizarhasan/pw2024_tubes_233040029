<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit; 
}

$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");
require 'functions.php';

// Ambil id_tim dari tabel user sesuai dengan pengguna yang sedang masuk
$id_user = $_SESSION['user_id'];
$query_user_tim = "SELECT id_tim FROM user WHERE id_user = $id_user";
$result_user_tim = mysqli_query($conn, $query_user_tim);
$row_user_tim = mysqli_fetch_assoc($result_user_tim);
$user_tim_id = $row_user_tim['id_tim'];

// Ambil nama tim dari tabel tim sesuai dengan id_tim yang dimiliki oleh pengguna yang sedang masuk
$query_tim = "SELECT id_tim, nama_tim FROM tim WHERE id_tim = $user_tim_id";
$result_tim = mysqli_query($conn, $query_tim);

// Ambil opsi tim yang diizinkan untuk pengguna yang sedang login
$row_tim = mysqli_fetch_assoc($result_tim);

// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    // Panggil fungsi tambahPeserta
    if (tambahPeserta($_POST) > 0) {
        echo "<script> 
            alert('Data peserta berhasil ditambahkan!');
            document.location.href = 'turnamenku.php';
        </script>";
    } else {
        echo "<script>
            alert('Data peserta gagal ditambahkan!');
            document.location.href = 'turnamenku.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Participant</title>
    <!-- MyCSS -->
    <link rel="stylesheet" href="../assets/css/tambahdata.css">
    <!-- Metro 4 -->
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4.3.2/css/metro-all.min.css">
    <!-- Icon -->
    <link rel="icon" href="../assets/img/logo-color.png">
</head>

<body style="background-color: #FCBC94;">
    <section class="add-product">
        <div class="container">
            <div class="grid">
                <div class="btn-cancel">
                    <a href="turnamenku.php" onclick="return confirm('Are you sure you want to go back?')"><i class="fas fa-times"></i></a>
                </div>
                <div class="row">
                    <div class="cell-10 offset-1">
                        <div class="title">
                            <p>Form Add Participant</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <!-- In-Game ID -->
                                <label for="id_inGame">In-Game ID</label>
                                <input type="text" data-role="input" name="id_inGame" id="id_inGame" required>
                            </div>
                            <div class="field">
                                <!-- Nickname -->
                                <label for="nickName">Nickname</label>
                                <input type="text" data-role="input" name="nickName" id="nickName" required>
                            </div>
                            <div class="field">
                                <!-- Role -->
                                <label for="id_role">Role</label>
                                <select name="id_role" id="id_role" required>
                                    <?php
                                    // Ambil data role dari database
                                    $query_role = "SELECT id_role, nama_role FROM roleingame";
                                    $result_role = mysqli_query($conn, $query_role);
                                    while ($row_role = mysqli_fetch_assoc($result_role)) {
                                        echo "<option value='" . $row_role['id_role'] . "'>" . $row_role['nama_role'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="field">
                                <!-- Team -->
                                <label for="id_tim">Team</label>
                                <select name="id_tim" id="id_tim" required>
                                    <option value="<?= $row_tim['id_tim']; ?>" selected><?= $row_tim['nama_tim']; ?></option>
                                </select>
                            </div>
                            <div class="field">
                                <!-- Email -->
                                <label for="email">Email</label>
                                <input type="email" data-role="input" name="email" id="email">
                            </div>
                            <button type="submit" name="submit" class="button success outline w-100">
                                Add Participant
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/6dd84d01cb.js" crossorigin="anonymous"></script>
    <!-- Metro - 4 -->
    <script src="https://cdn.metroui.org.ua/v4.3.2/js/metro.min.js"></script>
</body>

</html>
