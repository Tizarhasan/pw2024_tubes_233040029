<?php
session_start();

// Redirect ke halaman login jika pengguna belum login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Include file functions.php
require 'functions.php';

$turnamens = ambilSemuaTurnamen();

// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    // Panggil fungsi tambahTurnamenTim
    if (tambahTurnamenTimUser($_POST) > 0) {
        echo "<script> 
            alert('Data turnamen tim berhasil ditambahkan!');
            document.location.href = 'turnamenku.php';
        </script>";
    } else {
        echo "<script>
            alert('Data turnamen tim gagal ditambahkan!');
            document.location.href = 'turnamenku.php';
        </script>";
    }
}

// Ambil id_tim dari tabel user sesuai dengan pengguna yang sedang masuk
$id_user = $_SESSION['user_id'];
$query_user_tim = "SELECT id_tim FROM user WHERE id_user = $id_user";
$result_user_tim = mysqli_query($conn, $query_user_tim);
$row_user_tim = mysqli_fetch_assoc($result_user_tim);
$user_tim_id = $row_user_tim['id_tim'];

// Ambil nama tim dari tabel tim sesuai dengan id_tim yang dimiliki oleh pengguna yang sedang masuk
$query_tim = "SELECT id_tim, nama_tim FROM tim WHERE id_tim = $user_tim_id";
$result_tim = mysqli_query($conn, $query_tim);
$row_tim = mysqli_fetch_assoc($result_tim); // Ambil data tim

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tournament Team</title>
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
                            <p>Add Tournament Team Form</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <!-- ID Turnamen -->
                                <label for="id_turnamen">Turnamen</label>
                                <select name="id_turnamen" id="id_turnamen" required>
                                    <?php foreach ($turnamens as $turnamen) : ?>
                                        <option value="<?= $turnamen['id_turnamen']; ?>"><?= $turnamen['nama_turnamen']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <!-- ID Tim -->
                                <label for="id_tim">Tim yang Berpartisipasi</label>
                                <select name="id_tim" id="id_tim" required>
                                    <option value="<?= $row_tim['id_tim']; ?>" selected><?= $row_tim['nama_tim']; ?></option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="button success outline w-100">
                                Add Tournament Team
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
    <!-- Custom JavaScript -->
    <script>
        // Inisialisasi Metro UI select
        var select = $("#id_tim").select().data("select");
    </script>
</body>

</html>
