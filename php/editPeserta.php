<?php
session_start();

if (!isset($_SESSION["submit"])) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");
require 'functions.php';

// Ambil ID peserta dari URL
$id = $_GET['id'];

// Ambil data peserta berdasarkan ID
$peserta = getPesertaById($id, $conn);

// Ambil data role untuk dropdown
$roles = getAllRoles($conn);

// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit"])) {
    $data = [
        "id_peserta" => $id,
        "id_inGame" => $_POST["id_inGame"],
        "nickName" => $_POST["nickName"],
        "id_role" => $_POST["id_role"]
    ];

    // cek apakah data berhasil diubah atau tidak
    if (editPeserta($data) > 0) {
        echo "
           <script> 
            alert('Data berhasil diubah!');
            document.location.href = 'DashboardPeserta.php';
           </script>
        ";
    } else {
        echo "
        <script>
            alert('Data gagal diubah!');
            document.location.href = 'DashboardPeserta.php';
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
    <title>Edit Participant</title>
    <link rel="stylesheet" href="../assets/css/tambahdata.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4.3.2/css/metro-all.min.css">
    <link rel="icon" href="../assets/img/logo-color.png">
</head>
<body style="background-color: #FCBC94;">
    <section class="add-product">
        <div class="container">
            <div class="grid">
                <div class="btn-cancel">
                    <a href="DashboardPeserta.php" onclick="return confirm('Are you sure you want to go back?')"><i class="fas fa-times"></i></a>
                </div>
                <div class="row">
                    <div class="cell-10 offset-1">
                        <div class="title">
                            <p>Edit Participant Form</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <label for="id_inGame">ID InGame</label>
                                <input type="text" data-role="input" name="id_inGame" id="id_inGame" value="<?= $peserta['id_inGame'] ?>" required>
                            </div>
                            <div class="field">
                                <label for="nickName">Nickname</label>
                                <input type="text" data-role="input" name="nickName" id="nickName" value="<?= $peserta['nickName'] ?>" required>
                            </div>
                            <div class="field">
                                <label for="id_role">Role:</label>
                                <select name="id_role" id="id_role" required>
                                    <?php foreach ($roles as $role) : ?>
                                        <option value="<?= $role['id_role']; ?>" <?= ($role['id_role'] == $peserta['id_role']) ? 'selected' : ''; ?>><?= $role['nama_role']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="button success outline w-100">
                                Edit Participant
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://kit.fontawesome.com/6dd84d01cb.js" crossorigin="anonymous"></script>
    <script src="https://cdn.metroui.org.ua/v4.3.2/js/metro.min.js"></script>
</body>
</html>
