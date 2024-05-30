<?php
require 'functions.php'; // Sesuaikan dengan lokasi file functions.php

if (isset($_GET['id_content'])) {
    $id_content = $_GET['id_content'];
    
    if (hapusKonten($id_content) > 0) {
        echo "
            <script>
                alert('konten berhasil dihapus!');
                document.location.href = 'DashboardKonten.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('konten gagal dihapus!');
                document.location.href = 'DashboardKonten.php';
            </script>
        ";
    }
} else {
    header("Location: DashboardKonten.php");
    exit;
}
