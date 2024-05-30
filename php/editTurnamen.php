<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

require 'functions.php';

// Ambil ID turnamen_tim dari URL
$id = $_GET['id'];

// Cek apakah ID valid dan ada di database
if (!isset($id) || empty($id) || !is_numeric($id)) {
    echo "<script>alert('ID tidak valid!'); window.location.href = 'DashboardTurnamen.php';</script>";
    exit;
}

// Dapatkan data turnamen berdasarkan ID
$turnamen_tim = getTurnamenTimById($id, $conn);
if (!$turnamen_tim) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href = 'DashboardTurnamen.php';</script>";
    exit;
}

$turnamens = getAllTournaments($conn);
$teams = ambilSemuaTim();

// Jika tombol "Submit" ditekan, proses formulir
if (isset($_POST["submit"])) {
    $data = [
        "id_turnamen_tim" => $id,
        "id_turnamen" => $_POST["id_turnamen"],
        "id_tim" => $_POST["id_tim"]
    ];

    // Update turnamen tim
    if (updateTurnamenTim($data)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'DashboardTurnamen.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team</title>
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
                            <p>Form Edit Tournament Tim</p>
                        </div>
                        <form action="" method="post">
                            <div class="field">
                                <label for="id_turnamen">Nama Turnamen:</label>
                                <select name="id_turnamen" id="id_turnamen" required>
                                    <?php foreach ($turnamens as $turnamen) : ?>
                                        <option value="<?= $turnamen['id_turnamen']; ?>" <?= ($turnamen_tim['id_turnamen'] == $turnamen['id_turnamen']) ? 'selected' : ''; ?>>
                                            <?= $turnamen['nama_turnamen']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="field">
                                <label for="id_tim">Pilih Tim:</label>
                                <select name="id_tim" id="id_tim" required>
                                    <?php foreach ($teams as $team) : ?>
                                        <option value="<?= $team['id_tim']; ?>" <?= ($team['id_tim'] == $turnamen_tim['id_tim']) ? 'selected' : ''; ?>>
                                            <?= $team['nama_tim']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="button success outline w-100">Simpan</button>
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
