<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo "Anda tidak memiliki akses ke halaman ini";
    exit;
}

$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

require 'functions.php';

// Cek apakah tombol submit sudah ditekan
if (isset($_POST["submit_edit_tim"])) {
    $data = [
        "id_tim" => $_POST["edit_id_tim"],
        "nama_tim" => $_POST["edit_nama_tim"],
        "Region" => $_POST["edit_region"],
        "id_peserta" => $_POST["edit_id_peserta"]
    ];

    // cek apakah data berhasil diedit atau tidak
    if (editTim($data) > 0) {
        // Jika berhasil
        echo "
           <script> 
            alert('Data berhasil diubah!');
            document.location.href = 'DashboardTim.php';
           </script>
        ";
    } else {
        // Jika gagal
        echo "
        <script>
            alert('Data gagal diubah!');
            document.location.href = 'DashboardTim.php';
        </script>";
    }
}

// Ambil data tim berdasarkan id yang dikirimkan melalui URL
$id_tim = $_GET["id"];
$query_tim = "SELECT * FROM tim WHERE id_tim = $id_tim";
$result_tim = mysqli_query($conn, $query_tim);
$row_tim = mysqli_fetch_assoc($result_tim);
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
                    <a href="DashboardTim.php" onclick="return confirm('Are you sure you want to go back?')"><i class="fas fa-times"></i></a>
                </div>
                <div class="row">
                    <div class="cell-10 offset-1">
                        <div class="title">
                            <p>Form Edit Team</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="field">
                                <!-- Hidden field to store tim ID -->
                                <input type="hidden" name="edit_id_tim" value="<?= $row_tim["id_tim"]; ?>">
                                <!-- Team Name -->
                                <label for="edit_nama_tim">Team Name</label>
                                <input type="text" data-role="input" name="edit_nama_tim" id="edit_nama_tim" value="<?= $row_tim["nama_tim"]; ?>" required>
                            </div>
                            <div class="field">
                                <!-- Region -->
                                <label for="edit_region">Region</label>
                                <input type="text" data-role="input" name="edit_region" id="edit_region" value="<?= $row_tim["Region"]; ?>" required>
                            </div>
                            <div class="field">
                                <!-- Participant -->
                                <label for="edit_id_peserta">Participant</label>
                                <select name="edit_id_peserta" id="edit_id_peserta" required>
                                    <option value="">Select Participant</option>
                                    <?php
                                    $query_peserta = "SELECT id_peserta, nickName FROM peserta";
                                    $result_peserta = mysqli_query($conn, $query_peserta);

                                    if (!$result_peserta) {
                                        die("Query failed: " . mysqli_error($conn));
                                    }

                                    if (mysqli_num_rows($result_peserta)
                                    > 0) {
                                        while ($row_peserta = mysqli_fetch_assoc($result_peserta)) {
                                            // Jika id peserta pada baris saat ini sama dengan id_peserta pada data tim, beri atribut selected
                                            $selected = ($row_peserta["id_peserta"] == $row_tim["id_peserta"]) ? "selected" : "";
                                            echo "<option value='" . $row_peserta['id_peserta'] . "' $selected>" . $row_peserta['nickName'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No participants available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" name="submit_edit_tim" class="button success outline w-100">
                                Edit Team
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
