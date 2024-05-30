<?php
session_start();

// Redirect ke halaman login jika pengguna belum login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Include file functions.php
require 'functions.php';

// Cek apakah id_turnamen_tim sudah diterima dari halaman sebelumnya
if (!isset($_GET["id"])) {
    header("Location: DashboardTurnamen.php");
    exit;
}

// Ambil id_turnamen_tim dari parameter URL
$id_turnamen_tim = $_GET["id"];

// Panggil fungsi hapusTurnamenTim dengan id_turnamen_tim sebagai parameter
if (hapusTurnamenTim($id_turnamen_tim) > 0) {
    echo "<script> 
            alert('Data turnamen tim berhasil dihapus!');
            document.location.href = 'DashboardTurnamen.php';
        </script>";
} else {
    echo "<script>
            alert('Data turnamen tim gagal dihapus!');
            document.location.href = 'DashboardTurnamen.php';
        </script>";
}
?>


