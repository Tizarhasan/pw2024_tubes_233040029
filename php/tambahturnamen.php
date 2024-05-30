<?php
session_start();

// Redirect ke halaman login jika pengguna belum login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Include file functions.php
require 'functions.php';

// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    // Panggil fungsi tambahTurnamen
    if (tambahTurnamen($_POST) > 0) {
        echo "<script> 
            alert('Data turnamen berhasil ditambahkan!');
            document.location.href = 'DashboardTurnamen.php';
        </script>";
    } else {
        echo "<script>
            alert('Data turnamen gagal ditambahkan!');
            document.location.href = 'DashboardTurnamen.php';
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
    <title>Add Tournament</title>
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
                    <a href="DashboardTurnamen.php" onclick="return confirm('Are you sure you want to go back?')"><i class="fas fa-times"></i></a>
                </div>
                <div class="row">
                    <div class="cell-10 offset-1">
                        <div class="title">
                            <p>Add Tournament Form</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <!-- Nama Turnamen -->
                                <label for="nama_turnamen">Nama Turnamen</label>
                                <input type="text" data-role="input" name="nama_turnamen" id="nama_turnamen" required>
                            </div>
                            <!-- Tanggal Mulai -->
                            <div class="field">
    <label for="tanggal_mulai">Tanggal Mulai</label>
    <input type="date" name="tanggal_mulai" id="tanggal_mulai" required>
</div>


                            <button type="submit" name="submit" class="button success outline w-100">
                                Add Tournament
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