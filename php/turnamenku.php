<?php
include 'functions.php';
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Get user's team ID from database
$id_user = $_SESSION['user_id'];
$query_user_tim = "SELECT id_tim FROM user WHERE id_user = $id_user";
$result_user_tim = mysqli_query($conn, $query_user_tim);
$row_user_tim = mysqli_fetch_assoc($result_user_tim);
$user_tim_id = $row_user_tim['id_tim'];
// Determine if user has a team or not
$user_has_team =!is_null($user_tim_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

       .container {
            max-width: 600px;
        }

       .welcome-text {
            font-size: 24px;
            font-weight: bold;
        }

       .action-btns {
            margin-top: 20px;
        }
    </style>
</head>

<body>
<section class="nav mb-5">
        <?php include '../NavigationBar/nav1.php'; ?>
    </section>
<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="mb-4">Selamat Datang, <?php echo $_SESSION["username"];?>!</h1>
                <p class="welcome-text">Silahkan Daftarkan Tim Anda.</p>
            </div>
        </div>

        <div class="row justify-content-center action-btns">
            <div class="col-md-4">
                <?php if (!$user_has_team) :?>
                    <a href="tambahTimUser.php" class="btn btn-primary btn-block">Daftarkan Tim</a>
                <?php endif;?>
            </div>

            <div class="col-md-4">
                <?php if ($user_has_team) :?>
                    <a href="tambahPesertaUser.php" class="btn btn-success btn-block">Tambah Peserta</a>
                <?php else :?>
                    <button type="button" class="btn btn-outline-secondary btn-block disabled">Tambah Peserta</button>
                    <p class="mt-3 text-center">Anda belum menambahkan tim. Silakan tambahkan tim terlebih dahulu untuk dapat menambahkan peserta.</p>
                <?php endif;?>
            </div>

            <div class="col-md-4">
                <a href="tambahEventTurnamenUser.php" class="btn btn-info btn-block">Join Turnamen</a>
            </div>
        </div>
    </div>

    <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
    crossorigin="anonymous"
  ></script>
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
</body>

</html>