<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Anda tidak memiliki akses ke halaman ini";
    exit;
}

require 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = tambahKonten($_POST);
    if ($result === -1) {
        echo "<script>
        alert('Gagal mengunggah gambar. Pastikan gambar yang diunggah berformat jpg, jpeg, png, atau webp dan ukurannya tidak lebih dari 5MB.') 
        document.location.href = 'DashboardKonten.php';
        </script>";
    } elseif ($result > 0) {
        echo "<script>alert('Konten berhasil ditambahkan.') 
        document.location.href = 'DashboardKonten.php';</script> ";
    } else {
        echo " <script>alert('Gagal menambahkan konten.') 
        document.location.href = 'DashboardKonten.php';
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Content</title>
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
                    <a href="DashboardKonten.php" onclick="return confirm('Are you sure you want to go back?')"><i class="fas fa-times"></i></a>
                </div>
                <div class="row">
                    <div class="cell-10 offset-1">
                        <div class="title">
                            <p>Add Content</p>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="field">
                                <!-- Select Tournament -->
                                <label for="id_turnamen">Select Tournament</label>
                                <select class="form-select" id="id_turnamen" name="id_turnamen" required>
                                    <?php
                                    $conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

                                    if (!$conn) {
                                        die("Connection failed: " . mysqli_connect_error());
                                    }

                                    $query_turnamen = "SELECT id_turnamen, nama_turnamen FROM turnamen";
                                    $result_turnamen = mysqli_query($conn, $query_turnamen);

                                    if (mysqli_num_rows($result_turnamen) > 0) {
                                        while ($row_turnamen = mysqli_fetch_assoc($result_turnamen)) {
                                            echo "<option value='" . $row_turnamen['id_turnamen'] . "'>" . $row_turnamen['nama_turnamen'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value='' disabled selected>No tournaments available</option>";
                                    }

                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="field">
                                <!-- Description -->
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="field">
                                <!-- Image -->
                                <label for="image">Image</label>
                                <input type="file" class="form-control gambar" id="image" name="image" accept="image/*" onchange="previewImage()" required>
                                <img src="#" alt="Preview" class="img-preview" style="display: none; max-width: 100%; height: auto;">
                            </div>
                            <div class="field">
                                <!-- Video URL -->
                                <label for="video_url">Video URL</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" placeholder="https://example.com" required>
                            </div>
                            <button type="submit" class="button success outline w-100">Add Content</button>
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
                imgPreview.style.display = "block";
                imgPreview.src = oFREvent.target.result;
            };
        }
    </script>
</body>
</html>
