<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

require 'functions.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $data = [
        "id_content" => $_POST["id_content"],
        "id_turnamen" => $_POST["id_turnamen"],
        "description" => $_POST["description"],
        "video_url" => $_POST["video_url"]
    ];

    // Check if a new image is uploaded
    $image = isset($_FILES['image'])? $_FILES['image'] : null;

    // Edit content
    $result = editKonten($data, $image);

    // Check if editing was successful
    if ($result > 0) {
        // Redirect to content list page or display success message
        header("Location: DashboardKonten.php");
        exit;
    } elseif ($result === 0) {
        echo "No rows affected. Content may not exist.";
    } else {
        echo "Error editing content: ". mysqli_error($conn);
    }
}

// If not submitted or editing failed, retrieve content data for the form
if (isset($_GET['id_content'])) {
    $id_content = $_GET['id_content'];
    $content = query("SELECT * FROM content WHERE id_content = $id_content");
    if (!$content) {
        echo "Content not found.";
        exit;
    }
} else {
    echo "Content ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
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
                    <a href="daftarKonten.php" onclick="return confirm('Are you sure you want to go back?')"><i class="fas fa-times"></i></a>
                </div>
                <div class="row">
                    <div class="cell-10 offset-1">
                        <div class="title">
                            <p>Form Edit Content</p>
                        </div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id_content" value="<?php echo $content[0]['id_content'];?>">
                            <div class="field">
                                <label for="id_turnamen">Tournament ID:</label>
                                <input type="text" id="id_turnamen" name="id_turnamen" value="<?php echo $content[0]['id_turnamen'];?>">
                            </div>
                            <div class="field">
                                <label for="description">Description:</label>
                                <textarea id="description" name="description"><?php echo $content[0]['description'];?></textarea>
                            </div>
                            <div class="field">
                                <label for="video_url">Video URL:</label>
                                <input type="text" id="video_url" name="video_url" value="<?php echo $content[0]['video_url'];?>">
                            </div>
                            <div class="field">
                                <label for="image">Image:</label>
                                <input type="file" id="image" name="image">
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