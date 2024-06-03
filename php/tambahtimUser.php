<?php
session_start();

if (!isset($_SESSION["submit"])) {
    header("Location: login.php");
    exit; 
}

$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

require 'functions.php';

// Assuming $id_user is retrieved from the session or somewhere else in your code
$id_user = $_SESSION['user_id']; // Adjust this according to how you retrieve the user ID

// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit_tambah_tim"])) {
    $data = [
        "nama_tim" => $_POST["nama_tim"],
        "Region" => $_POST["Region"],
        "logo" => $_FILES["logo"]["name"], // Mengambil nama file logo dari $_FILES
        "id_peserta" => !empty($_POST["id_peserta"]) ? $_POST["id_peserta"] : null 
    ];

    // cek apakah data berhasil ditambahkan atau tidak
    if (tambahTimUser($data, $id_user) > 0) {
        // Jika berhasil
        echo "
           <script> 
            alert('Data berhasil ditambahkan!');
            document.location.href = 'turnamenku.php';
           </script>
        ";
    } else {
        // Jika gagal
        echo "
        <script>
            alert('Data gagal ditambahkan!');
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
    <title>Add Team</title>
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
                            <p>Form Daftar Tim</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <!-- Team Name -->
                                <label for="nama_tim">Team Name</label>
                                <input type="text" data-role="input" name="nama_tim" id="nama_tim" required>
                            </div>
                            <div class="field">
                                <!-- Region -->
                                <label for="Region">Region</label>
                                <input type="text" data-role="input" name="Region" id="Region" required>
                            </div>
                            <div class="field">
                                <!-- Logo -->
                                <label for="logo">Logo</label>
                                <input type="file" name="logo" id="logo" class="gambar" onchange="previewImage()" required>
                                <img src="../assets/img/nophoto.png" style="display:block; align-items: center;" class="img-preview w-50">
                            </div>
                            <!-- <div class="field">
                                Participant
                                <label for="id_peserta">Kapten Tim</label>
                                <select name="id_peserta" id="id_peserta" required>
                                    <option value="">Select Participant</option>
                                    <option value="" selected>Tidak Memilih</option>
                                    <?php
                                    $query_peserta = "SELECT id_peserta, nickName FROM peserta";
                                    $result_peserta = mysqli_query($conn, $query_peserta);

                                    if (!$result_peserta) {
                                        die("Query failed: " . mysqli_error($conn));
                                    }

                                    if (mysqli_num_rows($result_peserta) > 0) {
                                        while ($row_peserta = mysqli_fetch_assoc($result_peserta)) {
                                            echo "<option value='" . $row_peserta['id_peserta'] . "'>" . $row_peserta['nickName'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No participants available</option>";
                                    }
                                    ?>
                                </select>
                            </div> -->
                            <button type="submit" name="submit_tambah_tim" class="button success outline w-100">
                                Add Team
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
    <script>
        function previewImage() {
            const gambar = document.querySelector(".gambar");
            const imgPreview = document.querySelector(".img-preview");

            const oFReader = new FileReader();
            oFReader.readAsDataURL(gambar.files[0]);

            oFReader.onload = function (oFREvent) {
                imgPreview.src = oFREvent.target.result;
            };
        }
    </script>
</body>

</html>